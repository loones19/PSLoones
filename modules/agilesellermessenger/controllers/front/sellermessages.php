<?php
///-build_id: 2019051707.2854
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

include_once(_PS_ROOT_DIR_ .'/modules/agilesellermessenger/agilesellermessenger.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilesellermessenger/AgileSellerMessage.php');

class AgileSellerMessengerSellerMessagesModuleFrontController extends AgileModuleFrontController
{
	public function display()   {    if (isset($_GET['filename']))     AgileSellerMessenger::openUploadedFile($_GET['id_seller'],  $_GET['filename']);    else    {     parent::display();    }   }            public function postProcess()   {    if (Tools::isSubmit('submitReplyMessage'))    {        $R2065A4598F5AC52A855CD972A4D582B5 = new AgileSellerMessage(Tools::getValue('id_agile_sellermessage'));        if(!Validate::isLoadedObject($R2065A4598F5AC52A855CD972A4D582B5))        {            $this->_errors[] = Tools::displayError('Invalid message id');      return;        }        if(empty($this->_errors))        {      $this->_errors = AgileSellerMessenger::sendSellerReply($this->context->customer->firstname . ' ' . $this->context->customer->lastname);              }     if(empty($this->_errors))      Tools::redirect(self::$link->getModuleLink('agilesellermessenger', 'sellermessages', array(),true));          }      }         public function initContent()   {    parent::initContent();      $R41D339616252569CA6CA7A840CC51F6B = AgileSellerMessage::getSellerMessages($this->sellerinfo->id_seller,0, 0, true);          $R5C919D231D7C35A7ACCF5729150F7342 = AgileSellerMessage::getSellerMessages($this->sellerinfo->id_seller, 0,0, false, $this->p, $this->n);            self::$smarty->assign(array(              'seller_tab_id' => 8              ,'id_customer' => $this->context->customer->id              ,'sellermessages' => $R5C919D231D7C35A7ACCF5729150F7342     ,'hide_email' => intval(Configuration::get('ASMGER_HIDE_EMAIL'))              ));      $this->setTemplate('module:agilesellermessenger/views/templates/front/sellermessages.tpl');     }        }  