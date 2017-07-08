<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\AppController;

//get request
$request = Request::createFromGlobals();

$container = require __DIR__.'/../app/container.php';

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
} else if($request->get('p') == 'show') {
    checkMethod($request->getMethod(), ['GET']);

    $controller = new AppController();
    $controller->setContainer($container);

    $response = $controller->showHeartbeatAction($request);
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
