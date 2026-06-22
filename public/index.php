<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Latte\Engine;

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/api/cart/add' && $method === 'POST') {
    $cartController = $container->getByType(App\Controller\CartController::class);
    $cartController->addToCart();
    exit;
}

$productRepository = $container->getByType(App\Repository\ProductRepository::class);
$products = $productRepository->getAllProducts();

$latte = new Engine();
$latte->setCacheDirectory(__DIR__ . '/../temp');
$latte->render(__DIR__ . '/../src/Templates/home.latte', [
    'products' => $products
]);