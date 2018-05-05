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
            'width' => 800,
            'height' => 2100,
            'minHeight' => 400,
            'indent' => 10,
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

    public function setCollageConfig(int $width, int $height, int $minHeight, int $indent): self {
        $this->config['collage']['width'] = $width;
        $this->config['collage']['height'] = $height;
        $this->config['collage']['minHeight'] = $minHeight;
        $this->config['collage']['indent'] = $indent;

        return $this;
    }

    public function setImages(array $images): self {
//        if (4 != count($images)) {
//            throw new \Exception("unsupported count of images");
//        }

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

    public function testCollage() {
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

        $canvas->save('./test-2.jpg');
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

    protected function linearPartitionTable($seq, $k) {
        $n = count($seq);

        $table = array_fill(0, $n, array_fill(0, $k, 0));
        $solution = array_fill(0, $n - 1, array_fill(0, $k - 1, 0));

        for ($i = 0; $i < $n; $i++) {
            $table[$i][0] = $seq[$i] + ($i ? $table[$i - 1][0] : 0);
        }

        for ($j = 0; $j < $k; $j++) {
            $table[0][$j] = $seq[0];
        }

        for ($i = 1; $i < $n; $i++) {
            for ($j = 1; $j < $k; $j++) {
                $current_min = null;
                $minx = PHP_INT_MAX;

                for ($x = 0; $x < $i; $x++) {
                    $cost = max($table[$x][$j - 1], $table[$i][0] - $table[$x][0]);
                    if ($current_min === null || $cost < $current_min) {
                        $current_min = $cost;
                        $minx = $x;
                    }
                }

                $table[$i][$j] = $current_min;
                $solution[$i - 1][$j - 1] = $minx;
            }
        }

        return [$table, $solution];
    }
}
