<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

include(dirname(__FILE__).'/config/settings.inc.php');

if (!$con = mysql_connect('localhost', _DB_USER_, _DB_PASSWD_)) {
	die('An error occurred while connecting to the MySQL server!<br><br>' . mysql_error());
}

if (!mysql_select_db(_DB_NAME_)) {
	die('An error occurred while connecting to the database!<br><br>' . mysql_error());
}

if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '"._DB_PREFIX_."seller_event'"))==1) 
    echo _DB_PREFIX_."seller_event Table exists <br/>";
else{
	$sql = "CREATE TABLE `"._DB_PREFIX_."seller_event` (
				`id_event` INT(11) NOT NULL AUTO_INCREMENT,
				`id_seller` INT(11) NULL DEFAULT NULL,
				`id_customer` INT(11) NULL DEFAULT NULL,
				`active` INT(11) NULL DEFAULT '1',
				`place` VARCHAR(255) NULL DEFAULT NULL,
				`start_date` DATE NULL DEFAULT NULL,
				`end_date` DATE NULL DEFAULT NULL,
				`create_date` DATETIME NULL DEFAULT NULL,
				PRIMARY KEY (`id_event`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			AUTO_INCREMENT=9;";
	runsql($sql);
}

if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '"._DB_PREFIX_."seller_event_lang'"))==1) 
    echo _DB_PREFIX_."seller_event_lang Table exists <br/>";
else{
	$sql = "CREATE TABLE `"._DB_PREFIX_."seller_event_lang` (
				`id_event_lang` INT(11) NOT NULL AUTO_INCREMENT,
				`id_event` INT(11) NULL DEFAULT NULL,
				`id_lang` INT(11) NULL DEFAULT NULL,
				`title` VARCHAR(255) NULL DEFAULT NULL,
				`description` TEXT NULL,
				PRIMARY KEY (`id_event_lang`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			AUTO_INCREMENT=15;";
	runsql($sql);
}



function runsql($sql)
{
	echo "<br><br>" . $sql . "<BR><p style='color:red;'>";
	if (!mysql_query($sql)) 
	{
		die('A MySQL error has occurred!<br><br>' . mysql_error() . '<BR>');
	}
	echo "</p>";
}