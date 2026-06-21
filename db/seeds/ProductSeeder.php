<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'sku' => 'SOCK-CLASSIC-BLK',
                'name' => 'Classic premium black socs',
                'price' => 1500, // 15.00
                'stock' => 100,
            ],
            [
                'sku' => 'SOCK-SPORT-WHT',
                'name' => 'Sport socks in white',
                'price' => 1250, // 12.50
                'stock' => 50,
            ],
            [
                'sku' => 'SOCK-FUN-CAT',
                'name' => 'Socks with cats',
                'price' => 1800, // 18.00
                'stock' => 10,
            ]
        ];
        $this->table('products')->insert($data)->saveData();
    }
}
