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

use Contao\Config;
use Contao\Image;
use Contao\File;
use Contao\Image\Image as NewImage;
use Contao\Image\ResizeConfiguration;
use Contao\System;
use Imagine\Gd\Imagine as GdImagine;

class Watermark extends \OMOSde\ContaoOmImagineBundle\Imagine
{
    public function executeResize(\Contao\Image $parent) {
        return null;
        $image = $this->prepareImage($parent);
        $resizeConfig = $this->prepareResizeConfig($parent);
    }


    /**
     * Prepare image object.
     *
     * @return NewImage
     */
    protected function prepareImage(\Contao\Image $parent)
    {
        if ($parent->fileObj->isSvgImage)
        {
            $imagine = System::getContainer()->get('contao.image.imagine_svg');
        }
        else
        {
            $imagine = System::getContainer()->get('contao.image.imagine');
        }

        $image = new NewImage($parent->strRootDir . '/' . $parent->fileObj->path, $imagine, System::getContainer()->get('filesystem'));
        $image->setImportantPart($parent->prepareImportantPart());

        return $image;
    }

    /**
     * Prepare resize configuration object.
     *
     * @return ResizeConfiguration
     */
    protected function prepareResizeConfig(\Contao\Image $parent)
    {
        $resizeConfig = new ResizeConfiguration();
        $resizeConfig->setWidth($parent->targetWidth);
        $resizeConfig->setHeight($parent->targetHeight);
        $resizeConfig->setZoomLevel($parent->zoomLevel);

        if (substr_count($parent->resizeMode, '_') === 1)
        {
            $resizeConfig->setMode(ResizeConfiguration::MODE_CROP);
            $resizeConfig->setZoomLevel(0);
        }
        else
        {
            try
            {
                $resizeConfig->setMode($this->resizeMode);
            }
            catch (\Throwable $exception)
            {
                $resizeConfig->setMode(ResizeConfiguration::MODE_CROP);
            }
        }

        return $resizeConfig;
    }

    public function getImage(
        $originalPath,
        $width,
        $height,
        $mode = '',
        $target = null,
        $file = null,
		$targetPath = '',
		$image = null
    )
    {
        return null;
        $objImagine = \OMOSde\ContaoOmImagineBundle\OmImagineModel::findBy(['published=?', 'directory<>""'], [1]);

		if (!is_object($objImagine))
        {
            return null;
        }



        $strFile = TL_ROOT . '/' . $originalPath;

        // check if the file exists
        if (!file_exists($strFile))
        {
            return null;
        }
        if (!file_exists(TL_ROOT . '/' . $target))
        {
            return null;
        }

        $arrPathInfo = pathinfo(TL_ROOT . '/' . $target);
        $target2 = substr($target, 0, strlen($target) - strlen($arrPathInfo['extension'])) . 'watermark.' . $arrPathInfo['extension'];

        // get path info of file
        $arrPathInfo = pathinfo(TL_ROOT . '/' . $target2);

        // check file extension
        if (!in_array(strtolower($arrPathInfo['extension']), ['gif', 'jpg', 'png']))
        {
            return null;
        }

        copy(TL_ROOT . '/' . $target, TL_ROOT . '/' . $target2);


		// do for all active manipulations
        foreach ($objImagine as $objManipulation)
        {
            // get all active actions for this manipulation and check next if none exists
            $objActions = \OMOSde\ContaoOmImagineBundle\OmImagineActionModel::findBy(['pid=?', 'active=1'], [$objManipulation->id], ['order' => 'sorting ASC']);

			if (!$objActions)
            {
                continue;
            }

            // check for directories
            $objDirectories = \FilesModel::findMultipleByIds(deserialize($objManipulation->directory, true));
            if (!$objDirectories)
            {
                continue;
            }

            // create an array with the target directories
            foreach ($objDirectories as $directory)
            {
                $arrDirectories[] = $directory->path;
            }

            foreach ($arrDirectories as $strDirectory)
            {
                //
                if (!strpos($arrPathInfo['dirname'], $strDirectory) !== false)
                {
                    continue;
                }
                self::handleActions(TL_ROOT . '/' . $target2, $objManipulation, $objActions);
            }
        }

        return $target2;
    }
}