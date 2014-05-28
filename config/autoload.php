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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Catalogs\Catalogs'      	=> 'system/modules/catalogs/classes/Catalogs.php',

	// Models
	'CatalogsModel'				=> 'system/modules/catalogs/models/CatalogsModel.php',
	'CatalogsEntriesModel'		=> 'system/modules/catalogs/models/CatalogsEntriesModel.php',

	// Modules
	'Catalogs\ModuleList'		=> 'system/modules/catalogs/modules/ModuleList.php',
	'Catalogs\ModuleDetails'  	=> 'system/modules/catalogs/modules/ModuleDetails.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_catalogs_list'			=> 'system/modules/catalogs/templates/catalogs',
	'mod_catalogs_details'  	=> 'system/modules/catalogs/templates/catalogs',
));