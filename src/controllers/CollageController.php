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

        $this->collageMaker->setImages($postParams['images']);
    }
}
