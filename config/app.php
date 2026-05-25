<?php
return [
    'name'     => $_ENV['APP_NAME']  ?? 'MBOA School',
    'url'      => $_ENV['APP_URL']   ?? 'http://localhost/erp-scolaire/public',
    'env'      => $_ENV['APP_ENV']   ?? 'production',
    'debug'    => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    'timezone' => 'Africa/Abidjan',
    'locale'   => 'fr_CI',
];
