<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerOrdersModuleFrontController extends AgileModuleFrontController
{
	public function setMedia()
	{
		parent::setMedia();
		
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerStylesheet('jquery.ui.theme', '/js/jquery/ui/themes/base/jquery.ui.theme.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.slider', '/js/jquery/ui/themes/base/jquery.ui.slider.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.datepicker', '/js/jquery/ui/themes/base/jquery.ui.datepicker.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.timepicker', '/js/jquery/ui/themes/base/jquery-ui-timepicker-addon.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.fancybox', '/js/jquery/plugins/fancybox/jquery.fancybox.css', ['media' => 'all', 'priority' => 100]);

		$this->registerJavascript('agile_autocomplete','/modules/agilemultipleseller/js/autocomplete/jquery.autocomplete.js',['position' => 'bottom', 'priority' => 100]);		
		$this->registerJavascript('agile_sellerproducts','/modules/agilemultipleseller/js/sellerorders.js',['position' => 'bottom', 'priority' => 100]);		

		$this->registerJavascript('js_uicore','/js/jquery/ui/jquery.ui.core.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uiwidget','/js/jquery/ui/jquery.ui.widget.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uimouse','/js/jquery/ui/jquery.ui.mouse.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uislider','/js/jquery/ui/jquery.ui.slider.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uidatepicker','/js/jquery/ui/jquery.ui.datepicker.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_fancybox','/js/jquery/plugins/fancybox/jquery.fancybox.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerJavascript('js_idtabs','/js/jquery/plugins/jquery.idTabs.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellerpage','/modules/agilemultipleseller/js/sellerpage.js',['position' => 'bottom', 'priority' => 100]);		
	
	}
	
	
	public function postProcess()
	{
		$export = Tools::getValue("export");
		if(!empty($export))
		{
			header('Content-type: text/csv');
			header('Content-Type: application/force-download; charset=UTF-8');
			header('Cache-Control: no-store, no-cache');
			header('Content-disposition: attachment; filename="products_'.date('Y-m-d_His').'.csv"');


			$orders = AgileSellerManager::getOrders($this->sellerinfo->id_seller, true, $this->context, false, 1, 999999, $this->orderBy, $this->orderWay, $this->getExtraConditions());
			$data = $this->processExport($orders);
			$csv = $this->module->hookGenerateCSV(array('data' => $data));

			die($csv);
		}
	}
	
	private function getExtraConditions()
	{
		$extraCondition = '';

		$extraCondition = '';
		$filters = array( 
			array('name' =>'filter_id_order', 'type'=> 'number')
			,array('name'=>'filter_reference','type'=>'text')
			,array('name'=>'filter_customer','type'=>'text')
			,array('name'=>'filter_payment','type'=>'text')
			,array('name'=>'filter_total','type'=>'float')
			,array('name'=>'filter_id_order_state','type'=>'number')
			,array('name'=>'filter_date_add','type'=>'date')
			);
		
		foreach($filters as $filter)
		{
			$val = Tools::getValue($filter['name']);
			$len = strlen($val);
			
			switch($filter['name'])
			{
				case 'filter_id_order':
					if((int)$val > 0)$extraCondition = $extraCondition . " AND o.id_order = " . (int)$val;
					break;
				case 'filter_reference':
					if($len>0)$extraCondition = $extraCondition . " AND o.reference LIKE '%" . $val . "%'";
					break;
				case 'filter_customer':					
					if($len>0)$extraCondition = $extraCondition . " AND (c.firstname LIKE '%" . $val . "%' OR c.lastname LIKE '%" . $val ."%')";
					break;
				case 'filter_id_order_state':					
					if($val>0)$extraCondition = $extraCondition . " AND o.current_state =" . (int)$val;
					break;
				case 'filter_total':					
					if($len>0)$extraCondition = $extraCondition . " AND o.total_paid =" . (float)$val;
					break;
				case 'filter_payment':					
					if($len>0)$extraCondition = $extraCondition . " AND o.payment like '%" . $val . "%'";
					break;
				case 'filter_date_add':
					$from = Tools::getValue($filter['name'] . "_from");
					$to = Tools::getValue($filter['name'] . "_to");
					if(!empty($from))$extraCondition = $extraCondition . " AND o.date_add >='" . $from . "'";
					if(!empty($to))$extraCondition = $extraCondition . " AND o.date_add <='" . $to . "'";
					break;
			}
		} 

		return $extraCondition;
	}
	
	public function initContent()
	{
		parent::initContent();

		$this->n = (int)Tools::getValue('n');		
		$this->p = (int)Tools::getValue('p');		
		if($this->p <=0) $this->p = 1;
		if($this->n <=0) $this->n =  max(1, (int)Configuration::get('PS_PRODUCTS_PER_PAGE'));

		$this->orderBy = Tools::getValue("orderBy");
		$this->orderWay = Tools::getValue("orderWay");
		
		$order_nb = AgileSellerManager::getOrders($this->sellerinfo->id_seller, true, $this->context, true, $this->p, $this->n, $this->orderBy, $this->orderWay, $this->getExtraConditions());
				$this->pagination($order_nb);
		$orders = AgileSellerManager::getOrders($this->sellerinfo->id_seller, true, $this->context, false, $this->p, $this->n, $this->orderBy, $this->orderWay, $this->getExtraConditions());

		$statuses = OrderState::getOrderStates((int)$this->context->language->id);


		self::$smarty->assign(array(
			'seller_tab_id' => 4
			,'n' => $this->n
			,'p' => $this->p
			,'orderBy' => $this->orderBy
			,'orderWay' => $this->orderWay
			,'orders' => $orders
			,'statuses' => $statuses
			,'invoiceAllowed' => (int)(Configuration::get('PS_INVOICE'))
			,'filter_date_add_from' => Tools::getValue('filter_date_add_from')
			,'filter_date_add_to' => Tools::getValue('filter_date_add_to')
			,'filter_id_order_state' => Tools::getValue('filter_id_order_state')
			,'filter_id_order' => Tools::getValue('filter_id_order')
			,'filter_reference' => Tools::getValue('filter_reference')
			,'filter_customer' => Tools::getValue('filter_customer')
			,'filter_total' => Tools::getValue('filter_total')
			,'filter_payment' => Tools::getValue('filter_payment')
			));
		
		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerorders.tpl');
	}
	
	public function processExport($list, $text_delimiter = '"')
	{
				if (ob_get_level() && ob_get_length() > 0) {
			ob_clean();
		}

		$fields = array(
			'id_order' => $this->l('ID')
			,'date_add' => $this->l('Date')
			,'reference' => $this->l('Order Refeerence')
			,'currency' => $this->l('Currency')
			,'total_paid' => $this->l('Paid')
			,'payment' => $this->l('Payment')
			,'osname' => $this->l('Status')
			,'invoice' => $this->l('Invoice')
			); 
		
		foreach($fields as $field => $header)
		{
			$headers[] = $header;
		}
		
		
		$content = array();
		foreach ($list as $i => $row) {
			$content[$i] = array();
			foreach ($fields as $field => $header) {
				switch($field)
				{
					case "invoice":
						$content[$i][] = $this->context->link->getModuleLink('agilemultipleseller', 'sellerpdfinvoice', array('id_order' => $row['id_order'], true));
						break;
					default:
						$content[$i][] = $row[$field];
						break;
				}
			}
		}

		return array(
			'export_precontent' => $this->l("Date Range:") . Tools::getValue('date_from') . " - " . Tools::getValue('date_to') ,
			'export_headers' => $headers,
			'export_content' => $content,
			'text_delimiter' => $text_delimiter
			);

	}
	
}
