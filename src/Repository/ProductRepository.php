<?php
declare(strict_types=1);

namespace App\Repository;

use Nette\Database\Connection;

final class ProductRepository
{
    public function __construct(
        private Connection $db
    ) {}

    public function getAllProducts(): array
    {
        return $this->db->fetchAll('SELECT * FROM products');
    }
}