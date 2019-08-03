<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminCarrierWizardController extends AdminCarrierWizardControllerCore
{
	public function renderGenericForm($fields_form, $fields_value, $tpl_vars = array())
	{
		if($fields_form['form']['form']['id_form'] == 'step_carrier_summary')
		{			
			$fields_value['id_seller'] = AgileSellerManager::getObjectOwnerID('carrier', Tools::getValue('id_carrier'));

			if($this->is_seller)
			{
				array_unshift($fields_form['form']['form']['input'],
					array(
							'type' => 'hidden',
							'label' => $this->l('Seller:'),
							'name' => 'id_seller',
							'required' => false,
							)
						);
			}
			else
			{
				array_unshift($fields_form['form']['form']['input'],
					array(
							'type' => 'select',
							'label' => $this->l('Seller:'),
							'name' => 'id_seller',
							'required' => false,
							'default_value' => $fields_value['id_seller'],
							'options' => array(
								'query' => AgileSellerManager::getSellersNV(true, $this->l('Store Shared')),
								'id' => 'id_seller',
								'name' => 'name',
								),
							'desc' => $this->l('If this is private seller data, please choose the seller. Otherwise please choose Store Shared')
							)
						);
		
		
                  if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
                  {
                        require_once(_PS_ROOT_DIR_.'/modules/agilecashondelivery/AgileCarrierCod.php');
                        $carrierCod = new AgileCarrierCod();
                        $carrierSupportCod = $carrierCod->getHandleCodByCarrierId(Tools::getValue('id_carrier'));
                        if(isset($carrierSupportCod[0]))
						{
                            $carrierSupportCod = $carrierSupportCod[0]['handle_cod']; 
						}
						else 
						{
							$carrierSupportCod = false; 
						}
						array_unshift($fields_form['form']['form']['input'],
							array(
								'type' => 'text',
								'name' => 'configureCodSettings',
								)
							);
						array_unshift($fields_form['form']['form']['input'],
							array(
								'type' => 'switch',
								'label' => $this->l('COD support'),
								'name' => 'COD_support',
								'required' => false,
								'desc' => 'If this carrier supports COD, please chose yes. Otherwise please chose no',
								'class' => 't',
								'is_bool' => true,
								'values' => array(
									array(
										'id' => 'COD_support_on',
										'value' => 1
										),
									array(
										'id' => 'COD_support_off',
										'value' => 0
										)
									),
								'hint' => $this->l('Enable the COD support by this carrier.')
								)
							);
				}
			}
		}

		return parent::renderGenericForm($fields_form, $fields_value, $tpl_vars);	
	}
	
	public function setMedia()
	{
		parent::setMedia();
		if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
		{
			$this->addJs(_PS_ROOT_DIR_.'/modules/agilecashondelivery/js/codAdmin.js');
			$this->addCSS(_PS_ROOT_DIR_.'/modules/agilecashondelivery/css/carrier_popup.css');
		}
	}
	
	
	public function getStepFiveFieldsValues($carrier)
	{
		$fields = parent::getStepFiveFieldsValues($carrier);
		if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
		{
			require_once(_PS_ROOT_DIR_.'/modules/agilecashondelivery/AgileCarrierCod.php');
			$carrierCod = new AgileCarrierCod();
			$carrierSupportCod = $carrierCod->getHandleCodByCarrierId(Tools::getValue('id_carrier'));
			if(isset($carrierSupportCod[0]))
			{
				$carrierSupportCod = $carrierSupportCod[0]['handle_cod']; 
			}
			else 
			{
				$carrierSupportCod = false; 
			}
			$fields['COD_support'] = $carrierSupportCod;
			$fields['configureCodSettings'] =  $this->context->link->getModuleLink('agilecashondelivery','feezoneadmin', array(), true);
			return $fields;
		}
		return $fields;
	}

}

