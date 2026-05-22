<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::getInstance();
    }

    // -------------------------------------------------------
    // Lecture
    // -------------------------------------------------------

    public static function find(int $id): ?array
    {
        $table = static::$table;
        $pk    = static::$primaryKey;
        $stmt  = static::db()->prepare("SELECT * FROM `$table` WHERE `$pk` = ? AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findOrFail(int $id): array
    {
        $row = static::find($id);
        if (!$row) {
            http_response_code(404);
            throw new \RuntimeException("Enregistrement #{$id} introuvable dans " . static::$table);
        }
        return $row;
    }

    public static function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $table = static::$table;
        $stmt  = static::db()->query("SELECT * FROM `$table` WHERE deleted_at IS NULL ORDER BY `$orderBy` $direction");
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // QueryBuilder léger
    // -------------------------------------------------------

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::db(), static::$table);
    }

    public static function where(string $column, mixed $value, string $op = '='): QueryBuilder
    {
        return static::query()->where($column, $value, $op);
    }

    // -------------------------------------------------------
    // Écriture
    // -------------------------------------------------------

    public static function create(array $data): int
    {
        $data = static::addTimestamps($data);
        $table = static::$table;
        $cols  = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $vals  = implode(', ', array_fill(0, count($data), '?'));
        $stmt  = static::db()->prepare("INSERT INTO `$table` ($cols) VALUES ($vals)");
        $stmt->execute(array_values($data));
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $table  = static::$table;
        $pk     = static::$primaryKey;
        $sets   = implode(', ', array_map(fn($c) => "`$c` = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;
        $stmt   = static::db()->prepare("UPDATE `$table` SET $sets WHERE `$pk` = ?");
        return $stmt->execute($values);
    }

    public static function delete(int $id): bool
    {
        // Soft delete si colonne deleted_at existe
        $table = static::$table;
        $pk    = static::$primaryKey;
        $stmt  = static::db()->prepare("UPDATE `$table` SET deleted_at = NOW() WHERE `$pk` = ?");
        return $stmt->execute([$id]);
    }

    public static function forceDelete(int $id): bool
    {
        $table = static::$table;
        $pk    = static::$primaryKey;
        $stmt  = static::db()->prepare("DELETE FROM `$table` WHERE `$pk` = ?");
        return $stmt->execute([$id]);
    }

    public static function count(array $conditions = []): int
    {
        $table = static::$table;
        $sql   = "SELECT COUNT(*) FROM `$table` WHERE deleted_at IS NULL";
        $params = [];
        foreach ($conditions as $col => $val) {
            $sql .= " AND `$col` = ?";
            $params[] = $val;
        }
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function sum(string $column, array $conditions = []): float
    {
        $table = static::$table;
        $sql   = "SELECT COALESCE(SUM(`$column`), 0) FROM `$table` WHERE deleted_at IS NULL";
        $params = [];
        foreach ($conditions as $col => $val) {
            $sql .= " AND `$col` = ?";
            $params[] = $val;
        }
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return (float) $stmt->fetchColumn();
    }

    // -------------------------------------------------------
    // Raw SQL
    // -------------------------------------------------------

    public static function raw(string $sql, array $params = []): array
    {
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function rawFirst(string $sql, array $params = []): ?array
    {
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    private static function addTimestamps(array $data): array
    {
        $now = date('Y-m-d H:i:s');
        if (!isset($data['created_at'])) {
            $data['created_at'] = $now;
        }
        return $data;
    }

    public static function paginate(int $page, int $perPage, array $conditions = [], string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $offset = ($page - 1) * $perPage;
        $table  = static::$table;
        $where  = 'deleted_at IS NULL';
        $params = [];
        foreach ($conditions as $col => $val) {
            $where .= " AND `$col` = ?";
            $params[] = $val;
        }
        $total = (int) static::db()->prepare("SELECT COUNT(*) FROM `$table` WHERE $where")->execute($params) ? 0 : 0;
        $countStmt = static::db()->prepare("SELECT COUNT(*) FROM `$table` WHERE $where");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = static::db()->prepare("SELECT * FROM `$table` WHERE $where ORDER BY `$orderBy` $direction LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => max(1, (int) ceil($total / $perPage)),
        ];
    }
}
