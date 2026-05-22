<?php
return [
    'host'     => $_ENV['DB_HOST']     ?? 'localhost',
    'port'     => $_ENV['DB_PORT']     ?? '5432',
    'database' => $_ENV['DB_DATABASE'] ?? 'erp_scolaire',
    'username' => $_ENV['DB_USERNAME'] ?? 'postgres',
    'password' => $_ENV['DB_PASSWORD'] ?? 'PbYmK7IKPQjoxAQZEQJeeJe0CNBbGZVQ',
    'charset'  => 'utf8',
    'driver'   => 'pgsql'
];
