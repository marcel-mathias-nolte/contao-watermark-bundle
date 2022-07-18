<?php

/**
 * Contao bundle contao-om-imagine
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

\System::loadLanguageFile('tl_om_imagine');

/**
 * Table tl_om_imagine
 */
$GLOBALS['TL_DCA']['tl_om_imagine']['fields']['directory_resize'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['directory_resize'],
    'inputType' => 'fileTree',
    'eval'      => ['multiple' => true, 'fieldType' => 'checkbox', 'files' => false, 'mandatory' => false],
    'sql'       => "blob NULL"
];
$GLOBALS['TL_DCA']['tl_om_imagine']['palettes']['default'] = str_replace(',directory', ',directory,directory_resize', $GLOBALS['TL_DCA']['tl_om_imagine']['palettes']['default']);
