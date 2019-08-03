What is this?
==================
This customization to allow sellers to make special request to stote such as order a clothing bag.
Once seller submit the form, a email eill be sent to store owner to notify the request.

How to istall this customization
==============================
1. Install Agile Multiple Seller module 2.1.6.0 or above
2. Configure the module to use customization field "Number1" and "Number2"
3. Copy unziped custom package and copy whole folder "sellersummarybag" to following location
   /modules/agilemultipleseller/custom/
4. Copy and paste following code block to
 File: /morules/agilemultipleseller/controllers/front/sellersummary.php
 Location: at the bottom of method initContent(), before line "$this->setTemplate('sellersummary.tpl');"

		///custom code block begin
		if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php" ))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php");
			$modulebag = new SellerSummaryBag();
			$modulebag->initContent($sellerinfo, $this->context);
		}
		///Custom code block begin

5. Copy and paste following code block to
 File: /morules/agilemultipleseller/controllers/front/sellersummary.php
 Location: at bottom of method postProcess();

		///Custom code block begin
 		if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php" ))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php");
			$modulebag = new SellerSummaryBag();
			$reterrs = $modulebag->postProcess($this->context);
			if(!empty($reterrs))
			{
				$this->errors = array_merge($this->errors, $reterrs);
				return;
			}
		}
		///Custom code block end

6. AgileMultipleSellerBase changes. 
In function createSellerSignUpFields()
		$number_of_bags = false;
		///custom code block begin
		if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php" ))
		{
			$number_of_bags = true;
		}
In function createSellerInfo() 
 		///custom code block begin
		if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/selllersummarybag/SellerSummaryBag.php" ))
		{
			$numberofbags = Tools::getValue('txtBags');
			SellerSummaryBag::sendBagOrderEmail($sellerinfo, $numberofbags);
		}
