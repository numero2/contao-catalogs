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
 * Class ModuleList
 *
 * @copyright  2014 numero2 - Agentur für Internetdienstleistungen
 * @author     Benny Born <benny.born@numero2.de>
 * @package    Catalogs
 */
class ModuleList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_catalogs_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{

		if (TL_MODE == 'BE')
		{

			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### Catalogs - Listing ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// disable module if reader is active
		if( $this->catalogs_hide && \Input::get('auto_item') )
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		global $objPage;

		$this->loadDataContainer('tl_catalogs_entries');

		$objCatalogs = NULL;
		$objCatalogs = \CatalogsModel::findMultipleByIds( deserialize($this->catalogs_catalog) );

		$objEntries = NULL;
		$objEntries = \CatalogsEntriesModel::findPublishedByPids( deserialize($this->catalogs_catalog), 0, 0, array('order' => 'title ASC') );

		$entryCount = $objEntries->count();

		if( $entryCount )
		{

			$aEntries = array();

			foreach( $objEntries as $i => $entry )
			{
				$data = array(
					'fields' => $entry->row(),
					'class'	 => 'entry '
				);

				// set class
				$data['class'] .= (!$i) ? 'first ' : '';
				$data['class'] .= ($i%2) ? 'even ' : 'odd ';
				$data['class'] .= ($i==($entryCount-1)) ? 'last ' : '';

				// "decrypt" file fields
				foreach( $data['fields'] as $fName => $fVal )
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

						$data['fields'][$fName] = $fVal;
					}
				}

				// find jumpTo page
				foreach( $objCatalogs as $catalog )
				{
					if( $catalog->id != $entry->pid )
						continue;

					if( ($objTarget = $catalog->getRelated('jumpTo')) !== null && $entry->description )
					{
						$link = $this->generateFrontendUrl( $objTarget->row(), (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ?  '/%s' : '/entry/%s') );
						$data['link'] = sprintf($link,$entry->alias);
					}
				}

				$aEntries[] = $data;
			}

			$this->Template->entries = $aEntries;
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
		}
	}
}