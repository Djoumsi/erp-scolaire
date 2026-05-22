<?php
namespace App\Core;

use PDO;

class Auth
{
    private static ?array $user        = null;
    private static ?array $permissions = null;

    // -------------------------------------------------------
    // Connexion / Déconnexion
    // -------------------------------------------------------

    public static function attempt(string $login, string $password): bool
    {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT u.*, r.nom as role_nom, r.niveau as role_niveau
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.login = ? AND u.actif = 1 AND u.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            self::logAttempt(null, 'echec');
            return false;
        }

        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user',    $user);

        // Charger les permissions
        self::loadPermissions($user['role_id']);

        // Mise à jour dernière connexion
        $db->prepare("UPDATE users SET derniere_connexion = NOW() WHERE id = ?")
           ->execute([$user['id']]);

        self::logAttempt($user['id'], 'login');
        return true;
    }

    public static function logout(): void
    {
        if (self::check()) {
            self::logAttempt(self::id(), 'logout');
        }
        Session::destroy();
        self::$user        = null;
        self::$permissions = null;
    }

    // -------------------------------------------------------
    // Vérifications
    // -------------------------------------------------------

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::$user && self::check()) {
            self::$user = Session::get('user');
        }
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::user() ? (int) self::user()['id'] : null;
    }

    public static function role(): ?string
    {
        return self::user()['role_nom'] ?? null;
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function etablissementId(): ?int
    {
        $id = self::user()['etablissement_id'] ?? null;
        return $id ? (int) $id : null;
    }

    // -------------------------------------------------------
    // Permissions RBAC
    // -------------------------------------------------------

    public static function can(string $permission): bool
    {
        if (self::isSuperAdmin()) {
            return true;
        }
        if (self::$permissions === null) {
            self::loadPermissions(self::user()['role_id'] ?? 0);
        }
        return in_array($permission, self::$permissions ?? [], true);
    }

    public static function cannot(string $permission): bool
    {
        return !self::can($permission);
    }

    public static function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array(self::role(), $roles, true);
    }

    // -------------------------------------------------------
    // Helpers privés
    // -------------------------------------------------------

    private static function loadPermissions(int $roleId): void
    {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT CONCAT(p.module, '.', p.action) as perm
            FROM role_permissions rp
            JOIN permissions p ON p.id = rp.permission_id
            WHERE rp.role_id = ?
        ");
        $stmt->execute([$roleId]);
        self::$permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        Session::set('permissions', self::$permissions);
    }

    private static function logAttempt(?int $userId, string $action): void
    {
        try {
            $db = Database::getInstance();
            $db->prepare("INSERT INTO auth_logs (user_id, ip, user_agent, action) VALUES (?, ?, ?, ?)")
               ->execute([
                   $userId,
                   $_SERVER['REMOTE_ADDR'] ?? '',
                   substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                   $action,
               ]);
        } catch (\Throwable) {
            // Non bloquant
        }
    }

    // -------------------------------------------------------
    // Génération hash (utilitaire)
    // -------------------------------------------------------

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
