<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Nette\Configurator;
use Tester\Environment;

Environment::setup();

$configurator = new Configurator;
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');

return $configurator->createContainer();
