<?php

/*
 * LenPRO/Collage project
 */

use LenPRO\Lib\Collage\CollageMaker;
use \Intervention\Image\ImageManager;
use \Bnf\Slim3Psr15\CallableResolver;

$container = $app->getContainer();

$container['collageMaker'] = function ($container) {
    $collageMaker = new CollageMaker($container);
    return $collageMaker;
};

$container['imageManager'] = function ($container) {
    $manager = new ImageManager([
        'driver' => 'gd'
    ]);
    return $manager;
};

$container['callableResolver'] = function ($container) {
    return new CallableResolver($container);
};

return $container;
