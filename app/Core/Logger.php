<?php
namespace App\Core;

class Logger
{
    private static function write(string $level, string $message, array $context = []): void
    {
        $dir  = BASE_PATH . '/storage/logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file  = $dir . '/app-' . date('Y-m-d') . '.log';
        $date  = date('Y-m-d H:i:s');
        $ip    = $_SERVER['REMOTE_ADDR'] ?? '-';
        $user  = Session::get('user_id') ? 'user#' . Session::get('user_id') : 'guest';
        $ctx   = $context ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
        $entry = "[{$date}] [{$level}] [{$ip}] [{$user}] {$message}{$ctx}" . PHP_EOL;
        file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    public static function security(string $message, array $context = []): void
    {
        self::write('SECURITY', $message, $context);
    }
}
