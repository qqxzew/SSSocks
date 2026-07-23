<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Latte\Engine;

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/api/cart/add' && $method === 'POST') {
    $container->getByType(App\Service\StockManager::class)->syncStock('SOCK-CLASSIC-BLK', 999);

    $cartController = $container->getByType(App\Controller\CartController::class);
    $cartController->addToCart();
    exit;
}

if ($uri === '/api/csrf-token' && $method === 'GET'){
    header('Content-Type: application/json');
    $csrfManager = $container->getByType(App\Security\CsrfTokenManager::class);
    echo json_encode(['token' => $csrfManager->getOrCreateToken()]);
    exit;
}

if ($uri === '/api/cart' && $method === 'GET') {
    $cartController = $container->getByType(App\Controller\CartController::class);
    $cartController->viewCart();
    exit;
}

if ($uri === '/api/login' && $method === 'POST') {
    $container->getByType(App\Controller\AuthController::class)->login();
    exit;
}

$productRepository = $container->getByType(App\Repository\ProductRepository::class);
$products = $productRepository->getAllProducts();

$latte = new Engine();
$latte->setCacheDirectory(__DIR__ . '/../temp');
$latte->render(__DIR__ . '/../src/Templates/home.latte', [
    'products' => $products
]);