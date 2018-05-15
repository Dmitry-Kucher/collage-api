<?php
declare(strict_types=1);

/*
 * LenPRO/Collage project
 */

namespace LenPRO\Lib\Collage;

use Interop\Container\ContainerInterface;
use LenPRO\Lib\BaseLib;

class CollageMaker extends BaseLib {
    const IMAGE_PATH = './results/';

    private $config = [
        'collage' => [
            'width' => 800,
            'minHeight' => 400,
            'indent' => 10,
        ],
        'response' => [
            'type' => CollageResponseTypes::IMAGE,
            'imageType' => CollageImageTypes::PNG,
        ],
        'images' => [],
    ];
    private $imageManager;

    public function __construct(ContainerInterface $container, array $config = []) {
        $this->setup($config);
        $this->imageManager = $container->get('imageManager');
        parent::__construct($container);
    }

    public function setup(array $config): self {
        if (!empty($config)) {
            $collageConfig = $this->config['collage'];
            $this->config['collage'] = array_merge($collageConfig, $config['collage']);

            $responseConfig = $this->config['response'];
            $this->config['response'] = array_merge($responseConfig, $config['response']);

            $imagesConfig = $this->config['images'];
            $this->config['images'] = array_merge($imagesConfig, $config['images']);
        }

        return $this;
    }

    public function setImages(array $images): self {
        $this->config['images'] = $images;
        return $this;
    }

    public function getCanvasAndSaveCollage() {
        $images = $this->config['images'];
        $collageConfig = $this->config['collage'];

        $imagesAspectRatio = [];
        $imagesMeta = [];
        $offsetY = $collageConfig['indent'];
        $sumWidth = 0;
        foreach ($images as $image) {
            $image = $this->imageManager
                ->make($image);
            $image->heighten($collageConfig['minHeight']);
            $height = $image->height();
            $width = $image->width();
            $sumWidth += $width;
            $aspectRatio = floor($height / $width * 100);
            $imagesAspectRatio[] = $aspectRatio;
            $imagesMeta[] = [
                'aspectRatio' => $aspectRatio,
                'image' => $image,
                'width' => $width,
                'height' => $height,
            ];
        }

        $rows = (int)round($sumWidth / $collageConfig['width']);
        $averageWidth = $sumWidth / $rows;
        $linearPartitionResult = $this->linearPartition($imagesAspectRatio, $rows);
        $preparedImages = [];

        foreach ($linearPartitionResult as $aspectRatioRows) {
            $offsetX = $collageConfig['indent'];

            $sumWidthByRow = 0;
            foreach ($aspectRatioRows as $aspectRatioAfterSorting) {
                $imageKey = array_search($aspectRatioAfterSorting, $imagesAspectRatio);
                $imageMeta = $imagesMeta[$imageKey];

                $sumWidthByRow += $imageMeta['width'];
            }

            foreach ($aspectRatioRows as $aspectRatioAfterSorting) {
                $imageKey = array_search($aspectRatioAfterSorting, $imagesAspectRatio);
                unset($imagesAspectRatio[$imageKey]);
                $imageMeta = $imagesMeta[$imageKey];

                $calculatedWidth = $imageMeta['width'] / $sumWidthByRow * $averageWidth;
                $imageToInsert = $imageMeta['image']->widen((int)$calculatedWidth);
                $preparedImages[] = [
                    'image' => $imageToInsert,
                    'offsetX' => $offsetX,
                    'offsetY' => $offsetY
                ];

                $offsetX += $imageMeta['image']->width() + $collageConfig['indent'];
                $offsetYNew = $imageMeta['image']->height();
            }
            $offsetY += $offsetYNew + $collageConfig['indent'];
        }

        $canvas = $this->imageManager->canvas($offsetX, $offsetY);

        foreach ($preparedImages as $collageImage) {
            $canvas->insert($collageImage['image'], 'top-left', $collageImage['offsetX'], $collageImage['offsetY']);
        }

        $imageName = self::IMAGE_PATH . time() . '.' . $this->config['response']['imageType'];
        $canvas->save($imageName);

        return $canvas;
    }

    public function getCollage() {
        $responseType = $this->config['response']['type'];
        $canvas = $this->getCanvasAndSaveCollage();

        if ($responseType === CollageResponseTypes::IMAGE) {
            return $canvas;
        } elseif ($responseType === CollageResponseTypes::LINK) {
            //TODO:
            //Add image link logic here
        } else {
            throw new \Exception('Wrong Response type format');
        }


        return $canvas;
    }

    protected function linearPartition(array $sequence, int $parts) {
        if ($parts <= 0) {
            return [];
        }

        $sequenceCounter = count($sequence) - 1;
        if ($parts > $sequenceCounter) {
            return array_map(function ($element) {
                return [$element];
            }, $sequence);
        }

        list($table, $solution) = $this->linearPartitionTable($sequence, $parts);
        $parts = $parts - 2;
        $ans = [];

        while ($parts >= 0) {
            $ans = array_merge(
                [
                    array_slice(
                        $sequence,
                        $solution[$sequenceCounter - 1][$parts] + 1,
                        $sequenceCounter - $solution[$sequenceCounter - 1][$parts]
                    )
                ],
                $ans
            );
            $sequenceCounter = $solution[$sequenceCounter - 1][$parts];
            $parts = $parts - 1;
        }

        return array_merge([array_slice($sequence, 0, $sequenceCounter + 1)], $ans);
    }

    protected function linearPartitionTable(array $sequence, int $parts) {
        $sequenceCounter = count($sequence);

        $table = array_fill(0, $sequenceCounter, array_fill(0, $parts, 0));
        $solution = array_fill(0, $sequenceCounter - 1, array_fill(0, $parts - 1, 0));

        for ($i = 0; $i < $sequenceCounter; $i++) {
            $table[$i][0] = $sequence[$i] + ($i ? $table[$i - 1][0] : 0);
        }

        for ($j = 0; $j < $parts; $j++) {
            $table[0][$j] = $sequence[0];
        }

        for ($i = 1; $i < $sequenceCounter; $i++) {
            for ($j = 1; $j < $parts; $j++) {
                $currentMin = null;
                $minX = PHP_INT_MAX;

                for ($x = 0; $x < $i; $x++) {
                    $cost = max($table[$x][$j - 1], $table[$i][0] - $table[$x][0]);
                    if ($currentMin === null || $cost < $currentMin) {
                        $currentMin = $cost;
                        $minX = $x;
                    }
                }

                $table[$i][$j] = $currentMin;
                $solution[$i - 1][$j - 1] = $minX;
            }
        }

        return [$table, $solution];
    }
}
