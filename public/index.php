<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use Latte\Engine;

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();
$stockManager = $container->getByType(App\Service\StockManager::class);
$sku = 'SOCK-FUN-CAT';

// 1. Имитируем, что мы загрузили 2 пары носков на склад
$stockManager->syncStock($sku, 2);

// 2. Покупатель 1 забирает 2 пары
$attempt1 = $stockManager->reserve($sku, 2);

// 3. Покупатель 2 пытается забрать еще 1 пару
$attempt2 = $stockManager->reserve($sku, 1);

echo "<h3>Тест Highload Резервирования</h3>";
echo "Попытка 1 (Купить 2): " . ($attempt1 ? '<span style="color:green">УСПЕХ</span>' : 'ОТКАЗ') . "<br>";
echo "Попытка 2 (Купить 1): " . ($attempt2 ? '<span style="color:green">УСПЕХ</span>' : '<span style="color:red">ОТКАЗ (Нет на складе)</span>') . "<br>";
echo "Остаток в Redis: " . $stockManager->getCurrentStock($sku) . " шт.";

exit; // Останавливаем выполнение, чтобы Latte пока не рисовал витрину
