<?php

/*
 * LenPRO/Collage project
 */

require 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

$container = include './config/container.php';

$app = new \Slim\App($container);

$app->run();



// create an image manager instance with favored driver
$manager = new ImageManager([
    'driver' => 'gd'
]);

// to finally create image instances
$image = $manager
    ->make('https://greatartists.ru/contents/uploads/2018/04/fRLBai2Y07w.jpg')
    ->resize(300, 200);

$image->save('./test.jpg');
