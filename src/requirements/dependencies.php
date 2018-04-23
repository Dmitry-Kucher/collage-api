<?php

/*
 * LenPRO/Collage project
 */

use LenPRO\Lib\Collage\CollageMaker;
use \Intervention\Image\ImageManager;

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

return $container;
