<?php
/**
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registred Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
	exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Tm_Footerlink extends Module implements WidgetInterface
{
	/* @var boolean error */
	protected $error = false;
	private $templateFile;
	
	public function __construct()
	{
		$this->name = 'tm_footerlink';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'TemplateMela';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->trans('TM - Footer Link block', array(), 'Modules.Footerlink');
		$this->description = $this->trans('Adds a block with additional links.', array(), 'Modules.Footerlink');
		
		//$this->confirmUninstall = $this->trans('Are you sure you want to delete all your links?', array(), 'Modules.Footerlink');
		
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
	
		$this->templateFile = 'module:tm_footerlink/views/templates/hook/tm_footerlink.tpl';
	}

	public function install()
	{
		Configuration::updateValue('TM_FOOTERBLOCKLINK_TITLE', array('1' => 'Block link'));
		
		return  parent::install() &&
            $this->installDB() &&
            $this->registerHook('displayFooterAfter');
	}

	public function installDB()
    {
        $return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmfooterlink` (
			`id_tmfooterlink` int(10) NOT NULL AUTO_INCREMENT,
			`url` varchar(254) NOT NULL,
			`new_window` TINYINT(1) NOT NULL,
			PRIMARY KEY(`id_tmfooterlink`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8'
		);
			
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmfooterlink_shop` (
			`id_tmfooterlink` int(10) NOT NULL AUTO_INCREMENT,
			`id_shop` int(10) NOT NULL,
			PRIMARY KEY(`id_tmfooterlink`, `id_shop`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8'
		);
			
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmfooterlink_lang` (
			`id_tmfooterlink` int(10) NOT NULL,
			`id_lang` int(10) NOT NULL,
			`text` varchar(62) NOT NULL,
			PRIMARY KEY(`id_tmfooterlink`, `id_lang`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8'
		);
		
		return $return;
	}

	public function uninstall()
	{
		return parent::uninstall() && $this->uninstallDB();
	}
	
	public function uninstallDB($drop_table = true) {
		$ret = true;
        if ($drop_table) {
            $ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmfooterlink`') && 
				Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmfooterlink_shop`') &&
				Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tmfooterlink_lang`');
							
			$ret &= Configuration::deleteByName('TM_FOOTERBLOCKLINK_TITLE');
			$ret &= Configuration::deleteByName('TM_FOOTERBLOCKLINK_URL');
        }
        return $ret;
	}

	/**
	 * @param $id
	 *
	 * @return bool|array
	 */
	public function getLinkById($id)
	{
		if ((int)$id > 0)
		{
			$link = [];
			$sql = 'SELECT b.`id_tmfooterlink`, b.`url`, b.`new_window` FROM `'._DB_PREFIX_.'tmfooterlink` b WHERE b.id_tmfooterlink='.(int)$id;

			if (!$results = Db::getInstance()->getRow($sql))
				return false;

			$link['id_tmfooterlink'] = (int)$results['id_tmfooterlink'];
			$link['url'] = $results['url'];
			$link['newWindow'] = $results['new_window'];

			$results = Db::getInstance()->executeS('SELECT `id_lang`, `text` FROM '._DB_PREFIX_.'tmfooterlink_lang WHERE `id_tmfooterlink`='.(int)$link['id_tmfooterlink']);

			$results_lang = array();
			foreach ($results as $result)
				$results_lang[(int)$result['id_lang']] = $result;

			foreach (Language::getLanguages(false) as $lang)
				$link['text'][(int)$lang['id_lang']] = (isset($results_lang[(int)$lang['id_lang']])) ? $results_lang[(int)$lang['id_lang']]['text'] : false;

			return $link;
		}

		return false;
	}
	
	public function getLinks()
	{
		$result = array();
		// Get id and url

		$sql = 'SELECT b.`id_tmfooterlink`, b.`url`, b.`new_window`
				FROM `'._DB_PREFIX_.'tmfooterlink` b';
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_ALL)
			$sql .= ' JOIN `'._DB_PREFIX_.'tmfooterlink_shop` bs ON b.`id_tmfooterlink` = bs.`id_tmfooterlink` AND bs.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).') ';
		$sql .= (int)Configuration::get('TM_BLOCKLINK_ORDERWAY') == 1 ? ' ORDER BY `id_tmfooterlink` DESC' : '';

		if (!$links = Db::getInstance()->executeS($sql))
			return false;

		$i = 0;
		foreach ($links as $link)
		{
			$result[$i]['id'] = $link['id_tmfooterlink'];
			$result[$i]['url'] = $link['url'];
			$result[$i]['newWindow'] = $link['new_window'];
			// Get multilingual text

			if (!$texts = Db::getInstance()->executeS('
					SELECT `id_lang`, `text`
					FROM '._DB_PREFIX_.'tmfooterlink_lang
					WHERE `id_tmfooterlink`='.(int)$link['id_tmfooterlink']
				)
			)
				return false;

			foreach ($texts as $text)
				$result[$i]['text_'.$text['id_lang']] = $text['text'];

			$i++;
		}

		return $result;
	}
	
	public function addLink()
	{
		if (!($languages = Language::getLanguages(true)))
			return false;
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');

		if ((int)Tools::getValue('id_tmfooterlink') > 0)
		{
			$id_link = (int)Tools::getValue('id_tmfooterlink');
			if (!Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'tmfooterlink SET `url` = \''.pSQL(Tools::getValue('url')).'\', `new_window` = '.pSQL((int)Tools::getValue('newWindow')).' WHERE `id_tmfooterlink` = '.(int)$id_link))
				return false;
			if (!Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tmfooterlink_lang WHERE `id_tmfooterlink` = '.(int)$id_link))
				return false;

			foreach ($languages as $language)
				if (Tools::getValue('text_'.$language['id_lang']) != false )
				{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tmfooterlink_lang (`id_tmfooterlink`, `id_lang`, `text`) VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL(Tools::getValue('text_'.$language['id_lang'])).'\')'))
						return false;
				}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tmfooterlink_lang VALUES ('.(int)$id_link.', '.$language['id_lang'].', \''.pSQL(Tools::getValue('text_'.$id_lang_default)).'\')'))
						return false;
		}
		else
		{
			if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tmfooterlink (`id_tmfooterlink`, `url`, `new_window`)
														VALUES (NULL, \''.pSQL(Tools::getValue('url')).'\', '.((Tools::getValue('newWindow') && Tools::getValue('newWindow')) == 'on' ? 1 : 0).')') ||
				!$id_link = Db::getInstance()->Insert_ID()
			)
				return false;

			foreach ($languages as $language)
				if (Tools::getValue('text_'.$language['id_lang']) != false)
				{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tmfooterlink_lang (`id_tmfooterlink`, `id_lang`, `text`)
																VALUES ('.(int)$id_link.', '.(int)$language['id_lang'].', \''.pSQL(Tools::getValue('text_'.$language['id_lang'])).'\')')
					)
						return false;
				}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tmfooterlink_lang (`id_tmfooterlink`, `id_lang`, `text`) VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL(Tools::getValue('text_'.$id_lang_default)).'\')'))
						return false;
		}

		Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tmfooterlink_shop WHERE id_tmfooterlink='.(int)$id_link);

		$shops = Shop::getShops(true, null, true);
		if (!Shop::isFeatureActive() || (Shop::isFeatureActive() && count($shops) == 1))
		{
			Db::getInstance()->insert('tmfooterlink_shop', array(
				'id_tmfooterlink' => (int)$id_link,
				'id_shop' => (int)Context::getContext()->shop->id,
			));
		}
		else
		{
			$assos_shop = Tools::getValue('checkBoxShopAsso_configuration');
			if (empty($assos_shop))
				return false;
			foreach ($assos_shop as $id_shop => $row)
				Db::getInstance()->insert('tmfooterlink_shop', array(
					'id_tmfooterlink' => (int)$id_link,
					'id_shop' => (int)$id_shop,
				));
		}

		return true;
	}
		
	
	public function deleteLink()
	{
		return (Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tmfooterlink WHERE `id_tmfooterlink` = '.(int)Tools::getValue('id')) &&
			Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tmfooterlink_shop WHERE `id_tmfooterlink` = '.(int)Tools::getValue('id')) &&
			Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tmfooterlink_lang WHERE `id_tmfooterlink` = '.(int)Tools::getValue('id')));
	}

	public function updateTitle()
	{
		$languages = Language::getLanguages();
		$result = array();
		foreach ($languages as $language)
			$result[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
		if (!Configuration::updateValue('TM_FOOTERBLOCKLINK_TITLE', $result))
			return false;

		return Configuration::updateValue('TM_FOOTERBLOCKLINK_URL', Tools::getValue('title_url'));
	}
	
	public function getContent()
	{
		$this->_html = '';

		// Add a link
		if (Tools::isSubmit('submitLinkAdd') /*|| Tools::isSubmit('updatetm_footerlink')*/)
		{
			if (Tools::getValue('text_'.Configuration::get('PS_LANG_DEFAULT')) != true || Tools::getValue('url') != true )
				$this->_html .= $this->displayError($this->l('You must fill in all fields.'));
			elseif (!Validate::isUrl(str_replace('http://', '', Tools::getValue('url'))))
				$this->_html .= $this->displayError($this->l('Bad URL'));
			else
				if ($this->addLink())
					$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
				else
					$this->_html .= $this->displayError($this->l('An error occurred during link creation.'));
		}
		// Update the block title
		elseif (Tools::isSubmit('submitTitle'))
		{

			if (Tools::getValue('title_'.Configuration::get('PS_LANG_DEFAULT')) != true)
				$this->_html .= $this->displayError($this->l('"title" field cannot be empty.'));
			elseif (Tools::getValue('title_url') != true && !Validate::isUrl(str_replace('http://', '', Tools::getValue('title_url'))))
				$this->_html .= $this->displayError($this->l('The \'title\' field is invalid'));
			elseif (!Validate::isGenericName(Tools::getValue('title_'.Configuration::get('PS_LANG_DEFAULT'))))
				$this->_html .= $this->displayError($this->l('The \'title\' field is invalid'));
			elseif (!$this->updateTitle())
				$this->_html .= $this->displayError($this->l('An error occurred during title updating.'));
			else
				$this->_html .= $this->displayConfirmation($this->l('The block title has been updated.'));
		}
		// Delete a link
		elseif (Tools::isSubmit('deletetm_footerlink') && Tools::getValue('id'))
		{
			if (!is_numeric(Tools::getValue('id')) || !$this->deleteLink())
				$this->_html .= $this->displayError($this->l('An error occurred during link deletion.'));
			else
				$this->_html .= $this->displayConfirmation($this->l('The link has been deleted.'));
		}

		if (Tools::getValue('submitOrderWay'))
		{
			if (Configuration::updateValue('TM_BLOCKLINK_ORDERWAY', (int)(Tools::getValue('orderWay'))))
				$this->_html .= $this->displayConfirmation($this->l('Sort order updated'));
			else
				$this->_html .= $this->displayError($this->l('An error occurred during sort order set-up.'));
		}

		$this->_html .= $this->renderForm();
		$this->_html .= $this->renderList();
		
		return $this->_html;
	}
	
	public function renderList()
	{
		$fields_list = array(
			'id' => array(
				'title' => $this->l('Link ID'),
				'type' => 'text',
			),
			'text_'.$this->context->language->id => array(
				'title' => $this->l('Link text'),
				'type' => 'text',
			),
			'url' => array(
				'title' => $this->l('URL'),
				'type' => 'text',
			),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = false;
		
		$helper->title = $this->l('Link list');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$links = $this->getLinks();
		if (is_array($links) && count($links))
			return $helper->generateList($links, $fields_list);
		else
			return false;
	}

	public function renderForm()
	{
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Add a new link'),
					'icon' => 'icon-plus-sign-alt'
				),
				'input' => array(
					array(
						'type' => 'hidden',
						'name' => 'id_tmfooterlink',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Link text'),
						'name' => 'text',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('URL'),
						'name' => 'url',
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Open in a new window'),
						'name' => 'newWindow',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),

				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitLinkAdd',
				)
			),
		);

		if (Shop::isFeatureActive())
		{
			$fields_form_1['form']['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$fields_form_2 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Block settings'),
					'icon' => 'icon-plus-sign-alt'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Block title'),
						'name' => 'title',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('URL for the block\'s title'),
						'name' => 'title_url',
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitTitle',
				)
			),
		);

		$fields_form_3 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Link display settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('List order'),
						'name' => 'orderWay',
						'options' => array(
							'query' => array(
								array(
									'id' => 0,
									'name' => $this->l('most recent links first')
								),
								array(
									'id' => 1,
									'name' => $this->l('oldest links first')
								)
							),
							'id' => 'id',
							'name' => 'name',
						)
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitOrderWay',
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = 'id_tmfooterlink';
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_1, $fields_form_2, $fields_form_3));
	}

	public function getConfigFieldsValues()
	{
		$fields_values = array(
			'id_tmfooterlink' => Tools::getValue('id_tmfooterlink'),
			'url' => Tools::getValue('url'),
			'newWindow' => Tools::getValue('newWindow'),
			'orderWay' => Tools::getValue('orderWay', Configuration::get('TM_BLOCKLINK_ORDERWAY')),
			'title_url' => Tools::getValue('title_url', Configuration::get('TM_FOOTERBLOCKLINK_URL')),
		);


		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['text'][$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang']);
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('title', Configuration::get('TM_FOOTERBLOCKLINK_TITLE', $lang['id_lang']));
		}

		if (Tools::getIsset('updatetm_footerlink') && (int)Tools::getValue('id') > 0)
			$fields_values = array_merge($fields_values, $this->getLinkById((int)Tools::getValue('id')));
		
		return $fields_values;
	}
	
	public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('tm_footerlink'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('tm_footerlink'));
    }
	
	public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $links = $this->getLinks();
        return array(
            'tm_footerlink_links' => $links,
			'title' => Configuration::get('TM_FOOTERBLOCKLINK_TITLE', $this->context->language->id),
			'url' => Configuration::get('TM_FOOTERBLOCKLINK_URL', $this->context->language->id),
			'lang' => 'text_'.$this->context->language->id,
			'linkurl' => 'url_'.$this->context->language->id
        );
    }	
}
