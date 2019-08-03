<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class	AgileMultipleSellerMailer extends ObjectModel
{
	public static function SendTranslateSubject($id_lang, $template, $templateVars, $to, $toName = NULL, $from = NULL, $fromName = NULL, $fileAttachment = NULL, $modeSMTP = NULL, $templatePath = _PS_MAIL_DIR_, $die = false, $id_shop = NULL, $bcc = null)
	{
				switch($template)
		{
			case "order_return_request":
				return Mail::Send($id_lang, 'order_return_request', Mail::l('Order Return Request',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "new_order":
				return Mail::Send($id_lang, 'new_order', Mail::l('New Order',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "fund_request":
				return Mail::Send($id_lang, 'fund_request', Mail::l('Fund request from a seller',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "app_selleraccount":
				return Mail::Send($id_lang, 'app_selleraccount', Mail::l('Your seller account approved!',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "new_selleraccount":
				return Mail::Send($id_lang, 'new_selleraccount', Mail::l('Your seller account',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "new_selleraccount_admin":
				return Mail::Send($id_lang, 'new_selleraccount_admin', Mail::l('A new seller account has been added',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "new_product":
				return Mail::Send($id_lang, 'new_product', Mail::l('New product approval request',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "product_approval":
				return Mail::Send($id_lang, 'product_approval', Mail::l('Product Approved',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
			case "product_disapproval":
				return Mail::Send($id_lang, 'product_disapproval', Mail::l('Product Disapproved',$id_lang), $templateVars, $to, $toName, $from, $fromName, $fileAttachment, $modeSMTP, $templatePath, $die, $id_shop, $bcc);
		}
	}	

}

