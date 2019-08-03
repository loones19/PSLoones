<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AgileMultipleSellerSellerEventsModuleFrontController extends AgileModuleFrontController{

	public function setMedia()	{
		parent::setMedia();

		$this->addJS(array(
					_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/events.js?v=1.0',
				)
			);
	}

	private $id_seller;

	public $object;

	public function init(){
		parent::init();
		$this->id_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
				include_once(_PS_MODULE_DIR_.'agilemultipleseller/SellerEvent.php');
		
		self::$smarty->assign(array(
					'seller_tab_id' => 101
				)
			);
	}

	private function _debug($var,$exit = false){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if($exit) exit;
	}

	public function initContent(){
		parent::initContent();

				
								
		$count_events = SellerEvent::getCountEventsBySellerId($this->sellerinfo->id_seller,$this->context->language->id);
		if(isset($count_events[0]['count'])){
			$count_events = $count_events[0]['count'];
		}
				$this->pagination($count_events);
		$events = SellerEvent::getEventsBySellerId($this->sellerinfo->id_seller,$this->context->language->id,$this->p,$this->n);
				self::$smarty->assign(
				array(
					'events' => $events,
					'psize' => (int)Tools::getValue('n'),
					'pnum' => (int)Tools::getValue('p'),
				)
			);
								$this->setTemplate('sellerevents.tpl');
	}

	public function postProcess(){
		if(Tools::getValue('process') == 'inactive'){
			$this->object = new SellerEvent((int)Tools::getValue('id_event'));
			if(Validate::isLoadedObject($this->object)){
				$this->object->active = 0;
				$this->object->save();
				Tools::redirect($this->context->link->getModuleLink('agilemultipleseller', 'sellerevents', array('p'=>Tools::getValue('p'))));
			}
		}elseif(Tools::getValue('process') == 'active'){
			$this->object = new SellerEvent((int)Tools::getValue('id_event'));
			if(Validate::isLoadedObject($this->object)){
				$this->object->active = 1;
				$this->object->save();
				Tools::redirect($this->context->link->getModuleLink('agilemultipleseller', 'sellerevents', array('p'=>Tools::getValue('p'))));
			}
		}elseif(Tools::getValue('process') == 'delete' and Tools::getValue('id_event')){
			$this->object = new SellerEvent((int)Tools::getValue('id_event'));
			if(Validate::isLoadedObject($this->object) and $this->object->id_seller == AgileSellerManager::getLinkedSellerID($this->context->customer->id)){
				if($this->object->delete()){
					Tools::redirect($this->context->link->getModuleLink('agilemultipleseller', 'sellerevents', array()));
				}
			}else
				$this->errors[] = Tools::displayError('Cannot find event');
		}
	}

	public function pagination($total_events = null)
    {
        if (!self::$initialized) {
            $this->init();
        } elseif (!$this->context) {
            $this->context = Context::getContext();
        }

                $default_events_per_page = 10;
        $n_array = array($default_events_per_page, $default_events_per_page * 2, $default_events_per_page * 5);

        if ((int)Tools::getValue('n') && (int)$total_events > 0) {
            $n_array[] = $total_events;
        }
                $this->n = $default_events_per_page;
        if (isset($this->context->cookie->nb_item_per_page) && in_array($this->context->cookie->nb_item_per_page, $n_array)) {
            $this->n = (int)$this->context->cookie->nb_item_per_page;
        }

        if ((int)Tools::getValue('n') && in_array((int)Tools::getValue('n'), $n_array)) {
            $this->n = (int)Tools::getValue('n');
        }

                $this->p = (int)Tools::getValue('p', 1);
                if (!is_numeric($this->p) || $this->p < 1) {
            Tools::redirect($this->context->link->getPaginationLink(false, false, $this->n, false, 1, false));
        }

                $current_url = preg_replace('/(?:(\?)|&amp;)p=\d+/', '$1', Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']));

        if ($this->n != $default_events_per_page || isset($this->context->cookie->nb_item_per_page)) {
            $this->context->cookie->nb_item_per_page = $this->n;
        }

        $pages_nb = ceil($total_events / (int)$this->n);
        if ($this->p > $pages_nb && $total_events != 0) {
            Tools::redirect($this->context->link->getPaginationLink(false, false, $this->n, false, $pages_nb, false));
        }

        $range = 2;         $start = (int)($this->p - $range);
        if ($start < 1) {
            $start = 1;
        }

        $stop = (int)($this->p + $range);
        if ($stop > $pages_nb) {
            $stop = (int)$pages_nb;
        }

        $this->context->smarty->assign(array(
            'nb_products'       => $total_events,
            'products_per_page' => $this->n,
            'pages_nb'          => $pages_nb,
            'p'                 => $this->p,
            'n'                 => $this->n,
            'nArray'            => $n_array,
            'range'             => $range,
            'start'             => $start,
            'stop'              => $stop,
            'current_url'       => $current_url,
        ));
    }
}