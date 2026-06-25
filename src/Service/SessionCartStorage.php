<?php

declare(strict_types=1);

namespace App\Service;

final class SessionCartStorage
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem(string $sku, int $quantity): void
    {
        if (isset($_SESSION['cart'][$sku])) {
            $_SESSION['cart'][$sku] += $quantity;
        } else {
            $_SESSION['cart'][$sku] = $quantity;
        }
    }

    public function getItems(): array
    {
        return $_SESSION['cart'];
    }
}