<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Latte\Engine;

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();

$productRepository = $container->getByType(App\Repository\ProductRepository::class);
$products = $productRepository->getAllProducts();

$latte = new Engine();
$latte->setCacheDirectory(__DIR__ . '/../temp');
$latte->render(__DIR__ . '/../src/Templates/home.latte', [
    'products' => $products
]);