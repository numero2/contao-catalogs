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
 * Table tl_catalogs_entries
 */
$GLOBALS['TL_DCA']['tl_catalogs_entries'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'   	=> 'Table',
		'enableVersioning'	=> true,
		'ptable'			=> 'tl_catalogs',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid'=> 'index'
			)
		),
		'onsubmit_callback' => array
		(
			array('tl_catalogs_entries', 'fillCoordinates')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'          => 4,
			'fields'        => array('sorting'),
			'panelLayout'	=> '',
			'headerFields'  => array('title','type'),
			'child_record_callback' => array('tl_catalogs_entries', 'listEntryCallback')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'     => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'      => 'act=select',
				'class'   	=> 'header_edit_all',
				'attributes'=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['edit'],
				'href'		=> 'act=edit',
				'icon'		=> 'edit.gif'
			),
			'delete' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['delete'],
				'href'		=> 'act=delete',
				'icon'		=> 'delete.gif',
				'attributes'=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['toggle'],
				'icon'      => 'visible.gif',
				'attributes'=> 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'=> array('tl_catalogs_entries', 'toggleIcon')
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'			=> '{title_legend},title,alias,teaser,description,singleSRC;{adress_legend},street,postal,city,country;{geo_legend},geo_explain,longitude,map,latitude;{publish_legend:hide},published',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'			=> "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'    => 'tl_catalogs.id',
			'sql'           => "int(10) unsigned NOT NULL default '0'",
			'relation'      => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
			'sql'           => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'			=> "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['title'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'			=> "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array( array('tl_catalogs_entries', 'generateAlias') ),
			'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'teaser' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['teaser'],
			'exclude'		=> true,
			'inputType'		=> 'textarea',
			'eval'			=> array('mandatory'=>true, 'style'=>'height: 50px;', 'tl_class'=>'clr'),
			'sql'			=> "text NOT NULL"
		),
		'description' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['description'],
			'exclude'		=> true,
			'inputType'		=> 'textarea',
			'eval'			=> array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'			=> "text NOT NULL"
		),
		'singleSRC' => array
		(
			'label'          => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['singleSRC'],
			'exclude'        => true,
			'inputType'      => 'fileTree',
			'eval'           => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio', 'mandatory'=>false),
			'sql'            => "binary(16) NULL"
		),
		'street' => array
		(
            'label'       	=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['street']
		,	'inputType'   	=> 'text'
		,	'search'      	=> true
		,	'eval'        	=> array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50')
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'postal' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['postal']
		,	'inputType'     => 'text'
		,	'search'        => true
		,	'eval'          => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50')
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'city' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['city']
		,	'inputType'     => 'text'
		,	'search'        => true
		,	'eval'          => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50')
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'country' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['country']
		,	'inputType'     => 'select'
		,	'options_callback'	=> array('tl_catalogs_entries', 'getCountries')
		,	'default'		=> 'de'
		,	'search'        => true
		,	'eval'          => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'chosen'=>true)
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'geo_explain' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['geo_explain']
		,	'input_field_callback'    => array('tl_catalogs_entries', 'showGeoExplain'),
        ),
		'longitude' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['longitude']
		,	'inputType'     => 'text'
		,	'search'        => true
		,	'eval'          => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50')
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'latitude' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['latitude']
		,	'inputType'     => 'text'
		,	'search'        => true
		,	'eval'          => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50')
		,	'sql'			=> "varchar(64) NOT NULL default ''"
        ),
		'map' => array
		(
            'label'         => &$GLOBALS['TL_LANG']['tl_catalogs_entries']['map']
		,	'input_field_callback'    => array('tl_catalogs_entries', 'showMap')
        ),
		'published' => array
		(
			'label'        	=> &$GLOBALS['TL_LANG']['tl_catalogs_entries']['published'],
			'exclude'       => true,
			'filter'        => true,
			'inputType'     => 'checkbox',
			'sql'           => "char(1) NOT NULL default ''"
		),
	)
);

class tl_catalogs_entries extends Backend
{

	/**
	 * Shows a little info text what coordinates are
	 * @return string
	 */	
	public function showGeoExplain() {
	
		return '<div class="tl_help">'.$GLOBALS['TL_LANG']['tl_catalogs_entries']['geo_explain'].'</div>';
	}


	/**
	 * Displays a little static Google Map with position of the address
	 * @param DataContainer
	 * @return string
	 */
	public function showMap( DataContainer $dc ) {
	
		$sCoords = sprintf(
			"%s,%s"
		,	$dc->activeRecord->latitude
		,	$dc->activeRecord->longitude
		);
	
		return '<div style="float: right; height: 139px; margin-right: 23px; overflow: hidden; width: 320px;">'
		.'<h3><label>'.$GLOBALS['TL_LANG']['tl_catalogs_entries']['map'].'</label></h3> '
		.'<img style="margin-top: 1px;" src="http://maps.google.com/maps/api/staticmap?center='.$sCoords.'&zoom=8&size=320x139&maptype=roadmap&markers=color:red|label:|'.$sCoords.'&sensor=false" />'
		.'</div>';
	}


	/**
	 * Returns list of countries
	 * @return array
	 */
	public static function getCountries() {
	
		return parent::getCountries();
	}


	/**
	 * Fills coordinates if not already set and saving
	 * @param DataContainer
	 * @return bool
	 */
	public function fillCoordinates( DataContainer $dc ) {
	
		if( !$dc->activeRecord || !$dc->activeRecord->city ) {
			return;
		}

		$objCatalogs = new \Catalogs\Catalogs();
		
		// find coordinates using google maps api
		$coords = $objCatalogs->getCoordinates(
			$dc->activeRecord->street
		,	$dc->activeRecord->postal
		,	$dc->activeRecord->city
		,	$dc->activeRecord->country
		);

		if( !empty($coords) ) {
			$this->Database->prepare("UPDATE tl_catalogs_entries %s WHERE id=?")->set($coords)->execute($dc->id);
			return true;
		}
		
		return false;
	}


	/**
	 * Callback for generating list entry
	 * @return string
	 */
	public function listEntryCallback($arrRow)
	{
		return sprintf(
			'<div>%s</div>',
			$arrRow['title']
		);
	}


	/**
	 * Generates the correct "toggle visibility" icon
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;id='.Input::get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['published'];

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Toogle visibility of catalog entry
	 * @return none
	 */
	public function toggleVisibility($intId, $blnVisible)
	{

		$objVersions = new Versions('tl_catalogs_entries', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_catalogs_entries']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_catalogs_entries']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_catalogs_entries SET tstamp=". time() .", published='" . $blnVisible . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_catalogs_entries.id='.$intId.'" has been created'.$this->getParentEntries('tl_catalogs_entries', $intId), 'tl_catalogs_entries toggleVisibility()', TL_GENERAL);
	}


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