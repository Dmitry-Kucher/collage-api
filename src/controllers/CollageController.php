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

    public function testAction(Request $request, Response $response) {
        $testImages = [
            "https://greatartists.ru/contents/uploads/2018/01/wOggGWhh5T4.jpg",
            "https://greatartists.ru/contents/uploads/2018/01/4yLQtZ0fVfw.jpg",
            "https://greatartists.ru/contents/uploads/2018/01/YVGrECii0dA.jpg",
            "https://greatartists.ru/contents/uploads/2018/01/pAZK5cYYwy4.jpg",
            "https://greatartists.ru/contents/uploads/2018/01/LkSQon9mHc.jpg",
            "https://greatartists.ru/contents/uploads/2018/01/RUR-sg9k7rA.jpg",
            "https://greatartists.ru/contents/uploads/2017/12/WT72kdIdGB4.jpg",
            "https://greatartists.ru/contents/uploads/2017/12/JGBqhVZf8yc.jpg",
            "https://greatartists.ru/contents/uploads/2017/12/zixoLours64.jpg",
            "https://greatartists.ru/contents/uploads/2017/12/TQJGwLHkdBk.jpg"
        ];

        $imageResponse = $this->collageMaker
            ->setImages($testImages)
            ->testCollage();

        return $response;
    }
}
