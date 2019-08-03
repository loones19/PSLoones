<?php
///-build_id: 2017010307.5027
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileRating extends ObjectModel
{
	public 		$id;
	const      FOR_ALL_UNVALIDATED_REVIEWS = -1;
	
		public 		$id_target;
	public 		$id_type;
	
		public 		$customer;
	public 		$id_customer;
	public 		$id_order;
	
		public 		$content;
	public		$response;
	
		public 		$grade;
	
	public 		$ip_address;
	public 		$id_lang;
	
		public 		$date_add;
	public		$date_upd;
	
 	protected 	$fieldsRequired = array('id_target', 'id_customer', 'content');
 	protected 	$fieldsSize = array('content' => 65535);
 	protected 	$fieldsValidate = array('id_target' => 'isUnsignedId', 'content' => 'isMessage', 'grade' => 'isFloat');

	public static $definition = array(
		'table' => 'agile_rating',
		'primary' => 'id_agile_rating',
		'multilang' => false,
		'multilang_shop' => false,
		'fields' => array(
			'id_target' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId','size' => 12,'required' => true),
			'id_type' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId','size' => 12,'required' => true),
			'customer' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'id_customer' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId','size' => 12,'required' => true),
			'id_order' =>	 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId','size' => 12,'required' => true),
			'content' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'response' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'grade' =>	 			array('type' => self::TYPE_FLOAT),
			'ip_address' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'id_lang' =>	 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId','size' => 12,'required' => true),
			'date_add' =>	 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' =>	 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			),
		);

	public	function getFields()   {     parent::validateFields(false);    $RB0D5D47F3D2E32A124C14253ABA3976A['id_target'] = intval($this->id_target);    $RB0D5D47F3D2E32A124C14253ABA3976A['id_type'] = intval($this->id_type);    $RB0D5D47F3D2E32A124C14253ABA3976A['id_customer'] = pSQL($this->id_customer);    $RB0D5D47F3D2E32A124C14253ABA3976A['customer'] = pSQL($this->customer);    $RB0D5D47F3D2E32A124C14253ABA3976A['id_order'] = $this->id_order;    $RB0D5D47F3D2E32A124C14253ABA3976A['content'] = pSQL($this->content);    $RB0D5D47F3D2E32A124C14253ABA3976A['grade'] = floatval($this->grade);    $RB0D5D47F3D2E32A124C14253ABA3976A['ip_address'] = $this->ip_address;    $RB0D5D47F3D2E32A124C14253ABA3976A['id_lang'] = $this->id_lang;    $RB0D5D47F3D2E32A124C14253ABA3976A['response'] = pSQL($this->response);    $RB0D5D47F3D2E32A124C14253ABA3976A['date_upd'] = pSQL($this->date_upd);    $RB0D5D47F3D2E32A124C14253ABA3976A['date_add'] = pSQL($this->date_add);      return ($RB0D5D47F3D2E32A124C14253ABA3976A);   }            static public function getList($RCBC984619B225938552157EAB97522FE,$R380E3037993CB742B735A382A9C483E7,$p = 1, $n = 10)   {    $p = intval($p);    $n = intval($n);    if ($p <= 1)     $p = 1;    if ($n <= 0)     $n = 10;      $R130D64A4AD653C91E0FD80DE8FEADC3A = '    SELECT r.`id_agile_rating`, r.`customer`,r.response,r.`content`, r.`grade`, r.`date_add`, r.`date_upd`      FROM `'._DB_PREFIX_.'agile_rating` r    WHERE 1         AND r.`id_target` = '.intval($RCBC984619B225938552157EAB97522FE).'        AND r.`id_type` = '.intval($R380E3037993CB742B735A382A9C483E7).'    ORDER BY r.`date_add` DESC ';        if($n>0)$R130D64A4AD653C91E0FD80DE8FEADC3A = $R130D64A4AD653C91E0FD80DE8FEADC3A . 'LIMIT '.intval(($p - 1) * $n).', '.$p * intval($n);    return Db::getInstance()->ExecuteS($R130D64A4AD653C91E0FD80DE8FEADC3A);     }               static public function getAverage($RCBC984619B225938552157EAB97522FE,$R380E3037993CB742B735A382A9C483E7)   {    if (!Validate::isUnsignedId($RCBC984619B225938552157EAB97522FE) ||     !Validate::isUnsignedId($R380E3037993CB742B735A382A9C483E7))     die(Tools::displayError());      $R130D64A4AD653C91E0FD80DE8FEADC3A = 'SELECT avg(r.`grade`) AS rating    FROM `'._DB_PREFIX_.'agile_rating` r    WHERE r.`id_target` = '.intval($RCBC984619B225938552157EAB97522FE).'        AND r.`id_type` = ' . intval($R380E3037993CB742B735A382A9C483E7). '        GROUP BY id_target, id_type    ';      $R4002603E450F0DB8D5A7FF540344175C = Db::getInstance()->getRow($R130D64A4AD653C91E0FD80DE8FEADC3A);    return  isset($R4002603E450F0DB8D5A7FF540344175C['rating'])?floatval($R4002603E450F0DB8D5A7FF540344175C['rating']):0;   }       static public function getCount($RCBC984619B225938552157EAB97522FE,$R380E3037993CB742B735A382A9C483E7)   {    $R130D64A4AD653C91E0FD80DE8FEADC3A = 'SELECT count(r.`grade`) AS num    FROM `'._DB_PREFIX_.'agile_rating` r    WHERE r.`id_target` = '.intval($RCBC984619B225938552157EAB97522FE).'        AND r.`id_type` = ' . intval($R380E3037993CB742B735A382A9C483E7). '    ';    $R4002603E450F0DB8D5A7FF540344175C = Db::getInstance()->getRow($R130D64A4AD653C91E0FD80DE8FEADC3A);    return  isset($R4002603E450F0DB8D5A7FF540344175C['num'])?intval($R4002603E450F0DB8D5A7FF540344175C['num']):0;   }      public static function getFeedbackWaitingList($R91CACE256C6C839A6B447F6BAE86D72A)   {    include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");    $R3F76C20596A009928F756B651F405812 = new SellerInfo(SellerInfo::getIdByCustomerId($R91CACE256C6C839A6B447F6BAE86D72A));           $R130D64A4AD653C91E0FD80DE8FEADC3A = 'SELECT o.id_order, sl.company, IFNULL(oo.id_owner,0) AS id_owner,o.date_add               FROM `' . _DB_PREFIX_. 'orders` o                  LEFT JOIN `' . _DB_PREFIX_. 'order_owner` oo ON o.id_order=oo.id_order                  LEFT JOIN `' . _DB_PREFIX_. 'sellerinfo` s ON oo.id_owner=s.id_seller                  LEFT JOIN `' . _DB_PREFIX_. 'sellerinfo_lang` sl ON sl.id_sellerinfo=s.id_sellerinfo AND sl.id_lang=' . Context::getContext()->cookie->id_lang. '                  LEFT JOIN `' . _DB_PREFIX_. 'agile_rating` r ON (o.id_order=r.id_order AND r.id_target = oo.id_owner)              WHERE o.id_customer = ' . $R91CACE256C6C839A6B447F6BAE86D72A . '                  AND o.date_add >= DATE_ADD(CURDATE( ) , INTERVAL -120 DAY )                  AND r.id_order IS NULL      AND s.id_seller != ' . intval($R3F76C20596A009928F756B651F405812->id_seller) . '                  AND IFNULL(oo.id_owner,0)>0          ';            return Db::getInstance()->ExecuteS($R130D64A4AD653C91E0FD80DE8FEADC3A);   }     public static function getFeedbackWaitingCount($R91CACE256C6C839A6B447F6BAE86D72A)   {    if(intval($R91CACE256C6C839A6B447F6BAE86D72A)==0)return 0;    include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");    $R3F76C20596A009928F756B651F405812 = new SellerInfo(SellerInfo::getIdByCustomerId($R91CACE256C6C839A6B447F6BAE86D72A));           $R130D64A4AD653C91E0FD80DE8FEADC3A = 'SELECT COUNT(*) AS num              FROM `' . _DB_PREFIX_. 'orders` o                  LEFT JOIN `' . _DB_PREFIX_. 'order_owner` oo ON o.id_order=oo.id_order                  LEFT JOIN `' . _DB_PREFIX_. 'sellerinfo` s ON oo.id_owner=s.id_seller                  LEFT JOIN `' . _DB_PREFIX_. 'agile_rating` r ON o.id_order=r.id_order              WHERE o.id_customer = ' . intval($R91CACE256C6C839A6B447F6BAE86D72A) . '                  AND o.date_add >= DATE_ADD(CURDATE( ) , INTERVAL -120 DAY )      AND s.id_seller != ' . intval($R3F76C20596A009928F756B651F405812->id_seller) . '                  AND r.id_order IS NULL                  AND IFNULL(oo.id_owner,0)>0          ';             $R4002603E450F0DB8D5A7FF540344175C = Db::getInstance()->getRow($R130D64A4AD653C91E0FD80DE8FEADC3A);          return (isset($R4002603E450F0DB8D5A7FF540344175C['num']) AND intval($R4002603E450F0DB8D5A7FF540344175C['num'])>0) ? intval($R4002603E450F0DB8D5A7FF540344175C['num']):0;   }     static public function addCriterionRatingGrade($R22B5E40887FFDD144E2FAFF4FA4DDD75,$R76F813E5E2ECD893182730D21F1A8608,$R0AF750EE2CEFDB488675D9F23229CD30)   {    if (!Validate::isUnsignedId($R76F813E5E2ECD893182730D21F1A8608) ||     !Validate::isUnsignedId($R22B5E40887FFDD144E2FAFF4FA4DDD75))     die(Tools::displayError());      $R130D64A4AD653C91E0FD80DE8FEADC3A = 'INSERT INTO `'._DB_PREFIX_.'agile_rating_grade` (`id_agile_rating`,`id_agile_rating_criterion`,`grade`)        VALUES (' . $R22B5E40887FFDD144E2FAFF4FA4DDD75 . ',' . $R76F813E5E2ECD893182730D21F1A8608 . ','. $R0AF750EE2CEFDB488675D9F23229CD30 . ')';            $R4002603E450F0DB8D5A7FF540344175C = Db::getInstance()->Execute($R130D64A4AD653C91E0FD80DE8FEADC3A);   }      static public function getAverages($RCBC984619B225938552157EAB97522FE,$R380E3037993CB742B735A382A9C483E7)   {       $R130D64A4AD653C91E0FD80DE8FEADC3A = 'SELECT avg(g.grade) as grade,id_agile_rating_criterion FROM `'._DB_PREFIX_.'agile_rating`  a                      LEFT JOIN '._DB_PREFIX_.'agile_rating_grade g on a.id_agile_rating=g.id_agile_rating                  WHERE id_type=' . $R380E3037993CB742B735A382A9C483E7  .' AND id_target=' . $RCBC984619B225938552157EAB97522FE . '                  Group BY id_agile_rating_criterion                  ';          $RA741A20D9955FD3598D648988CE7EF9A = Db::getInstance()->ExecuteS($R130D64A4AD653C91E0FD80DE8FEADC3A);          $RB8BAF21E196B0AC2674B94DC3AABDE58 = array();          if(!isset($RA741A20D9955FD3598D648988CE7EF9A) OR empty($RA741A20D9955FD3598D648988CE7EF9A))return $RB8BAF21E196B0AC2674B94DC3AABDE58;          foreach($RA741A20D9955FD3598D648988CE7EF9A AS $R30218FE02C3F61C97CB460A604482247)          {              $RB8BAF21E196B0AC2674B94DC3AABDE58[intval($R30218FE02C3F61C97CB460A604482247['id_agile_rating_criterion'])] = (float)$R30218FE02C3F61C97CB460A604482247['grade'];          }             return $RB8BAF21E196B0AC2674B94DC3AABDE58;   }      public function delete()   {    $R130D64A4AD653C91E0FD80DE8FEADC3A = 'DELETE FROM ' . _DB_PREFIX_  . 'agile_rating_grade WHERE id_agile_rating = ' . (int)$this->id;    Db::getInstance()->Execute($R130D64A4AD653C91E0FD80DE8FEADC3A);    return parent::delete();   }    };  