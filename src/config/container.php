<?php

/*
 * LenPRO/Collage project
 */

use Intervention\Image\ImageManager;

$container = new \Slim\Container;

$container['imageManager'] = function ($container) {
    $manager = new ImageManager([
        'driver' => 'gd'
    ]);
    return $manager;
};

return $container;
