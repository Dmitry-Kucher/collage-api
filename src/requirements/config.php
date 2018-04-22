<?php

/*
 * LenPRO/Collage project
 */

use \Bnf\Slim3Psr15\CallableResolver;

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'debug' => true,
    ],
];

$container = new \Slim\Container($config);

$container['callableResolver'] = function ($container) {
    return new CallableResolver($container);
};

return $container;
