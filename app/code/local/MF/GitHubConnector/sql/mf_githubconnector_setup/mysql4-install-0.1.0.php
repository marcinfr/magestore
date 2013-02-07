<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */

$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mf_githubconnector_revisions')};
CREATE TABLE {$this->getTable('mf_githubconnector_revisions')} (
  `id` int unsigned NOT NULL auto_increment,
  `sha` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `committer_name` varchar(255) default NULL,
  `committer_email` varchar(255) default NULL,
  `committer_date` datetime default NULL,
  `message` text default NULL,
  `status` int,
  `publisher_name` varchar(255) default NULL,
  `publisher_date` datetime default NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
$installer->endSetup();
