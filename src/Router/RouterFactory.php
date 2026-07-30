<?php
declare(strict_types=1);

namespace App\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    public static function createRouter(): RouteList
    {
        $router = new RouteList();

        $router->addRoute('api/cart/add', 'Cart:add');
        $router->addRoute('api/csrf-token', 'Cart:csrfToken');
        $router->addRoute('api/cart', 'Cart:view');
        $router->addRoute('api/login', 'Auth:login');
        $router->addRoute('api/logout', 'Auth:logout');
        $router->addRoute('/', 'Home:default');

        return $router;
    }
}
