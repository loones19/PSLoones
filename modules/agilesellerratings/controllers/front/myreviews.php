<?php
///-build_id: 2017010307.5027
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

include_once(dirname(__FILE__).'/../../agilesellerratings.php');
include_once(dirname(__FILE__).'/../../AgileRating.php');

class AgileSellerRatingsMyReviewsModuleFrontController extends AgileModuleFrontController
{
	
	public function setMedia()   {    parent::setMedia();      Media::addJsDef(array(     'asr_pleaseenterreply' => $this->l('Please entery your reply message'),    ));        $this->registerJavascript('asr-myreview-js','/modules/agilesellerratings/js/myreview.js',['position' => 'bottom', 'priority' => 100]);     }      public function postProcess()   {    if (Tools::isSubmit('submitResponse'))    {     $RB7B1DC9B333DF94E3C6AE8D8C77A7A43 = new AgileRating(Tools::getValue('id_agile_rating'));     if(!Validate::isLoadedObject($RB7B1DC9B333DF94E3C6AE8D8C77A7A43))        {      $this->_errors[] = Tools::displayError('Invalid review id');      return;        }        if(empty($this->_errors))        {      $RB7B1DC9B333DF94E3C6AE8D8C77A7A43->response = Tools::getValue('response');      $RB7B1DC9B333DF94E3C6AE8D8C77A7A43->date_upd = date('Y-m-d H:i:s');       $RB7B1DC9B333DF94E3C6AE8D8C77A7A43->save();              }          }      }         public function initContent()   {    parent::initContent();      $R1CB81FC3B3C09968BE4D24E34B6D233A = AgileRating::getCount($this->sellerinfo->id_seller, AgileSellerRatings::RATING_TYPE_SELLER);    $R756E0EEC3C6CAE7914F5E44651852446 = AgileRating::getList($this->sellerinfo->id_seller, AgileSellerRatings::RATING_TYPE_SELLER, $this->p, $this->n);            self::$smarty->assign(array(              'seller_tab_id' => 9              ,'id_customer' => $this->context->customer->id     ,'reviews' => $R756E0EEC3C6CAE7914F5E44651852446     ));      $this->setTemplate('module:agilesellerratings/views/templates/front/myreviews.tpl');     }        }  