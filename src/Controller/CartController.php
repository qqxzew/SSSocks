<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\StockManager;
use App\Service\SessionCartStorage;
final class CartController
{
    public function __construct(
        private StockManager $stockManager,
        private SessionCartStorage $CartStorage
    ) {}

    public function addToCart(): void
    {
        header('Content-type: application/json');

        $rawInput = file_get_contents('php://input');
        $data = json_decode((string)$rawInput, true);

        $sku = $data['sku'] ?? null;
        $quantity = $data['quantity'] ?? null;

        if (!is_string($sku) || trim($sku) === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid SKU']);
            return;
        }

        if (!is_int($quantity) || $quantity <=0) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantity cant be =< 0']);
            return;
        }

        $success = $this->stockManager->reserve($sku, $quantity);

        if ($success) {
            try {
                $this->CartStorage->addItem($sku, $quantity);
            } catch (\Throwable $e){
                error_log($e->getMessage());
                http_response_code(500);
                echo json_encode(['error' => 'Internal server error']);
                return;
            }
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Item added to cart',
                'reserved_sku' => $sku,
                'quantity' => $quantity,
                'cart_total_items' => array_sum($this->CartStorage->getItems())
            ]);
        } else {
            http_response_code(409);
            echo json_encode([
                'status' => 'error',
                'message' => 'Item not available in stock',
            ]);
        }
    }

    public function viewCart(): void
    {
        header('Content-type: application/json');
        echo json_encode([
            'cart' => $this->CartStorage->getItems()
        ]);
    }
}