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

class AgileSellerRatingsRatingListModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function init()   {    parent::init();   }     public function initContent()   {    parent::initContent();        $R01787CB5A57D47DC8A648998E4A6512C = new AgileSellerRatings();    $R01787CB5A57D47DC8A648998E4A6512C->process_ratinglist($this);        $this->setTemplate('module:agilesellerratings/views/templates/front/ratinglist.tpl');     }        }  