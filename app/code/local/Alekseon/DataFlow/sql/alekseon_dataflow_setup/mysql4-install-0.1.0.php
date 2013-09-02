<?php

$installer = $this;
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('alekseon_dataflow_schedule_configuration')}`;
DROP TABLE IF EXISTS `{$this->getTable('alekseon_dataflow_schedule_execution')}`;
DROP TABLE IF EXISTS `{$this->getTable('alekseon_dataflow_schedule')}`;

CREATE TABLE `{$this->getTable('alekseon_dataflow_schedule')}` (
  `id` int unsigned NOT NULL auto_increment,
  `code` varchar(255) unique,
  `name` varchar(255),
  `profiles` text,
  `schedule` varchar(255),
  `status` tinyint default 0,
  `prev_status` tinyint default 0,
  `priority` int default 0,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$this->getTable('alekseon_dataflow_schedule_configuration')}` (
  `id` int unsigned NOT NULL auto_increment,
  `schedule_id` int unsigned NOT NULL,
  `profile_code` varchar(255),
  `element_id` varchar(255),
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scheduleId_profileCode_elemetId` (`schedule_id`,`element_id`,`profile_code`),
  CONSTRAINT `fk_config_scheduleId` FOREIGN KEY (`schedule_id`) REFERENCES `{$this->getTable('alekseon_dataflow_schedule')}`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$this->getTable('alekseon_dataflow_schedule_execution')}` (
  `id` int unsigned NOT NULL auto_increment,
  `schedule_id` int unsigned NOT NULL,
  `type` tinyint default 0,
  `status` tinyint default 0,
  `message` text,
  `user` varchar(255),
  `scheduled_at` datetime,
  `executed_at` datetime,
  `finished_at` datetime,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_execution_scheduleId` FOREIGN KEY (`schedule_id`) REFERENCES `{$this->getTable('alekseon_dataflow_schedule')}`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
