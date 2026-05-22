<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// Autoloader Composer
require BASE_PATH . '/vendor/autoload.php';

// Variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// Config
$appConfig = require BASE_PATH . '/config/app.php';
define('APP_URL', rtrim($appConfig['url'], '/'));
define('APP_NAME', $appConfig['name']);
define('APP_DEBUG', $appConfig['debug']);

// En développement uniquement : invalider l'OPcache pour toujours avoir le code frais
// Ne jamais faire cela en production (pénalité de performance importante)
if (APP_DEBUG && ($_ENV['APP_ENV'] ?? 'production') === 'development' && function_exists('opcache_reset')) {
    opcache_reset();
}

// Timezone
date_default_timezone_set($appConfig['timezone']);

// Gestion des erreurs
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        error_log("[$errno] $errstr in $errfile:$errline");
    });
}

// Session
\App\Core\Session::start();

// Requête
$request = new \App\Core\Request();

// CSRF check
\App\Core\CSRF::check($request);

// Routes
$router = new \App\Core\Router();
require BASE_PATH . '/routes/web.php';

// Dispatch
$router->dispatch($request);
