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
 * Class CatalogsEntriesModel
 *
 * @copyright  2014 numero2 - Agentur für Internetdienstleistungen
 * @author     Benny Born <benny.born@numero2.de>
 * @package    Catalogs
 */
class CatalogsEntriesModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_catalogs_entries';


	/**
	 * Find published catalog items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no entries
	 */
	public static function findPublishedByPids( $arrPids, $intLimit=0, $intOffset=0, array $arrOptions=array() )
	{
		if( !is_array($arrPids) || empty($arrPids) )
		{
			return null;
		}

		$t = static::$strTable;

		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");
		$arrColumns[] = "$t.published=1";

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.sorting DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, null, $arrOptions);
	}
}