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
use Imagine\Gd\Imagine as GdImagine;

class WatermarkService extends \OMOSde\ContaoOmImagineBundle\Imagine
{
    public function getImage(
        $originalPath,
        $width,
        $height,
        $mode = '',
        $target = null,
        $force = false
    )
    {
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
