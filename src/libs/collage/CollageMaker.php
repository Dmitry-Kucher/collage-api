<?php
declare(strict_types=1);

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Lib\Collage;

use Interop\Container\ContainerInterface;
use LenPRO\Lib\BaseLib;

class CollageMaker extends BaseLib {
    private $config = [
        'collage' => [
            'width' => 860,
            'height' => 860,
        ]
    ];
    private $imageManager;

    public function __construct(ContainerInterface $container, array $config = []) {
        $this->setup($config);
        $this->imageManager = $container->get('imageManager');
        parent::__construct($container);
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

        $this->config['images'] = $images;
        return $this;
    }

    public function makeCollage() {
        $collageConfig = $this->config['collage'];
        $images = $this->config['images'];

        $resultImages = [];
        foreach ($images as $image) {
            $resultImages[] = $this->imageManager
                ->make($image)
                ->fit(400, 400, function ($constraint) {
//                    $constraint->upsize();
                });
        }
        $canvas = $this->imageManager->canvas($collageConfig['width'], $collageConfig['height']);
        $canvas->insert($resultImages[0], 'top-left', 20, 20);
        $canvas->insert($resultImages[1], 'top-left', 440, 20);
        $canvas->insert($resultImages[2], 'top-left', 20, 440);
        $canvas->insert($resultImages[3], 'top-left', 440, 440);
        $canvas->save('./test.jpg');

        return $canvas;
    }
}
