CREATE TABLE IF NOT EXISTS `PREFIX_agile_itemshipping` (
  `id_agile_itemshipping` bigint(11) AUTO_INCREMENT NOT NULL,
  `id_zone` int(10) NULL,
  `id_peoduct` varchar(64) NULL,
  `single_item_fee` float NULL,
  `additional_item_fee` float NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_agile_itemshipping`)
) DEFAULT CHARSET=utf8;
