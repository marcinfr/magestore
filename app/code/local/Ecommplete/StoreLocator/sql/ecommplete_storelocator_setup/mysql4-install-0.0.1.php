<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
$installer = $this;

$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS `{$installer->getTable('ecommplete_storeLocator/store')}`;
CREATE TABLE `{$installer->getTable('ecommplete_storeLocator/store')}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);
");

$installer->endSetup();