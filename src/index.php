<?php

/*
 * LenPRO/Collage project
 */

require 'vendor/autoload.php';

$container = include './config/container.php';

$app = new \Slim\App($container);

$app->run();
