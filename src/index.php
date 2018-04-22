<?php

/*
 * LenPRO/Collage project
 */

require './vendor/autoload.php';

$container = include './requirements/container.php';
$container['callableResolver'] = function ($container) {
    return new \Bnf\Slim3Psr15\CallableResolver($container);
};

$app = new \Slim\App($container);

$trailingSlashMiddleware = new Middlewares\TrailingSlash(true);
$trailingSlashMiddleware->redirect(true);

$app->add($trailingSlashMiddleware);

require_once './requirements/dependencies.php';
require_once './requirements/routes.php';

$app->run();
