<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ADMIN_DIR_.'/../modules/agilemultipleseller/agilemultipleseller.php');

class AdminOrderProductsController extends ModuleAdminController
{    
	private $_isSeller;

	public function __construct()
	{
		$this->bootstrap = true;		
		$this->table = 'order_detail';
		$this->identifier = 'id_order_detail';
		$this->className = 'OrderDetail';
				$this->addRowAction(''); 
		parent::__construct();
		
		$statuses_array = array();
		$statuses = OrderState::getOrderStates((int)$this->context->language->id);

		foreach ($statuses as $status)
			$statuses_array[$status['id_order_state']] = $status['name'];
		
		$this->fields_list = array(
			'id_order_detail' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 50
			),
			'id_order' => array(
				'title' => $this->l('Order ID'),
				'align' => 'center',
				'width' => 50,
				'filter_key' => 'a!id_order'
				),
			'id_owner' => array(
				'title' => $this->l('Seller ID'),
				'align' => 'center',
				'width' => 50
			),
			'seller' => array(
				'title' => $this->l('Seller'),
				'havingFilter' => true,
			),

			'product_name' => array(
					'title' => $this->l('Product Name'),
				'width' => 180,
				'align' => 'left',
			),
				
			'product_price' => array(
				'title' => $this->l('Price'),
				'width' => 70,
				'align' => 'right',
				'prefix' => '<b>',
				'suffix' => '</b>',
				'type' => 'price',
				'currency' => true
			),
		
			'product_quantity' => array(
				'title' => $this->l('Quantity'),
				'align' => 'center',
				'havingFilter' => true,
			),
		
			'osname' => array(
				'title' => $this->l('Status'),
				'color' => 'color',
				'width' => 280,
				'type' => 'select',
				'list' => $statuses_array,
				'filter_key' => 'os!id_order_state',
				'filter_type' => 'int'
			),
		
			'date_add' => array(
				'title' => $this->l('Date'),
				'width' => 130,
				'align' => 'right',
				'type' => 'datetime',
				'filter_key' => 'o!date_add'
			),
		);

		$this->_isSeller = (intval($this->context->cookie->profile) == Configuration::get('AGILE_MS_PROFILE_ID'));

	    $this->_join = $this->_join . '
            LEFT JOIN `'._DB_PREFIX_.'orders` o ON (a.`id_order`=o.id_order)
            LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON (a.`id_order`=oo.`id_order`)
            LEFT JOIN `'._DB_PREFIX_.'sellerinfo` s ON (oo.`id_owner`=s.`id_seller`)
            LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (sl.`id_sellerinfo`=s.`id_sellerinfo` AND sl.id_lang=' . $this->context->language->id . ')
	 	    LEFT JOIN `'._DB_PREFIX_.'order_history` oh ON (oh.`id_order` = a.`id_order`)
		    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = oh.`id_order_state`)
		    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)($this->context->language->id).')
		    ';
        $this->_select = $this->_select . '
                o.date_add, oo.id_owner, o.id_currency as id_currency,a.product_name, sl.company as seller
			    ,osl.`name` AS `osname`, os.`color`
                ';

		$this->_where = ' AND oh.`id_order_history` = (SELECT MAX(`id_order_history`) FROM `'._DB_PREFIX_.'order_history` moh WHERE moh.`id_order` = a.`id_order` GROUP BY moh.`id_order`)';

		if($this->_isSeller)
		{
			$this->_where = $this->_where . ' AND oo.id_owner=' . intval($this->context->cookie->id_employee);
        }
        else
        {
			$this->fieldsDisplay['seller'] = array('title' => $this->l('Seller'),'filter_key' => 'sl!company');
        }
		
		if(isset($_GET['updateorder_detail']) AND isset($_GET['id_order_detail']) AND (int)$_GET['id_order_detail']>0)
		{	
			$id_order_detail = Tools::getValue("id_order_detail");
			$orderdetail = new OrderDetail($id_order_detail);
			$tabid = Tab::getIdFromClassName('AdminOrders');
			$newtoekn = Tools::getAdminToken('AdminOrders' .intval($tabid).intval($this->context->cookie->id_employee));
			$url = "./index.php?controller=adminorders&id_order=" . $orderdetail->id_order . "&vieworder&token=" . $newtoekn;
			Tools::redirectAdmin($url);
		}
		
	}

	public function initToolbar()
	{
		parent::initToolbar();
		unset($this->toolbar_btn['new']);
	}

	public function init()
	{
		if(isset($_GET['updateorder_detail']) AND isset($_GET['id_order_detail']) AND (int)$_GET['id_order_detail']>0)
		{	
			$id_order_detail = Tools::getValue("id_order_detail");
			$orderdetail = new OrderDetail($id_order_detail);
			$tabid = Tab::getIdFromClassName('AdminOrders');
			$newtoekn = Tools::getAdminToken('AdminOrders' .intval($tabid).intval($$this->context->cookie->id_employee));
			$url = "./index.php?controller=adminorders&id_order=" . $orderdetail->id_order . "&vieworder&token=" . $newtoekn;
			Tools::redirectAdmin($url);
		}
		
		parent::init();
	}
	
}


