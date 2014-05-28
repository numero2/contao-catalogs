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
 * Namespace
 */
namespace Catalogs;


/**
 * Class ModuleDetails
 *
 * @copyright  2014 numero2 - Agentur für Internetdienstleistungen
 * @author     Benny Born <benny.born@numero2.de>
 * @package    Catalogs
 */
class ModuleDetails extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_catalogs_details';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{

		if (TL_MODE == 'BE')
		{

			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### Catalogs - Details ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		
		$this->loadDataContainer('tl_catalogs_entries');
		$alias = \Input::get('auto_item');

		$objEntry = NULL;
		$objEntry = \CatalogsEntriesModel::findByIdOrAlias( $alias );

		$fields = $objEntry->row();

		// "decrypt" file fields
		foreach( $fields as $fName => $fVal )
		{
			$fieldDef = &$GLOBALS['TL_DCA']['tl_catalogs_entries']['fields'][ $fName ];

			if( !empty($fieldDef['inputType']) && $fieldDef['inputType'] == 'fileTree' )
			{
				// multiple images
				if( !empty($fieldDef['eval']['multiple']) && $fieldDef['eval']['multiple'] )
				{
					$arrEnclosure = deserialize( $fVal );
					$fVal = \FilesModel::findMultipleByUuids($arrEnclosure);

				// single images
				} else {
					$fVal = \FilesModel::findByUuid($fVal);
				}

				$fields[$fName] = $fVal;
			}
		}

		$this->Template->entry = $fields;

		global $objPage;

		// overwrite page title
		if( !empty($fields['title']) )
			$objPage->title = $fields['title'];

		#echo '<pre>'.print_r($this->Template->entry,1).'</pre>'; die();
	}
}