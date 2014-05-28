<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   Catalogs
 * @author    Benny Born <benny.born@numero2.de>
 * @license   Commercial
 * @copyright 2014 numero2 - Agentur für Internetdienstleistungen
 */


/**
 * BACK END MODULES
 */
$GLOBALS['BE_MOD']['content']['catalogs'] = array(
	'tables'	=> array('tl_catalogs', 'tl_catalogs_entries'),
	'icon'      => 'system/modules/catalogs/assets/icon.png'
);


/**
 * FRONT END MODULES
 */
$GLOBALS['FE_MOD']['catalogs'] = array(
	'catalogslist'   	=> 'Catalogs\ModuleList',
	'catalogsdetails'	=> 'Catalogs\ModuleDetails'
);