<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use App\Controller\AppController;

//get request
$request = Request::createFromGlobals();

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

$container->compile();

function checkMethod($method, $in)
{
    if(!in_array($method, $in)) {
        throw new \Exception('method not allowed');
    }
}

//app
if($request->get('p') == 'update') {
    checkMethod($request->getMethod(), ['POST']);

    $controller = new AppController();
    $controller->setContainer($container);

    $response = $controller->updateHeartbeatAction($request);
} else {
    $data = json_encode([
        'status' => 'error',
        'message' => 'no such action',
        'code' => 404,
    ]);
}

($response ?? new Response($data, $data['code'] ?? 200, [
    'Content-Type' => $contentType ?? 'application/json'
]))->send();
