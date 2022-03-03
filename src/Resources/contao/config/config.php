<?php

/*
 * This file is part of ContaoTextAndImageAsModuleBundle.
 *
 * @package   ContaoTextAndImageAsModuleBundle
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015-2020
 * @website	  https://github.com/marcel-mathias-nolte
 * @license   LGPL-3.0-or-later
 */

namespace MarcelMathiasNolte\WatermarkBundle;

$GLOBALS['TL_HOOKS']['getImage'][] = ['MarcelMathiasNolte\WatermarkBundle\Watermark', 'getImage'];
$GLOBALS['TL_HOOKS']['executeResize'][] = ['MarcelMathiasNolte\WatermarkBundle\Watermark', 'executeResize'];
