CREATE TABLE `tasks` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`instance_name` VARCHAR( 255 ) NOT NULL ,
`params` TEXT NOT NULL ,
`status` VARCHAR( 10 ) NOT NULL ,
`created_date` DATETIME NOT NULL ,
`last_try_date` DATETIME NULL ,
`next_try_date` DATETIME NULL ,
`nbtries` INT NULL,
`last_output` TEXT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT = 'The lists of tasks to be performed (or that have been performed)';

ALTER TABLE `tasks` ADD INDEX ( `next_try_date` , `last_try_date` ) ;
