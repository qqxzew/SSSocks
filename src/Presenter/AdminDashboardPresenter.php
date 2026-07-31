<?php
declare(strict_types=1);

namespace App\Presenter;

final class AdminDashboardPresenter extends AdminPresenter
{
    function actionDefault(): void
    {
        $this->sendJson(['status' => 'admin area']);
    }
}