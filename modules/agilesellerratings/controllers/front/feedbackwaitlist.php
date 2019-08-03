<?php
///-build_id: 2017010307.5027
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ . '/modules/agilesellerratings/agilesellerratings.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilesellerratings/AgileRating.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilesellerratings/AgileRatingCriterion.php');

class AgileSellerRatingsFeedBackWaitListModuleFrontController extends ModuleFrontController
{
	public $auth = true;
	public $ssl = true;

	public function postProcess()   {    if (Tools::isSubmit('submitFeedback'))     AgileSellerRatings::procss_feedback();      }         public function setMedia()   {    parent::setMedia();      Media::addJsDef(array(     'asr_pleaseentercomment' => $this->l('Please enter your comment'),     'asr_pleaseenterrating' => $this->l('Please select your rating'),     ));        $this->registerJavascript('asr-ratingform-js','/modules/agilesellerratings/js/ratingform.js',['position' => 'bottom', 'priority' => 100]);     }       public function initContent()   {    parent::initContent();      $this->context->smarty->assign(array(     'criterions' => AgileRatingCriterion::getList($this->context->cookie->id_lang),     'agile_feedbacks' => AgileRating::getFeedbackWaitingList($this->context->cookie->id_customer)    ));      $this->setTemplate('module:agilesellerratings/views/templates/front/feedbackwaitlist.tpl');     }      }  