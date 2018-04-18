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

$container['settings'] = function ($container) {
    $config = [
        'displayErrorDetails' => true,
    ];
    return $config;
};

return $container;
