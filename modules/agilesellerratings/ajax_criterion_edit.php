<?php
///-build_id: 2017010307.5027
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/agilesellerratings.php');
require_once(dirname(__FILE__).'/AgileRatingCriterion.php');

$mymodule = new AgileSellerRatings();

$id = intval(Tools::getValue('id'));
$languages = Language::getLanguages(true);


echo '<div class="margin-form" id="divCriterion">
			<input type="hidden" name="criterion_id" id="criterion_id" value="' . $id . '" />
            <table  class="table"><thead><tr>
            <th>'.$mymodule->l('Language').'</th><th>'.$mymodule->l('Criterion').'</th>';            
echo'
            </tr></thead>
             <tbody>
            ';

$idx = 0;
foreach($languages AS $lang)
{
    $criterion = new AgileRatingCriterion($id,$lang['id_lang']);
	$brpos = strpos($lang['name'],"(");
	$lname = ($brpos >0? substr($lang['name'],0,$brpos) : $lang['name']);
    echo '<tr><td>'. $lang['iso_code'] . '-'. $lname . '</td>';
    echo '<td><input type="text" name="criterion_'. $lang['id_lang'] . '" id="criterion_'. $lang['id_lang'] . '" value="' . $criterion->name . '" /></td>';
    echo '<td>&nbsp;</td></tr>';
    $idx++;
}

echo '</tbody>
        </table> 
	</div>
';