<?php
namespace App\Core;

class AuditLog
{
    /**
     * Enregistre une action dans activity_logs.
     *
     * @param string     $action       Ex: 'create', 'update', 'delete', 'annuler'
     * @param string     $module       Ex: 'notes', 'paiements', 'eleves'
     * @param int|null   $entiteId     ID de l'entité concernée
     * @param string|null $entiteType  Nom de la table ou du modèle
     * @param array|null $avant        Données avant modification
     * @param array|null $apres        Données après modification
     */
    public static function log(
        string  $action,
        string  $module,
        ?int    $entiteId   = null,
        ?string $entiteType = null,
        ?array  $avant      = null,
        ?array  $apres      = null
    ): void {
        try {
            $db = Database::getInstance();
            $db->prepare("
                INSERT INTO activity_logs
                    (user_id, action, module, entite_type, entite_id, donnees_avant, donnees_apres, ip)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                Session::get('user_id'),
                $action,
                $module,
                $entiteType,
                $entiteId,
                $avant !== null ? json_encode($avant, JSON_UNESCAPED_UNICODE) : null,
                $apres  !== null ? json_encode($apres,  JSON_UNESCAPED_UNICODE) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (\Throwable) {
            // Non bloquant — ne jamais faire planter l'appli pour un log
        }
    }
}
