<?php

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Controllers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CollageController extends BaseController {
    private $collageMaker;

    public function __construct(ContainerInterface $container) {
        $this->collageMaker = $container['collageMaker'];
    }

    public function createAction(Request $request, Response $response) {
        $postParams = $request->getParsedBody();

        $imageResponse = $this->collageMaker
            ->setImages($postParams['images'])
            ->makeCollage();

        $newResponse = $response
            ->withHeader('Content-Type', $imageResponse->mime())
            ->write($imageResponse->response('jpg'));
        return $newResponse;
    }
}
