ALTER TABLE cjwnl_edition_send CHANGE COLUMN status status tinyint(4) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_edition_send_item CHANGE COLUMN output_format_id output_format_id tinyint(4) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_edition_send_item CHANGE COLUMN status status tinyint(4) NOT NULL DEFAULT '0';
ALTER TABLE cjwnl_mailbox CHANGE COLUMN is_ssl is_ssl tinyint(1) NOT NULL DEFAULT '0';
