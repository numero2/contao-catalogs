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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['catalogslist']		= '{title_legend},name,headline,type;{config_legend},catalogs_catalog,catalogs_hide,catalog_order_sql;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['catalogsdetails']	= '{title_legend},name,headline,type;{config_legend},{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['catalogs_catalog'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_module']['catalogs_catalog'],
	'default'               => '',
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'options_callback'      => array('tl_module_catalogs', 'getCatalogs'),
	'eval'                  => array('multiple'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
	'sql'                  	=> "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['catalogs_hide'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_module']['catalogs_hide'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'w50'),
	'sql'                  	=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_order_sql'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_module']['catalog_order_sql'],
	'exclude'               => true,
	'inputType'             => 'text',
	'eval'                  => array('tl_class'=>'w50'),
	'sql'                  	=> "varchar(255) NOT NULL default ''"
);


/**
 * Class tl_module_catalogs
 *
 * @package   SocialMediaCalculator
 * @author    Benny Born <benny.born@numero2.de>
 * @license   Commercial
 * @copyright 2013 numero2 - Agentur für Internetdienstleistungen
 */
class tl_module_catalogs extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Get all catalogs and return them as array
	 * @return array
	 */
	public function getCatalogs()
	{
		if (!$this->User->isAdmin)
		{
			return array();
		}

		$objCatalogs = NULL;
		$objCatalogs = $this->Database->prepare("SELECT id, title FROM tl_catalogs")->execute();

		$arrCatalogs= array();

		while( $objCatalogs->next() ) {
			$arrCatalogs[ $objCatalogs->id ] = $objCatalogs->title;
		}

		return $arrCatalogs;
	}
}