<?php
class ProductDownload extends ProductDownloadCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public function getHtmlLinkFrontSeller()
	{
		$link = $this->getTextLink(false,false) . "&is_seller=1";
		$html = '<a href="'.$link.'" title=""';
		$html .= '>'.$this->display_filename.'</a>';
		return $html;
	}
}
