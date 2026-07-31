<?php
declare(strict_types=1);

namespace App\Presenter;

use App\Repository\ProductRepository;
use Nette\Application\UI\Presenter;

final class HomePresenter extends Presenter
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function renderDefault(): void
    {
        $this->template->setParameters(['products' => $this->productRepository->getAllProducts()]);
    }
}
