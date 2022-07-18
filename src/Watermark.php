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

use Imagine\Image\AbstractFont;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class Watermark
{
    public static function scanAssets() {
        $watermark = new Watermark();
        if (!is_dir(TL_ROOT . '/assets/images/watermark'))
            mkdir(TL_ROOT . '/assets/images/watermark');
        foreach (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'] as $f)
            if (!is_dir(TL_ROOT . '/assets/images/watermark/' . $f))
                mkdir(TL_ROOT . '/assets/images/watermark/' . $f);
        foreach (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'] as $f) {
            $fd = @opendir(TL_ROOT . '/assets/images/' . $f);
            if ($fd) {
                while ($file = readdir($fd)) {
                    if ($file == '.' || $file == '..') continue;
                    $full = TL_ROOT . '/assets/images/' . $f . '/' . $file;
                    $wm = TL_ROOT . '/assets/images/watermark/' . $f . '/' . substr($file, 0, strlen($file) - 5);
                    if (is_file($full) && (!file_exists($wm) || filemtime($wm) != filemtime($full))) {
                        list($width, $height, $type, $attr) = getimagesize($full);
                        if ($width > 285 && $height > 285) {
                            if ($watermark->applyWatermarks($full)) {
                                touch($wm, filemtime($full));
                            }
                        }
                    }
                }
                closedir($fd);
            }
        }
    }
    
    public function applyWatermarks($strTarget)
    {
        if (!file_exists($strTarget)) return;
        try {
            $this->addWatermark($strTarget);
        }
        catch (\Exception $ex) {

        }
        return true;
    }


    /**
     * Add an image element to the uploaded image
     *
     * @param $strFile
     */
    protected function addWatermark($strFile)
    {
        $imagine = new \Imagine\Gd\Imagine();

        $objImage = $imagine->open($strFile);
        $objImageWatermark = $imagine->open(TL_ROOT . '/files/wasserzeichen.png');
        $aspect = $objImage->getSize()->getWidth() / $objImage->getSize()->getHeight();
        if ($aspect > 1) {
            $wHeight = $objImage->getSize()->getHeight() / 5;
            $Margin = $objImage->getSize()->getHeight() / 20;
            $wWidth = $objImageWatermark->getSize()->getWidth() * $wHeight / $objImageWatermark->getSize()->getHeight();
        } else {
            $wWidth = $objImage->getSize()->getWidth() / 5;
            $Margin = $objImage->getSize()->getWidth() / 20;
            $wHeight = $objImageWatermark->getSize()->getHeight() * $wWidth / $objImageWatermark->getSize()->getWidth();
        }
        $objImageWatermark->resize(new Box($wWidth, $wHeight));
        $arrMargin = deserialize($objAction->margins);
        $objPosition = new Point(
            $objImage->getSize()->getWidth() - $wWidth - $Margin,
            $objImage->getSize()->getHeight() - $wHeight - $Margin);
        $objImage->paste($objImageWatermark, $objPosition);
        $objImage->save($strFile);
    }
}