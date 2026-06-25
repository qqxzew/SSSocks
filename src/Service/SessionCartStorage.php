<?php
declare(strict_types=1);

namespace App\Service;

use Nette\Http\Session;

final class SessionCartStorage
{
    public function __construct(
        private Session $session
    ) {}

    public function addItem(string $sku, int $quantity): void
    {
        $section = $this->session->getSection('cart');

        $items = $section->items ?? [];

        if (isset($items[$sku])) {
            $items[$sku] += $quantity;
        } else {
            $items[$sku] = $quantity;
        }

        $section->items = $items;
    }

    public function getItems(): array
    {
        $section = $this->session->getSection('cart');
        return $section->items ?? [];
    }
}