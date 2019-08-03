CREATE TABLE IF NOT EXISTS `PREFIX_agile_rating` (
  `id_agile_rating` int(10) unsigned NOT NULL auto_increment,
  `id_target` int(10) unsigned NOT NULL,
  `id_order` int(10) unsigned NOT NULL,  
  `id_type` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,  
  `id_customer` int(10) NOT NULL,
  `customer` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `grade` float unsigned NOT NULL,
  `ip_address` varchar(64) NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_agile_rating`)
)  DEFAULT_CHARSET_COLLATION;

CREATE TABLE IF NOT EXISTS `PREFIX_agile_rating_criterion` (
  `id_agile_rating_criterion` int(10) unsigned NOT NULL auto_increment,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_agile_rating_criterion`)
)  DEFAULT_CHARSET_COLLATION;


CREATE TABLE IF NOT EXISTS `PREFIX_agile_rating_criterion_lang` (
  `id_agile_rating_criterion_lang` int(10) unsigned NOT NULL auto_increment,
  `id_agile_rating_criterion` int(10),
  `id_lang` int(10) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id_agile_rating_criterion_lang`)
)  DEFAULT_CHARSET_COLLATION;

CREATE TABLE IF NOT EXISTS `PREFIX_agile_rating_grade` (
  `id_agile_rating` int(10) unsigned NOT NULL,
  `id_agile_rating_criterion` int(10) unsigned NOT NULL,
  `grade` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_agile_rating`, `id_agile_rating_criterion`)
) DEFAULT_CHARSET_COLLATION;