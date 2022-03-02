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
				var_dump($objDirectories); die();
                continue;
            }

            // create an array with the target directories
            foreach ($objDirectories as $directory)
            {
                $arrDirectories[] = $directory->path;
            }

            $strFile = TL_ROOT . '/' . $originalPath;

            // check if the file exists
            if (!file_exists($strFile))
            {
				var_dump($strFile); die();
                continue;
            }
            if (!file_exists(TL_ROOT . '/' . $target))
            {
                var_dump(TL_ROOT . '/' . $target); die();
				if ($image->getImagine() instanceof GdImagine) {
					$dimensions = $image->getDimensions();

					/** @var Config $config */
					$config = $this->framework->getAdapter(Config::class);
					$gdMaxImgWidth = $config->get('gdMaxImgWidth');
					$gdMaxImgHeight = $config->get('gdMaxImgHeight');

					// Return the path to the original image if it cannot be handled
					if (
						$dimensions->getSize()->getWidth() > $gdMaxImgWidth
						|| $dimensions->getSize()->getHeight() > $gdMaxImgHeight
						|| $coordinates->getSize()->getWidth() > $gdMaxImgWidth
						|| $coordinates->getSize()->getHeight() > $gdMaxImgHeight
					) {
						$strFile =  $this->createImage($image, $image->getPath());
						var_dump(1); var_dump($strFile); die();
					}
				}

				$strFile = parent::executeResize($image, $coordinates, $path, $options);

                var_dump(2); var_dump($strFile); die();
				continue;
            }

            // get path info of file
            $arrPathInfo = pathinfo($strFile);

            // check file extension
            if (!in_array(strtolower($arrPathInfo['extension']), ['gif', 'jpg', 'png']))
            {
                continue;
            }

            //
            foreach ($arrDirectories as $strDirectory)
            {
                //
                if (!strpos($arrPathInfo['dirname'], $strDirectory) !== false)
                {
                    continue;
                }
                self::handleActions(TL_ROOT . '/' . $target, $objManipulation, $objActions);
            }
        }

        return null;
    }
}
