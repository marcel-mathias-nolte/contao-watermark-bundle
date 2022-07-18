<?php

namespace MarcelMathiasNolte\WatermarkBundle;

use Contao\Image\DeferredResizerInterface;
use Contao\Image\Image;
use Contao\Image\ImageInterface;
use Contao\Image\ImportantPart;
use Contao\Image\ResizeConfiguration;
use Contao\Image\ResizeOptions;
use Contao\StringUtil;

class ImageFactory implements \Contao\CoreBundle\Image\ImageFactoryInterface
{
    protected \Contao\CoreBundle\Image\ImageFactoryInterface $parent;

    public function __construct(\Contao\CoreBundle\Image\ImageFactoryInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Sets the predefined image sizes.
     */
    public function setPredefinedSizes(array $predefinedSizes): void
    {
        $this->parent->setPredefinedSizes($predefinedSizes);
    }

    /**
     * @param int|array|string|ResizeConfiguration|null $size
     */
    public function create($path, $size = null, $options = null): ImageInterface
    {
        $tmp = $this->parent->create($path, $size, $options);
        return $tmp;
    }

    public function getImportantPartFromLegacyMode(ImageInterface $image, $mode): ImportantPart
    {
        return $this->parent->getImportantPartFromLegacyMode($image, $mode);
    }

}
