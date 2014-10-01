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
 * Table tl_catalogs
 */
$GLOBALS['TL_DCA']['tl_catalogs'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'   	=> 'Table',
		'enableVersioning'	=> true,
		'ctable'			=> array('tl_catalogs_entries'),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'          => 1,
			'fields'        => array('title'),
			'flag'          => 1
		),
		'label' => array
		(
			'fields'        => array('title'),
			'format'        => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'     => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'      => 'act=select',
				'class'     => 'header_edit_all',
				'attributes'=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_catalogs']['edit'],
				'href'		=> 'table=tl_catalogs_entries',
				'icon'		=> 'edit.gif'
			),
			'delete' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_catalogs']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_catalogs']['show'],
				'href'		=> 'act=show',
				'icon'		=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'		=> array(''),
		'default'			=> '{title_legend},title,alias,jumpTo;'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'			=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'			=> "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_catalogs']['title'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'			=> "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_catalogs']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array( array('tl_catalogs', 'generateAlias') ),
			'sql'           => "varchar(128) NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'     	=> &$GLOBALS['TL_LANG']['tl_catalogs']['jumpTo'],
			'exclude'   	=> true,
			'inputType' 	=> 'pageTree',
			'foreignKey'	=> 'tl_page.title',
			'eval'      	=> array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'       	=> "int(10) unsigned NOT NULL default '0'",
			'relation'  	=> array('type'=>'hasOne', 'load'=>'eager')
		),
	)
);

class tl_catalogs extends Backend
{

	/**
	 * Auto-generate the catalog alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_catalogs WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}