<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use Tracy\Debugger;
//production change
Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log');
Debugger::$strictMode = true;

try {
    throw new \Exception('Test');
} catch (\Exception $e) {
    Debugger::log($e, 'checkout');

    echo "<h1>Oops! Something went wrong with your order.</h1>";
}