<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

if($request->get('p') == 'update') {

} else {
    $data = json_encode([
        'status' => 'error',
        'message' => 'no such action',
        'code' => 404,
    ]);
}

$response = new Response($data, $data['code'] ?? 200, [
    'Content-Type' => $contentType ?? 'application/json'
]);
$response->send();
