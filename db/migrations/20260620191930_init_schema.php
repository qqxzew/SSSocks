<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitSchema extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'], ['unique' => true])
            ->create();

        $table = $this->table('products');
        $table->addColumn('sku', 'string', ['limit' => 64])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('price', 'integer')
            ->addColumn('stock', 'integer', ['default' => 0])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['sku'], ['unique' => true])
            ->create();

        $table = $this->table('orders');
        $table->addColumn('user_id', 'integer',  ['signed' => false])
            ->addColumn('total_price', 'integer',  ['signed' => false])
            ->addColumn('status', 'enum', ['values' => ['pending', 'confirmed', 'cancelled', 'compensated', 'failed'], 'default' => 'pending'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['user_id'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();

        $table = $this->table('order_items');
        $table->addColumn('order_id', 'integer', ['signed' => false])
            ->addColumn('product_id', 'integer', ['signed' => false])
            ->addColumn('quantity', 'integer')
            ->addColumn('price', 'integer')
            ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();

        $table = $this->table('idempotency_keys');
        $table->addColumn('idempotency_key', 'string', ['limit' => 255])
            ->addColumn('response_code', 'integer', ['null' => true])
            ->addColumn('response_body', 'text', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['idempotency_key'], ['unique' => true])
            ->create();

        $table = $this->table('reservations');
        $table->addColumn('order_id', 'integer', ['signed' => false])
            ->addColumn('product_id', 'integer', ['signed' => false])
            ->addColumn('quantity', 'integer')
            ->addColumn('status', 'enum', ['values' => ['pending', 'confirmed', 'cancelled', 'compensated', 'failed'], 'default' => 'pending'])
            ->addColumn('redis_key', 'string', ['limit' => 255])
            ->addIndex(['order_id'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();

    }

}
