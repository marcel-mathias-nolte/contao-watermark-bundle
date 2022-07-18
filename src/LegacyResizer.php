<?php

namespace MarcelMathiasNolte\WatermarkBundle;

use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\File;
use Contao\Image as LegacyImage;
use Contao\Image\DeferredImageInterface;
use Contao\Image\DeferredResizer as ImageResizer;
use Contao\Image\ImageInterface;
use Contao\Image\ResizeConfiguration;
use Contao\Image\ResizeOptions;
use Contao\System;
use Imagine\Exception\RuntimeException as ImagineRuntimeException;

class LegacyResizer  extends ImageResizer implements \Contao\CoreBundle\Framework\FrameworkAwareInterface
{
    use FrameworkAwareTrait;
    protected \Contao\CoreBundle\Framework\FrameworkAwareInterface $parent;
    protected \MarcelMathiasNolte\WatermarkBundle\Watermark $watermark;

    public function __construct(\Contao\CoreBundle\Framework\FrameworkAwareInterface $parent)
    {
        $this->parent = $parent;
        $this->watermark = new \MarcelMathiasNolte\WatermarkBundle\Watermark();
    }

    public function resize(ImageInterface $image, ResizeConfiguration $config, ResizeOptions $options): ImageInterface
    {
        $tmp = $this->parent->resize($image, $config, $options);
        return $tmp;
        $sourcePath = $image->getPath();
        $targetPath = $tmp->getPath();
        if ($sourcePath != $targetPath) {
            $this->watermark->applyWatermarks($sourcePath, $targetPath);
        }
        return $tmp;
    }

    public function resizeDeferredImage(DeferredImageInterface $image, bool $blocking = true): ?ImageInterface
    {
        return $this->parent->resizeDeferredImage($image, $blocking);
    }

}
