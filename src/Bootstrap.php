<?php
declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;

final class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator();

        $env = parse_ini_file(__DIR__ . '/../.env');
        $configurator->addDynamicParameters(['env' => $env]);

        $configurator->setDebugMode(($env['APP_ENV'] ?? 'production') === 'development');
        $configurator->enableTracy(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp');

        $configurator->addConfig(__DIR__ . '/../config/common.neon');

        return $configurator;
    }
}