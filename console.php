#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\GenerateSecretCommand;
use App\Command\CheckSiteCommand;

$application = new Application();
$container = require __DIR__.'/app/container.php';

$generateSecreteCommand = new GenerateSecretCommand();
$generateSecreteCommand->setContainer($container);
$application->add($generateSecreteCommand);

$checkSiteCommand = new CheckSiteCommand();
$checkSiteCommand->setContainer($container);
$application->add($checkSiteCommand);

$application->run();
