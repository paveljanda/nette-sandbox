<?php

declare(strict_types=1);

namespace Tests;

use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';

class ExampleTest extends TestCase
{
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	public function testSomething(): void
	{
		Assert::true(true);
	}
}

(new ExampleTest($container))->run();
