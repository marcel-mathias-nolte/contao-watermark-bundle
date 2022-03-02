<?php

/*
 * This file is part of WatermarkBundle.
 *
 * @package   WatermarkBundle
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015-2020
 * @website	  https://github.com/marcel-mathias-nolte
 * @license   LGPL-3.0-or-later
 */

namespace MarcelMathiasNolte\WatermarkBundle;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Image;
use Contao\File;

/**
 * @Hook("getImage")
 */
class WatermarkService
{
    public function __invoke(
        string $originalPath,
        int $width,
        int $height,
        string $mode,
        string $cacheName,
        File $file,
        string $targetPath,
        Image $imageObject
    ): ?string
    {
        // Return the path to a custom image
        var_dump(func_get_args()); die();

        return null;
    }
}
