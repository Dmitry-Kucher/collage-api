<?php

/*
 * LenPRO/Collage project
 */

require './vendor/autoload.php';

$config = include './requirements/config.php';

$app = new \Slim\App($config);

$trailingSlashMiddleware = new Middlewares\TrailingSlash(true);
$trailingSlashMiddleware->redirect(true);

$app->add($trailingSlashMiddleware);

require_once './requirements/dependencies.php';
require_once './requirements/routes.php';

$app->run();
