-- START: from 0.1.3

-- rename tables  cjwnewsletter_... => cjwnl_...

RENAME TABLE `cjwnewsletter_list` TO `cjwnl_list`;
RENAME TABLE `cjwnewsletter_user` TO `cjwnl_user`;
RENAME TABLE `cjwnewsletter_subscription` TO `cjwnl_subscription`;
RENAME TABLE `cjwnewsletter_edition` TO `cjwnl_edition`;
RENAME TABLE `cjwnewsletter_edition_send` TO `cjwnl_edition_send`;
RENAME TABLE `cjwnewsletter_edition_send_item` TO `cjwnl_edition_send_item`;

ALTER TABLE cjwnl_edition ADD INDEX contentobject_attribute_id ( contentobject_attribute_id );
ALTER TABLE cjwnl_edition ADD INDEX contentobject_attribute_version ( contentobject_attribute_version );
ALTER TABLE cjwnl_edition ADD INDEX contentobject_id ( contentobject_id );
ALTER TABLE cjwnl_edition_send ADD INDEX edition_contentobject_id ( edition_contentobject_id );
ALTER TABLE cjwnl_edition_send ADD INDEX edition_contentobject_version ( edition_contentobject_version );
ALTER TABLE cjwnl_edition_send ADD INDEX list_contentobject_id ( list_contentobject_id );
ALTER TABLE cjwnl_edition_send_item ADD COLUMN bounced int(11) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_edition_send_item ADD COLUMN hash varchar(255) NOT NULL DEFAULT '';
ALTER TABLE cjwnl_edition_send_item ADD INDEX edition_send_id ( edition_send_id );
ALTER TABLE cjwnl_edition_send_item ADD INDEX newsletter_user_id ( newsletter_user_id );
ALTER TABLE cjwnl_edition_send_item ADD INDEX subscription_id ( subscription_id );
ALTER TABLE cjwnl_list ADD INDEX contentobject_attribute_id ( contentobject_attribute_id );
ALTER TABLE cjwnl_list ADD INDEX contentobject_attribute_version ( contentobject_attribute_version );
ALTER TABLE cjwnl_list ADD INDEX contentobject_id ( contentobject_id );
ALTER TABLE cjwnl_subscription ADD COLUMN import_id int(11) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_subscription ADD COLUMN remote_id varchar(255) NOT NULL DEFAULT '';
ALTER TABLE cjwnl_subscription ADD INDEX import_id ( import_id );
ALTER TABLE cjwnl_subscription ADD INDEX list_contentobject_id ( list_contentobject_id );
ALTER TABLE cjwnl_subscription ADD INDEX newsletter_user_id ( newsletter_user_id );
ALTER TABLE cjwnl_user ADD COLUMN birthday varchar(10) DEFAULT NULL;
ALTER TABLE cjwnl_user ADD COLUMN blacklisted int(11) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_user ADD COLUMN bounce_count tinyint(4) DEFAULT '0';
ALTER TABLE cjwnl_user ADD COLUMN bounced int(11) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_user ADD COLUMN data_text text;
ALTER TABLE cjwnl_user ADD COLUMN data_xml text;
ALTER TABLE cjwnl_user ADD COLUMN import_id int(11) DEFAULT NULL;
ALTER TABLE cjwnl_user ADD COLUMN note text;
ALTER TABLE cjwnl_user ADD COLUMN organisation varchar(255) DEFAULT NULL;
ALTER TABLE cjwnl_user ADD COLUMN remote_id varchar(255) DEFAULT NULL;
ALTER TABLE cjwnl_user DROP COLUMN additional_data;
ALTER TABLE cjwnl_user ADD INDEX ez_user_id ( ez_user_id );
ALTER TABLE cjwnl_user ADD INDEX import_id ( import_id );
ALTER TABLE cjwnl_user ADD creator_contentobject_id INT( 11 ) NOT NULL AFTER status;
ALTER TABLE cjwnl_user ADD modifier_contentobject_id INT( 11 ) NOT NULL AFTER modified; 
ALTER TABLE cjwnl_user ADD removed INT( 11 ) NOT NULL AFTER confirmed; 
ALTER TABLE cjwnl_edition_send ADD personalize_content tinyint(1) NOT NULL DEFAULT '0' AFTER email_sender_name;
ALTER TABLE cjwnl_edition_send CHANGE output_xml output_xml LONGTEXT NOT NULL COMMENT 'xml with newsletter version of html, text';

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
) COMMENT='table with blacklisted user emails';

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
);

CREATE TABLE cjwnl_mailbox (
  delete_mails_from_server tinyint(1) NOT NULL DEFAULT '0',
  email varchar(255) DEFAULT NULL,
  id int(11) NOT NULL AUTO_INCREMENT,
  is_activated tinyint(1) DEFAULT '1',
  is_ssl tinyint(1) NOT NULL DEFAULT '0',
  last_server_connect int(11) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  port int(11) DEFAULT NULL,
  server varchar(255) DEFAULT NULL,
  type varchar(10) DEFAULT 'imap',
  user varchar(255) DEFAULT NULL,
  PRIMARY KEY ( id )
);
CREATE TABLE cjwnl_mailbox_item (
  bounce_code VARCHAR( 10 ) NULL DEFAULT NULL,
  created int(11) DEFAULT NULL,
  edition_send_id int(11) DEFAULT NULL,
  edition_send_item_id int(11) NOT NULL DEFAULT '0',
  email_from varchar(255) DEFAULT NULL,
  email_send_date int(11) DEFAULT NULL,
  email_subject varchar(255) DEFAULT NULL,
  email_to varchar(255) DEFAULT NULL,
  id int(11) NOT NULL AUTO_INCREMENT,
  mailbox_id int(11) DEFAULT NULL,
  message_id int(11) DEFAULT NULL,
  message_identifier varchar(255) DEFAULT NULL,
  message_size int(11) NOT NULL DEFAULT '0',
  newsletter_user_id int(11) DEFAULT NULL,
  processed int(11) DEFAULT NULL,
  PRIMARY KEY ( id ),
  KEY edition_send_id ( edition_send_id ),
  KEY mailbox_id ( mailbox_id ),
  KEY newsletter_user_id ( newsletter_user_id )
);

-- END: from 0.1.3