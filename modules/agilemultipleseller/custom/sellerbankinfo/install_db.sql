CREATE TABLE IF NOT EXISTS `PREFIX_sellerbankinfo` (
  `id_sellerbankinfo` bigint(11) AUTO_INCREMENT NOT NULL,
  `id_seller` bigint(11) NULL,
  `shop_name` varchar(256) NULL,
  `business_name` varchar(256) NULL,
  `business_address1` varchar(256) NULL,
  `business_address2` varchar(256) NULL,
  `account_name` varchar(256) NULL,
  `account_number` varchar(256) NULL,
  `bank_name` varchar(256) NULL,
  `bank_address` varchar(256) NULL,
  `passwd` varchar(256) NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_sellerbankinfo`)
) DEFAULT_CHARSET_COLLATION;