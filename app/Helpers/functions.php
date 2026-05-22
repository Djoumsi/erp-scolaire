<?php

use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;

// -------------------------------------------------------
// URLs
// -------------------------------------------------------

function url(string $path = ''): string
{
    $base = rtrim(APP_URL, '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $rel      = 'assets/' . ltrim($path, '/');
    $fullPath = BASE_PATH . '/public/' . $rel;
    $ver      = file_exists($fullPath) ? filemtime($fullPath) : 1;
    return url($rel) . '?v=' . $ver;
}

function redirect(string $url): never
{
    if (!str_starts_with($url, 'http')) {
        $url = url($url);
    }
    header("Location: $url");
    exit;
}

function abort(int $code, string $message = ''): never
{
    http_response_code($code);
    $titles = [403 => 'Accès refusé', 404 => 'Page introuvable', 500 => 'Erreur serveur', 419 => 'Session expirée'];
    $title  = $titles[$code] ?? 'Erreur';
    echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>{$title}</title>
    <link rel='stylesheet' href='" . asset('css/bootstrap.min.css') . "'>
    </head><body class='bg-light d-flex align-items-center justify-content-center' style='min-height:100vh'>
    <div class='text-center'><h1 class='display-1 fw-bold text-danger'>{$code}</h1>
    <h2 class='mb-4'>{$title}</h2><p class='text-muted'>{$message}</p>
    <a href='" . url('/dashboard') . "' class='btn btn-primary'>Retour au tableau de bord</a>
    </div></body></html>";
    exit;
}

// -------------------------------------------------------
// Sécurité
// -------------------------------------------------------

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function csrf_field(): string
{
    return CSRF::field();
}

function csrf_token(): string
{
    return CSRF::token();
}

function old(string $key, mixed $default = ''): mixed
{
    $old = Session::get('old_input', []);
    return $old[$key] ?? $default;
}

function error_field(string $key): string
{
    $errors = Session::get('validation_errors', []);
    if (isset($errors[$key])) {
        $msg = implode(' ', $errors[$key]);
        return "<div class='invalid-feedback d-block'>" . e($msg) . "</div>";
    }
    return '';
}

function has_error(string $key): string
{
    $errors = Session::get('validation_errors', []);
    return isset($errors[$key]) ? 'is-invalid' : '';
}

// -------------------------------------------------------
// Auth helpers
// -------------------------------------------------------

function auth(): ?array
{
    return Auth::user();
}

function can(string $perm): bool
{
    return Auth::can($perm);
}

// -------------------------------------------------------
// Formatage
// -------------------------------------------------------

function money(float $amount, string $devise = 'XOF'): string
{
    if ($devise === 'XOF') {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
    return number_format($amount, 2, ',', ' ') . ' ' . $devise;
}

function dateFormat(string $date, string $format = 'd/m/Y'): string
{
    if (!$date) return '-';
    return (new \DateTime($date))->format($format);
}

function dateTimeFormat(string $date): string
{
    return dateFormat($date, 'd/m/Y à H:i');
}

function sexeLabel(string $sexe): string
{
    return $sexe === 'M' ? 'Masculin' : 'Féminin';
}

function statutBadge(string $statut, ?array $customMap = null): string
{
    $map = $customMap ?? [
        'actif'       => ['success', 'Actif'],
        'diplome'     => ['primary', 'Diplômé'],
        'transfere'   => ['warning', 'Transféré'],
        'exclu'       => ['danger',  'Exclu'],
        'archive'     => ['secondary','Archivé'],
        'inscrit'     => ['success', 'Inscrit'],
        'en_attente'  => ['warning', 'En attente'],
        'annule'      => ['danger',  'Annulé'],
        'non_paye'    => ['danger',  'Non payé'],
        'partiel'     => ['warning', 'Partiel'],
        'solde'       => ['success', 'Soldé'],
        'exonere'     => ['info',    'Exonéré'],
        'present'     => ['success', 'Présent'],
        'absent'      => ['danger',  'Absent'],
        'retard'      => ['warning', 'Retard'],
        'excuse'      => ['info',    'Excusé'],
    ];
    [$color, $label] = $map[$statut] ?? ['secondary', ucfirst($statut)];
    return "<span class='badge bg-{$color}'>{$label}</span>";
}

function appreciation(float $moyenne): string
{
    return match(true) {
        $moyenne >= 18 => 'Excellent',
        $moyenne >= 16 => 'Très Bien',
        $moyenne >= 14 => 'Bien',
        $moyenne >= 12 => 'Assez Bien',
        $moyenne >= 10 => 'Passable',
        default        => 'Insuffisant',
    };
}

function mentionBac(float $moyenne): string
{
    return match(true) {
        $moyenne >= 16 => 'Très Bien',
        $moyenne >= 14 => 'Bien',
        $moyenne >= 12 => 'Assez Bien',
        $moyenne >= 10 => 'Passable',
        default        => 'Ajourné',
    };
}

// -------------------------------------------------------
// Debug
// -------------------------------------------------------

function dd(mixed ...$vars): never
{
    echo '<pre style="background:#1e1e2e;color:#cdd6f4;padding:1rem;font-size:.85rem;border-radius:8px;margin:1rem">';
    foreach ($vars as $v) {
        var_dump($v);
    }
    echo '</pre>';
    exit;
}

function dump(mixed ...$vars): void
{
    echo '<pre style="background:#1e1e2e;color:#cdd6f4;padding:1rem;font-size:.85rem;border-radius:8px;margin:1rem">';
    foreach ($vars as $v) {
        var_dump($v);
    }
    echo '</pre>';
}

// -------------------------------------------------------
// Numérotation reçus
// -------------------------------------------------------

function generateNumeroRecu(string $codeEtab, int $annee): string
{
    $db   = \App\Core\Database::getInstance();
    $stmt = $db->prepare("SELECT COUNT(*) FROM paiements WHERE YEAR(created_at) = ?");
    $stmt->execute([$annee]);
    $seq = $stmt->fetchColumn() + 1;
    return strtoupper($codeEtab) . '-' . $annee . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
}

// -------------------------------------------------------
// Upload
// -------------------------------------------------------

function uploadFile(array $file, string $folder, array $allowed = ['jpg','jpeg','png']): ?string
{
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Taille maximale : 5 Mo
    if ($file['size'] > 5 * 1024 * 1024) {
        \App\Core\Logger::warning('Upload refusé : fichier trop lourd', ['size' => $file['size'], 'folder' => $folder]);
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        \App\Core\Logger::warning('Upload refusé : extension non autorisée', ['ext' => $ext, 'allowed' => $allowed]);
        return null;
    }

    // Vérification mime-type réel (protection contre l'usurpation d'extension)
    $mimeMap = [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'gif'  => ['image/gif'],
        'pdf'  => ['application/pdf'],
        'csv'  => ['text/csv', 'text/plain', 'application/csv'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
    ];

    if (function_exists('finfo_open') && isset($mimeMap[$ext])) {
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($realMime, $mimeMap[$ext], true)) {
            \App\Core\Logger::security('Upload bloqué : mime-type suspect', ['ext' => $ext, 'real_mime' => $realMime]);
            return null;
        }
    }

    $dir = BASE_PATH . '/storage/uploads/' . trim($folder, '/');
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $name = uniqid('', true) . '.' . $ext;
    $dest = $dir . '/' . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'storage/uploads/' . trim($folder, '/') . '/' . $name;
    }
    return null;
}

// -------------------------------------------------------
// Pagination HTML
// -------------------------------------------------------

function paginationLinks(array $pagination, string $baseUrl): string
{
    if ($pagination['last_page'] <= 1) return '';
    $html  = '<nav><ul class="pagination pagination-sm mb-0">';
    $curr  = $pagination['current_page'];
    $last  = $pagination['last_page'];
    $sep   = str_contains($baseUrl, '?') ? '&' : '?';

    $html .= '<li class="page-item' . ($curr <= 1 ? ' disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . e($baseUrl . $sep . 'page=' . ($curr - 1)) . '">&laquo;</a></li>';

    for ($i = max(1, $curr - 2); $i <= min($last, $curr + 2); $i++) {
        $html .= '<li class="page-item' . ($i === $curr ? ' active' : '') . '">';
        $html .= '<a class="page-link" href="' . e($baseUrl . $sep . 'page=' . $i) . '">' . $i . '</a></li>';
    }

    $html .= '<li class="page-item' . ($curr >= $last ? ' disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . e($baseUrl . $sep . 'page=' . ($curr + 1)) . '">&raquo;</a></li>';
    $html .= '</ul></nav>';
    return $html;
}

// Nettoyage des erreurs de validation après affichage
function clearValidation(): void
{
    Session::delete('validation_errors');
    Session::delete('old_input');
}
