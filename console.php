<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\GenerateSecretCommand;

$application = new Application();
$container = require __DIR__.'/app/container.php';

$generateSecreteCommand = new GenerateSecretCommand();
$generateSecreteCommand->setContainer($container);
$application->add($generateSecreteCommand);

$application->run();
