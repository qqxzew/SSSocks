<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = App\Bootstrap::boot();

$container = $configurator->createContainer();

$security = $container->getByType(App\Security\PasswordManager::class);


