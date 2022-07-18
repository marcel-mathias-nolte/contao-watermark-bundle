<?php

namespace MarcelMathiasNolte\WatermarkBundle;

use Contao\Image\ImageInterface;
use Contao\Image\PictureConfiguration;
use Contao\Image\PictureInterface;
use Contao\Image\ResizeConfiguration;
use Contao\Image\ResizeOptions;
use Contao\StringUtil;

class PictureFactory  implements \Contao\CoreBundle\Image\PictureFactoryInterface
{
    protected \Contao\CoreBundle\Image\PictureFactoryInterface $parent;

    public function __construct(\Contao\CoreBundle\Image\PictureFactoryInterface $parent)
    {
        $this->parent = $parent;
    }

    public function setDefaultDensities($densities)
    {
        return $this->parent->setDefaultDensities($densities);
    }

    /**
     * Sets the predefined image sizes.
     */
    public function setPredefinedSizes(array $predefinedSizes)
    {
        $this->parent->setPredefinedSizes($predefinedSizes);
    }

    public function create($path, $size = null, ResizeOptions $options = null): PictureInterface
    {


        $tmp = $this->parent->create($path, $size, $options);

        return $tmp;
    }
}
