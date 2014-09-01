CREATE TABLE cjwnl_blacklist_item (
  id int(11) NOT NULL AUTO_INCREMENT,
  email_hash varchar(255) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  newsletter_user_id int(11) NOT NULL,
  created int(11) DEFAULT NULL,
  creator_contentobject_id int(11) DEFAULT NULL,
  note text,
  PRIMARY KEY (id),
  KEY cjwnewsletter_user_id (newsletter_user_id)
) COMMENT='table with blacklisted user emails' ENGINE=InnoDB;


CREATE TABLE cjwnl_edition (
  contentobject_attribute_id int(11) NOT NULL,
  contentobject_attribute_version int(11) NOT NULL,
  contentobject_id int(11) NOT NULL,
  contentclass_id int(11) NOT NULL,
  PRIMARY KEY (contentobject_attribute_id,contentobject_attribute_version),
  KEY contentobject_id (contentobject_id),
  KEY contentobject_attribute_id (contentobject_attribute_id),
  KEY contentobject_attribute_version (contentobject_attribute_version)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_edition_send (
  id int(11) NOT NULL AUTO_INCREMENT,
  list_contentobject_id int(11) NOT NULL,
  list_contentobject_version int(11) NOT NULL DEFAULT '0',
  list_is_virtual tinyint(1) NOT NULL DEFAULT '0',
  edition_contentobject_id int(11) NOT NULL,
  edition_contentobject_version int(11) NOT NULL,
  created int(11) NOT NULL COMMENT 'when the edition is marked for send out',
  status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0- WAIT_FOR_PROCESS, 1 - MAILQUEUE_CREATED, 2 - MAILQUEUE_PROCESS_STARTED, 3 - MAILQUEUE_PROCESS_FINISHED, 4 -STATUS_WAIT_FOR_SCHEDULE, 9 - ABORT',
  siteaccess varchar(50) NOT NULL,
  output_format_array_string varchar(50) NOT NULL,
  creator_id int(11) NOT NULL,
  mailqueue_created int(11) NOT NULL,
  mailqueue_process_scheduled int(11) NULL DEFAULT NULL,
  mailqueue_process_started int(11) NOT NULL,
  mailqueue_process_finished int(11) NOT NULL,
  mailqueue_process_aborted int(11) NOT NULL,
  output_xml longtext NOT NULL COMMENT 'xml with newsletter version of html, text',
  hash varchar(255) NOT NULL,
  email_sender varchar(255) NOT NULL,
  email_reply_to varchar(255) NOT NULL,
  email_return_path varchar(255) NOT NULL,
  email_sender_name varchar(255) NOT NULL,
  personalize_content tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY edition_contentobject_id (edition_contentobject_id),
  KEY edition_contentobject_version (edition_contentobject_version),
  KEY list_contentobject_id (list_contentobject_id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_edition_send_item (
  id int(11) NOT NULL AUTO_INCREMENT,
  edition_send_id int(11) NOT NULL,
  newsletter_user_id int(11) NOT NULL,
  output_format_id tinyint(4) NOT NULL DEFAULT '0',
  subscription_id int(11) NOT NULL,
  created int(11) NOT NULL COMMENT 'timestamp',
  processed int(11) NOT NULL COMMENT 'timestamp',
  status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - NEW, 1 - SEND, 9 - ABBORT ',
  hash varchar(255) NOT NULL,
  bounced int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY edition_send_id (edition_send_id),
  KEY newsletter_user_id (newsletter_user_id),
  KEY subscription_id (subscription_id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_import (
  id int(11) NOT NULL AUTO_INCREMENT,
  type varchar(255) NOT NULL COMMENT 'import type',
  list_contentobject_id int(11) DEFAULT NULL,
  created int(11) DEFAULT NULL,
  creator_contentobject_id varchar(45) DEFAULT NULL,
  note text,
  data_text longtext NOT NULL,
  remote_id varchar(255) NOT NULL,
  data_xml longtext NOT NULL,
  imported int(11) NOT NULL,
  imported_user_count int(11) NOT NULL,
  imported_subscription_count int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_list (
  contentobject_attribute_id int(11) NOT NULL,
  contentobject_attribute_version int(11) NOT NULL,
  contentobject_id int(11) NOT NULL,
  contentclass_id int(11) NOT NULL,
  main_siteaccess varchar(255) NOT NULL,
  siteaccess_array_string varchar(255) NOT NULL,
  output_format_array_string varchar(255) NOT NULL,
  email_sender_name varchar(255) NOT NULL,
  email_sender varchar(255) NOT NULL,
  email_reply_to varchar(255) NOT NULL,
  email_return_path varchar(255) NOT NULL,
  email_receiver_test varchar(255) NOT NULL,
  auto_approve_registered_user tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  skin_name varchar(255) NOT NULL DEFAULT 'default',
  personalize_content tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - false, 1- true',
  user_data_fields text NOT NULL COMMENT 'definition user data fields for data input ( show / required ) as xml',
  is_virtual tinyint(1) NOT NULL DEFAULT '0',
  virtual_filter text NOT NULL,
  PRIMARY KEY (contentobject_attribute_id,contentobject_attribute_version),
  KEY contentobject_id (contentobject_id),
  KEY contentobject_attribute_id (contentobject_attribute_id),
  KEY contentobject_attribute_version (contentobject_attribute_version)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_mailbox (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) DEFAULT NULL,
  server varchar(255) DEFAULT NULL,
  port int(11) DEFAULT NULL,
  user_name varchar(255) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  type varchar(10) DEFAULT 'imap' COMMENT 'pop3, imap',
  delete_mails_from_server tinyint(1) NOT NULL DEFAULT '0',
  is_ssl tinyint(1) NOT NULL DEFAULT '0',
  is_activated tinyint(1) DEFAULT '1',
  last_server_connect int(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_mailbox_item (
  id int(11) NOT NULL AUTO_INCREMENT,
  mailbox_id int(11) DEFAULT NULL,
  message_id int(11) DEFAULT NULL,
  message_identifier varchar(50) DEFAULT NULL,
  message_size int(11) NOT NULL DEFAULT '0',
  created int(11) DEFAULT NULL,
  processed int(11) DEFAULT NULL,
  bounce_code varchar(255) DEFAULT NULL,
  email_from varchar(255) DEFAULT NULL,
  email_to varchar(255) DEFAULT NULL,
  email_subject varchar(255) DEFAULT NULL,
  email_send_date int(11) DEFAULT NULL,
  edition_send_id int(11) DEFAULT NULL,
  edition_send_item_id int(11) NOT NULL,
  newsletter_user_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY edition_send_id (edition_send_id),
  KEY mailbox_id (mailbox_id),
  KEY newsletter_user_id (newsletter_user_id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_subscription (
  id int(11) NOT NULL AUTO_INCREMENT,
  list_contentobject_id int(11) NOT NULL,
  newsletter_user_id int(11) DEFAULT NULL,
  hash varchar(255) NOT NULL,
  status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - PENDING, 1 - CONFIRMED,  2 - APPROVED, 3 - REMOVED_SELF, 4 - REMOVED_ADMIN',
  output_format_array_string varchar(255) NOT NULL COMMENT ';0;1;',
  creator_contentobject_id int(11) NOT NULL,
  created int(11) NOT NULL,
  modifier_contentobject_id int(11) NOT NULL,
  modified int(11) NOT NULL,
  confirmed int(11) NOT NULL,
  approved int(11) NOT NULL,
  removed int(11) NOT NULL,
  remote_id varchar(255) NOT NULL,
  import_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY list_contentobject_id (list_contentobject_id),
  KEY newsletter_user_id (newsletter_user_id),
  KEY import_id (import_id)
) ENGINE=InnoDB;


CREATE TABLE cjwnl_user (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) DEFAULT NULL,
  salutation tinyint(4) DEFAULT NULL COMMENT '0-no, 1-Mr, 2-Ms',
  first_name varchar(255) DEFAULT NULL,
  last_name varchar(255) DEFAULT NULL,
  organisation varchar(255) DEFAULT NULL,
  birthday varchar(10) DEFAULT NULL COMMENT 'yyyy-mm-dd',
  data_xml text COMMENT 'extra data kodiert in xml',
  hash varchar(255) DEFAULT NULL,
  ez_user_id int(11) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - PENDING, 1 - CONFIRMED',
  creator_contentobject_id int(11) NOT NULL,
  created int(11) NOT NULL,
  modified int(11) NOT NULL,
  modifier_contentobject_id int(11) NOT NULL,
  confirmed int(11) NOT NULL COMMENT 'timestamp email confirmation',
  removed int(11) NOT NULL,
  bounced int(11) NOT NULL,
  blacklisted int(11) NOT NULL,
  note text,
  external_user_id int(11) DEFAULT NULL,
  remote_id varchar(255) DEFAULT NULL,
  import_id int(11) DEFAULT NULL,
  bounce_count tinyint(4) DEFAULT '0',
  data_text text COMMENT 'field is free for any kind of text data',
  custom_data_text_1 varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  custom_data_text_2 varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  custom_data_text_3 varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  custom_data_text_4 varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (id),
  KEY ez_user_id (ez_user_id),
  KEY import_id (import_id)
) ENGINE=InnoDB;
