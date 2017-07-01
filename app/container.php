<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

//read config
$parametersFile = __DIR__.'/../config/app.yml';
try {
    $parameters = Yaml::parse(file_get_contents($parametersFile))['config'];
} catch (\Exception $exc) {
    throw new \Exception(sprintf('Something is wrong with the file %s! Maybe the file does not exist?', $parametersFile));
}

//build container
$container = new ContainerBuilder();

//app params
foreach ($parameters as $key => $value) {
    $container->setParameter('app.'.$key, $value);
}

//base params
$container
    ->setParameter('root_dir', __DIR__.'/..')
;

//services
$container
    ->register('app.updater', 'App\Service\HeartbeatUpdater')
    ->addArgument('%root_dir%/config/sites.yml')
    ->addArgument('%root_dir%/data/store.json')
;

$container
    ->register('app.secure_string', 'App\Service\SecureStringService')
;

$container->compile();

return $container;
