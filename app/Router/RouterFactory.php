<?php

declare(strict_types=1);

namespace App\Router;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	public static function createRouter(): IRouter
	{
		$router = new RouteList;
		$router[] = new Route('<presenter>/<action>', 'Homepage:default');

		return $router;
	}
}
