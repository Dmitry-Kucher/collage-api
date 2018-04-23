<?php

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Controllers;

use Interop\Container\ContainerInterface;

class CollageController extends BaseController {
    public function __construct(ContainerInterface $container) {
        $this->imageManager = $container['imageManager'];
    }

    public function createAction() {
        var_dump($this->imageManager);
    }
}
