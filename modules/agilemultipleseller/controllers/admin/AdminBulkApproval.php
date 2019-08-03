<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ADMIN_DIR_.'/../modules/agilemultipleseller/agilemultipleseller.php');

class AdminBulkApprovalController extends ModuleAdminController
{    
	private $_isSeller;
	private $_approved_statuses = array('1' => 'Yes', '0' =>'No');
	public $controller_type='admin';

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'product';
		$this->identifier = 'id_product';
	 	$this->className = 'Product';
		$this->list_no_link = false;

		parent::__construct();
		
		$currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
		
		$this->addRowAction(''); 		
		$this->confirmations[] = $this->l('Only products waiting approval will be displayed here, no matter what category the product is in. Products have been approved will not appear here. Also please note, it will may take some time when you approve a lot of producs at a time - expecially when you enabled product approval notification email.');

				$this->bulk_actions['bulkaproval'] = array('text' => $this->l('Approve'), 'confirm' => $this->l('Approve all selected items?'));

		$this->_isSeller = (intval($this->context->cookie->profile) == Configuration::get('AGILE_MS_PROFILE_ID'));
		$this->_join = $this->_join 
			. ' LEFT JOIN `'._DB_PREFIX_.'product_lang` b ON (a.`id_product` = b.`id_product` AND b.id_lang=' . $this->context->language->id . ')'
			. '	LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON (a.`id_product`=po.`id_product`)'
			. ' LEFT JOIN `'._DB_PREFIX_.'sellerinfo` s ON (po.`id_owner` = s.`id_seller`)'
			. ' LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (sl.`id_sellerinfo` = s.`id_sellerinfo`AND sl.id_lang=' . $this->context->language->id. ')'
			. ' LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = a.`id_product` AND i.`cover` = 1) '
			. ' LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = a.`id_product` AND cp.id_category=a.id_category_default) '
			. ' LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (a.`id_tax_rules_group` = tr.`id_tax_rules_group` AND tr.`id_country` = '.(_PS_VERSION_>='1.5'? Context::getContext()->country->id:  (int)Country::getDefaultCountryId()).' AND tr.`id_state` = 0)'
				. ' LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)'
			;

		$this->_select = $this->_select 
			. 'b.name, cp.`position`,sl.company AS owner, IFNULL(po.approved,0) AS approved, i.`id_image`, (a.`price` * ((100 + (IFNULL(t.rate,0)))/100)) AS price_final
			,' . $currency . ' as id_currency
			,CASE WHEN IFNULL(po.approved,0)=1 THEN \'' . $this->_approved_statuses[1] . '\' ELSE \'' .$this->_approved_statuses[0] . '\' END AS approved_text
            ';

		$this->_where = $this->_where . ' AND IFNULL(po.approved,0)=0';
		
		$this->fields_list = array(
			'id_product' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 20),
			'image' => array('title' => $this->l('Photo'), 'align' => 'center', 'image' => 'p', 'width' => 45, 'orderby' => false, 'filter' => false, 'search' => false),
			'name' => array('title' => $this->l('Name'), 'width' => 220, 'filter_key' => 'b!name'),
			'price' => array('title' => $this->l('Base price'), 'width' => 70, 'type' => 'price','currency' => true, 'align' => 'right', 'filter_key' => 'a!price'),
			'price_final' => array('title' => $this->l('Final price'), 'width' => 70, 'type' => 'price', 'currency' => true, 'align' => 'right', 'havingFilter' => true, 'orderby' => false),
			'approved_text' =>array('title' => $this->l('Approved'), 'width' => 60,'type' => 'select','list' => $this->_approved_statuses, 'filter_type' => 'int','filter_key' => 'po!approved'),
			'owner'=>array('title' => $this->l('Owner'), 'width' => 90, 'filter_key' => 'sl!company')
		);
	}
	
	public function initToolbar()
	{
		parent::initToolbar();
		unset($this->toolbar_btn['new']);
	}
	
	public function init()
	{
		if(isset($_GET['update'. $this->table]) AND isset($_GET[$this->identifier]) AND (int)$_GET[$this->identifier]>0)
		{	
			$id = Tools::getValue($this->identifier);
			$newtoekn = Tools::getAdminTokenLite('AdminProducts');
			$url = "./index.php?controller=adminproducts&" . $this->identifier ."=" . $id . "&update" . $this->table . "&token=" . $newtoekn;
			Tools::redirectAdmin($url);
		}		
		parent::init();
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitBulkbulkaprovalproduct'))
		{
			if(isset($_POST[$this->table.'Box']))
			{
				$productids =  $_POST[$this->table.'Box'];
				foreach($productids AS $id)
				{
					if(intval($id)<=0)continue;
					$sql = 'SELECT * FROM '. _DB_PREFIX_ . 'product_owner WHERE id_product =' . $id;
					$rec = Db::getInstance()->getRow($sql);
					if(!isset($rec['id_product']))
						$sql = 'INSERT INTO '. _DB_PREFIX_ . 'product_owner (id_product,id_owner,approved,date_add) VALUES (' . $id . ',0,1,\'' . date('Y-m-d H:i:s') . '\')';
					else
						$sql = 'UPDATE '. _DB_PREFIX_ . 'product_owner SET approved=1 WHERE id_product=' . (int)$id;
					
					Db::getInstance()->Execute($sql);
					$prod = new Product($id, false, $this->context->language->id);
					AgileMultipleSeller::sendProductApproveNotification($rec['id_owner'], $prod, 1);
				}
			}
			else
			{
				$this->_errors[] = "No product was selected to approve.";
			}
			return;			
		}

		parent::postProcess();
	}
	
}


