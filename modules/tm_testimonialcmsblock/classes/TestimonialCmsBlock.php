<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registred Trademark & Property of PrestaShop SA
*/

class TestimonialCmsBlock extends ObjectModel
{
    /** @var int $id_info - the ID of CustomText */
	public $id_tmtestimonialcmsblockinfo;

    /** @var String $text - HTML format of CustomText values */
	public $text;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'tmtestimonialcmsblockinfo',
		'primary' => 'id_tmtestimonialcmsblockinfo',
		'multilang' => true,
		'multilang_shop' => true,
		'fields' => array(
			'id_tmtestimonialcmsblockinfo' =>			array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
			// Lang fields
			'text' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
		)
	);

	/**
	 * Return the CustomText ID By shop ID
	 * 
	 * @param int $shopId
	 * @return bool|int
	 */
	public static function getTestimonialCmsBlockIdByShop($shopId)
	{
		$sql = 'SELECT i.`id_tmtestimonialcmsblockinfo` FROM `' . _DB_PREFIX_ . 'tmtestimonialcmsblockinfo` i
		LEFT JOIN `' . _DB_PREFIX_ . 'tmtestimonialcmsblockinfo_shop` ish ON ish.`id_tmtestimonialcmsblockinfo` = i.`id_tmtestimonialcmsblockinfo`
		WHERE ish.`id_shop` = ' . (int)$shopId;
		
		if ($result = Db::getInstance()->executeS($sql)) {
			return (int) reset($result)['id_tmtestimonialcmsblockinfo'];
		}

		return false;
	}
}
