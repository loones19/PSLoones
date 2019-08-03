<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_.'/modules/agilemultipleseller/agilemultipleseller.php');
include_once(_PS_ROOT_DIR_.'/modules/agilemultipleseller/SellerType.php');
class AdminSellerTypesController extends ModuleAdminController
{
	public function __construct()
	{
	 	$this->table = 'sellertype';
		$this->bootstrap = true;
		$this->className = 'Sellertype';

		parent::__construct();

		$this->addRowAction('delete');
		$this->addRowAction('edit');
		
		$this->fields_list = array(
			'id_sellertype' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'name' => array('title' => $this->l('Name'), 'width' => 80, 'filter_key' => 'sl!name'),
			'date_add' => array('title' => $this->l('Creation date'), 'width' => 60, 'type' => 'date', 'align' => 'right', 'filter_key' => 'a!date_add'));

		$this->_select = 'sl.name';


		$this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'sellertype_lang` sl ON (a.`id_sellertype` = sl.`id_sellertype` AND sl.id_lang=' . intval($this->context->cookie->id_lang). ')
            ';
	}
	
	
	public function renderForm()
	{
		if (!($obj = $this->loadObject(true)))
			return;

		$this->fields_form = array(
			'legend' => array(
					'title' => $this->l('Seller Type')
					),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Name'),
						'name' => 'name',
						'size' => 33,
						'lang' => true,
						'required' => true
						),
					)
				);

		$this->fields_form['submit'] = array(
			'title' => $this->l('Save'),
			'class' => 'btn btn-default pull-right'
			);
		
		return parent::renderForm();
	}    
	
}
