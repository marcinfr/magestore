<?php

$installer = $this;
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('alekseon_dataflow_shedule')};
CREATE TABLE {$this->getTable('alekseon_dataflow_shedule')} (
  `id` int unsigned NOT NULL auto_increment,
  `code` varchar(255) unique,
  `status` int default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
