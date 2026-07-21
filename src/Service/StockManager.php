<?php
declare(strict_types=1);

namespace App\Service;

use Predis\Client;
use Tracy\Debugger;

final class StockManager
{
    public function __construct(
        private Client $redis
    ) {}

    public function syncStock(string $sku, int $stock): void
    {
        $this->redis->set("stock:{$sku}", $stock);
    }

    public function reserve(string $sku, int $quantity): bool
    {
        $script = <<<LUA
        local current_stock = tonumber(redis.call('GET', KEYS[1]))
        if current_stock and current_stock >= tonumber(ARGV[1]) then
            redis.call('DECRBY', KEYS[1], ARGV[1])
            return 1
        end
        return 0
LUA;

        try {
            $result = $this->redis->eval($script, 1, "stock:{$sku}", (string) $quantity);
        } catch (\Throwable $e) {
            \Tracy\Debugger::log($e, 'lua');
            throw $e;
        }
        return (bool)$result;
    }

    public function getCurrentStock(string $sku): int
    {
        return (int)$this->redis->get("stock:{$sku}");
    }
}