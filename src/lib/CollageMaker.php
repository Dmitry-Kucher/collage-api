<?php
declare(strict_types=1);

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Collage;

class CollageMaker {
    private $config = [];

    public function __construct(array $config) {
        $this->setup($config);
    }

    private function setup(array $config): void {
        $this->config = array_merge($this->config, $config);
    }

    public function setCollageSize(int $width, int $height): self {
        $this->config['collage']['width'] = $width;
        $this->config['collage']['height'] = $height;

        return $this;
    }

    public function setImages(array $images): self {
        if (4 != count($images)) {
            throw new \Exception("unsupported count of images");
        }
        foreach ($images as $image) {
        }
        $this->config['images'] = $images;
        return $this;
    }

    public function makeCollage() {
        $collageConfig = $this->config['collage'];
        $images = $this->config['images'];
    }
}
