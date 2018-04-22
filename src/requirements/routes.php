<?php

/*
 * LenPRO/Collage project
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/health/', function (Request $request, Response $response) {
    $arr = [
        'data' => 'ok'
    ];
    $newResponse = $response->withJson($arr);
    return $newResponse;
});
