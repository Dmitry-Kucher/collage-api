<?php

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Lib\Base;

use Interop\Container\ContainerInterface;

class BaseLib {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
}
