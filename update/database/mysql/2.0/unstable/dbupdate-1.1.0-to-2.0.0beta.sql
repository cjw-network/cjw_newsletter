ALTER TABLE `cjwnl_user` ADD `external_user_id` INT( 11 ) DEFAULT NULL AFTER `note`;

ALTER TABLE `cjwnl_subscription` CHANGE `newsletter_user_id` `newsletter_user_id` INT( 11 ) DEFAULT NULL;

ALTER TABLE `cjwnl_list` ADD `is_virtual` tinyint(1) NOT NULL DEFAULT '0',
ADD `virtual_filter` TEXT NOT NULL;

ALTER TABLE `cjwnl_edition_send` ADD `list_contentobject_version` INT( 11 ) NOT NULL DEFAULT '0' AFTER `list_contentobject_id`;
ALTER TABLE `cjwnl_edition_send` ADD `list_is_virtual` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `list_contentobject_version`;

ALTER TABLE `cjwnl_list` ADD `email_reply_to` VARCHAR( 255 ) NOT NULL AFTER `email_sender` ,
ADD `email_return_path` VARCHAR( 255 ) NOT NULL AFTER `email_reply_to`;
ALTER TABLE `cjwnl_edition_send` ADD `email_reply_to` VARCHAR( 255 ) NOT NULL AFTER `email_sender` ,
ADD `email_return_path` VARCHAR( 255 ) NOT NULL AFTER `email_reply_to`;

ALTER TABLE `cjwnl_user` ADD `custom_data_text_1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `cjwnl_user` ADD `custom_data_text_2` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `cjwnl_user` ADD `custom_data_text_3` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `cjwnl_user` ADD `custom_data_text_4` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `cjwnl_edition_send` ADD `mailqueue_process_scheduled` INT( 11 ) NULL DEFAULT NULL AFTER `mailqueue_created`;
ALTER TABLE `cjwnl_edition_send` CHANGE `status` `status` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0- WAIT_FOR_PROCESS, 1 - MAILQUEUE_CREATED, 2 - MAILQUEUE_PROCESS_STARTED, 3 - MAILQUEUE_PROCESS_FINISHED,4 -STATUS_WAIT_FOR_SCHEDULE, 9 - ABORT';