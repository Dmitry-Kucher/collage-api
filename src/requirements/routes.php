<?php

/*
 * LenPRO/Collage project
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use LenPRO\Lib\http\StatusCodes;

$app->get('/health/', function (Request $request, Response $response) {
    $arr = [
        'data' => 'ok'
    ];
    $newResponse = $response->withJson($arr);
    return $newResponse;
});

$app->group(
    '/collage',
    function () {
        $this->get('/create/', function (Request $request, Response $response) {
            $newResponse = $response
                ->withStatus(StatusCodes::HTTP_METHOD_NOT_ALLOWED)
                ->withHeader('Allow', 'POST');
            return $newResponse;
        });

        $this->post('/create/', function (Request $request, Response $response) {
        });
    }
);
