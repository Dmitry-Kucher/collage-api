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
        $config = [];

        if (is_array($postParams['config'])) {
            $config = array_merge($config, $postParams['config']);
        }
        if (is_array($postParams['images'])) {
            $config['images'] = $postParams['images'];
        } else {
            throw new \Exception('Can\'t create collage without images');
        }
        $this->collageMaker->setup($config);

        $imageResponse = $this->collageMaker
            ->makeCollage();

        $newResponse = $response
            ->withHeader('Content-Type', $imageResponse->mime())
            ->write($imageResponse->response('jpg'));
        return $newResponse;
    }
}
