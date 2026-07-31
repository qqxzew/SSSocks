<?php
declare(strict_types=1);
namespace App\Presenter;

use Nette\Application\UI\Presenter;
abstract class AdminPresenter extends Presenter
{
    protected function startup(): void
    {
        parent::startup();

        if(!$this->getUser()->isLoggedIn()){
            $this->getHttpResponse()->setCode(401);
            $this->sendJson(['error' => 'Unauthorized']);
        }
        if(!$this->getUser()->isInRole('admin')){
            $this->getHttpResponse()->setCode(403);
            $this->sendJson(['error' => 'Forbidden']);
        }
    }

}