<?php

/*
 * LenPRO/Collage project
 */

use LenPRO\Lib\Collage\CollageMaker;

$container = $app->getContainer();

$container['collageMaker'] = function ($container) {
    $collageMaker = new CollageMaker();
    return $collageMaker;
};
