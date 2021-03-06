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

use MarcelMathiasNolte\WatermarkBundle\DependencyInjection\WatermarkBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WatermarkBundle extends Bundle
{

    public function getContainerExtension(): WatermarkBundleExtension
    {
        return new WatermarkBundleExtension();
    }
}
