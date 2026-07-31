<?php
declare(strict_types=1);
namespace App\Presenter;

use App\Security\CsrfTokenManager;
use App\Service\SessionCartStorage;
use App\Service\StockManager;
use Nette\Application\UI\Presenter;

final class CartPresenter extends Presenter
{
    public function __construct(
        private StockManager       $stockManager,
        private SessionCartStorage $CartStorage,
        private CsrfTokenManager   $csrfManager
    ){}

    public function actionAdd(): void
    {
        $token = $this->getHttpRequest()->getHeader('X-CSRF-Token') ?? null;

        if ($this->csrfManager->validateToken($token) === false) {
            $this->getHttpResponse()->setCode(403);
            $this->sendJson(['error' => 'Invalid CSRF token']);

        }

        $rawInput = file_get_contents('php://input');
        $data = json_decode((string)$rawInput, true);

        $sku = $data['sku'] ?? null;
        $quantity = $data['quantity'] ?? null;

        if (!is_string($sku) || trim($sku) === '') {
            $this->getHttpResponse()->setCode(400);
            $this->sendJson(['error' => 'Invalid SKU']);

        }

        if (!is_int($quantity) || $quantity <= 0) {
            $this->getHttpResponse()->setCode(400);
            $this->sendJson(['error' => 'Quantity cant be =< 0']);

        }
        try {
            $success = $this->stockManager->reserve($sku, $quantity);
        } catch (\Throwable $e) {
            \Tracy\Debugger::log($e, 'lua');
            $this->getHttpResponse()->setCode(500);
            $this->sendJson(['error' => 'Internal server error']);

        }
        if ($success) {
            try {
                $this->CartStorage->addItem($sku, $quantity);
            } catch (\Throwable $e) {
                \Tracy\Debugger::log($e, 'checkout');
                $this->getHttpResponse()->setCode(500);
                $this->sendJson(['error' => 'Internal server error']);

            }
            $this->getHttpResponse()->setCode(200);
            $this->sendJson([
                'status' => 'success',
                'message' => 'Item added to cart',
                'reserved_sku' => $sku,
                'quantity' => $quantity,
                'cart_total_items' => array_sum($this->CartStorage->getItems())
            ]);
        } else {
            $this->getHttpResponse()->setCode(409);
            $this->sendJson([
                'status' => 'error',
                'message' => 'Item not available in stock',
            ]);
        }
    }

    public function actionView(): void
    {
        $this->sendJson([
            'cart' => $this->CartStorage->getItems()
        ]);
    }

    public function actionCsrfToken(): void
    {
        $this->sendJson([
            'token' => $this->csrfManager->getOrCreateToken()
        ]);
    }
}