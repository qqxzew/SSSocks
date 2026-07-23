<?php
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    throw new \RuntimeException('.env file not found.');
}
$env = parse_ini_file($envPath);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'mysql',
            'name' => $env['MYSQL_DATABASE'],
            'user' => $env['MYSQL_USER'],
            'pass' => $env['MYSQL_PASSWORD'],
            'port' => 3306,
            'charset' => 'utf8mb4',
        ]
    ],
    'version_order' => 'creation'
];