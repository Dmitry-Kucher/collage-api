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
            "https://greatartists.ru/contents/uploads/2018/04/fRLBai2Y07w.jpg",
            "https://greatartists.ru/contents/uploads/2018/04/fRLBai2Y07w.jpg",
            "https://greatartists.ru/contents/uploads/2018/04/fRLBai2Y07w.jpg",
            "https://greatartists.ru/contents/uploads/2018/04/FN5YFRME-jE.jpg",
            "https://greatartists.ru/contents/uploads/2018/04/FN5YFRME-jE.jpg",
            "https://greatartists.ru/contents/uploads/2018/04/AGytJVguK-4.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/osOVyteW62Y.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/aUhoG3kTrzY.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/aUhoG3kTrzY.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/aUhoG3kTrzY.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/J5HmT1HlWtU.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/J5HmT1HlWtU.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/J5HmT1HlWtU.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/xeEp7ZTB_eI.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/xeEp7ZTB_eI.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/xUuo8XUDCic.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/7Wd91yWg1_s.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/7Wd91yWg1_s.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/7Wd91yWg1_s.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/7Wd91yWg1_s.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/RVyCYqN_Co8.jpg",
            "https://greatartists.ru/contents/uploads/2018/03/RVyCYqN_Co8.jpg",
        ];

        $imageResponse = $this->collageMaker
            ->setImages($testImages)
            ->testCollage();

        return $response;
    }
}
