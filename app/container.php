<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
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

$container
    ->register('app.slack', 'App\Service\SlackService')
    ->addArgument('%app.slack_url%')
;

$container
    ->register('app.ifttt_webhook', 'App\Service\IftttWebhookService')
    ->addArgument('%app.ifttt_webhook_url%')
;

$container
    ->register('app.notifier', 'App\Service\NotifierService')
    ->addArgument(new Reference('app.updater'))
    ->addArgument(new Reference('app.slack'))
    ->addArgument('%app.notify.slack%')
    ->addArgument(new Reference('app.ifttt_webhook'))
    ->addArgument('%app.notify.ifttt_webhook%')
;

$container->compile();

return $container;
