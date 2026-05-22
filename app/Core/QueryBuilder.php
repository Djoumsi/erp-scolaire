<?php
namespace App\Core;

use PDO;

class QueryBuilder
{
    private PDO    $db;
    private string $table;
    private array  $wheres    = [];
    private array  $params    = [];
    private array  $joins     = [];
    private string $orderBy   = '';
    private string $groupBy   = '';
    private string $having    = '';
    private int    $limitVal  = 0;
    private int    $offsetVal = 0;
    private string $select    = '*';

    public function __construct(PDO $db, string $table)
    {
        $this->db    = $db;
        $this->table = $table;
    }

    public function select(string $columns): static
    {
        $this->select = $columns;
        return $this;
    }

    public function where(string $column, mixed $value, string $op = '='): static
    {
        $this->wheres[] = "`$column` $op ?";
        $this->params[] = $value;
        return $this;
    }

    public function whereRaw(string $sql, array $params = []): static
    {
        $this->wheres[] = $sql;
        $this->params   = array_merge($this->params, $params);
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $placeholders   = implode(',', array_fill(0, count($values), '?'));
        $this->wheres[] = "`$column` IN ($placeholders)";
        $this->params   = array_merge($this->params, $values);
        return $this;
    }

    public function whereNull(string $column): static
    {
        $this->wheres[] = "`$column` IS NULL";
        return $this;
    }

    public function whereNotNull(string $column): static
    {
        $this->wheres[] = "`$column` IS NOT NULL";
        return $this;
    }

    public function join(string $table, string $on, string $type = 'INNER'): static
    {
        $this->joins[] = "$type JOIN `$table` ON $on";
        return $this;
    }

    public function leftJoin(string $table, string $on): static
    {
        return $this->join($table, $on, 'LEFT');
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = "ORDER BY `$column` $direction";
        return $this;
    }

    public function orderByRaw(string $raw): static
    {
        $this->orderBy = "ORDER BY $raw";
        return $this;
    }

    public function groupBy(string $column): static
    {
        $this->groupBy = "GROUP BY `$column`";
        return $this;
    }

    public function having(string $raw): static
    {
        $this->having = "HAVING $raw";
        return $this;
    }

    public function limit(int $n): static
    {
        $this->limitVal = $n;
        return $this;
    }

    public function offset(int $n): static
    {
        $this->offsetVal = $n;
        return $this;
    }

    public function get(): array
    {
        $stmt = $this->db->prepare($this->buildSql());
        $stmt->execute($this->params);
        return $stmt->fetchAll();
    }

    public function first(): ?array
    {
        $this->limitVal = 1;
        $stmt = $this->db->prepare($this->buildSql());
        $stmt->execute($this->params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function count(): int
    {
        $backup       = $this->select;
        $this->select = 'COUNT(*) as cnt';
        $stmt         = $this->db->prepare($this->buildSql(true));
        $stmt->execute($this->params);
        $this->select = $backup;
        return (int) $stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $total            = $this->count();
        $this->limitVal   = $perPage;
        $this->offsetVal  = ($page - 1) * $perPage;
        $items            = $this->get();
        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => max(1, (int) ceil($total / $perPage)),
        ];
    }

    private function buildSql(bool $forCount = false): string
    {
        $sql  = "SELECT {$this->select} FROM `{$this->table}`";
        $sql .= $this->joins  ? ' ' . implode(' ', $this->joins)  : '';
        $sql .= $this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '';
        if (!$forCount) {
            $sql .= $this->groupBy ? ' ' . $this->groupBy : '';
            $sql .= $this->having  ? ' ' . $this->having  : '';
            $sql .= $this->orderBy ? ' ' . $this->orderBy : '';
            $sql .= $this->limitVal  ? " LIMIT {$this->limitVal}"    : '';
            $sql .= $this->offsetVal ? " OFFSET {$this->offsetVal}"  : '';
        }
        return $sql;
    }
}
