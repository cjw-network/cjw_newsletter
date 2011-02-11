CREATE SEQUENCE cjwnl_blacklist_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_edition_send_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_edition_send_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_import_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_mailbox_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_mailbox_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_subscription_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE cjwnl_user_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE cjwnl_blacklist_item (
  id integer NOT NULL DEFAULT nextval(('cjwnl_blacklist_item_s'::text)::regclass),
  email_hash character varying(255) DEFAULT NULL::character varying,
  email character varying(255) DEFAULT NULL::character varying,
  newsletter_user_id integer NOT NULL DEFAULT 0,
  created integer,
  creator_contentobject_id integer,
  note text,
  CONSTRAINT cjwnl_blacklist_item_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_blacklist_item_user_id ON cjwnl_blacklist_item USING btree (newsletter_user_id);

CREATE TABLE cjwnl_edition (
  contentobject_attribute_id integer NOT NULL DEFAULT 0,
  contentobject_attribute_version integer NOT NULL DEFAULT 0,
  contentobject_id integer NOT NULL DEFAULT 0,
  contentclass_id integer NOT NULL DEFAULT 0,
  CONSTRAINT cjwnl_edition_pkey PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version)
);

CREATE INDEX cjwnl_edition_contentobject_attr_id ON cjwnl_edition USING btree (contentobject_attribute_id);
CREATE INDEX cjwnl_edition_contentobject_attr_version ON cjwnl_edition USING btree (contentobject_attribute_version);
CREATE INDEX cjwnl_edition_contentobject_id ON cjwnl_edition USING btree (contentobject_id);

CREATE TABLE cjwnl_edition_send (
  id integer NOT NULL DEFAULT nextval(('cjwnl_edition_send_s'::text)::regclass),
  list_contentobject_id integer NOT NULL DEFAULT 0,
  edition_contentobject_id integer NOT NULL DEFAULT 0,
  edition_contentobject_version integer NOT NULL DEFAULT 0,
  created integer NOT NULL DEFAULT 0,
  status smallint NOT NULL DEFAULT 0::smallint,
  siteaccess character varying(50) NOT NULL,
  output_format_array_string character varying(50) NOT NULL,
  creator_id integer NOT NULL DEFAULT 0,
  mailqueue_created integer NOT NULL DEFAULT 0,
  mailqueue_process_started integer NOT NULL DEFAULT 0,
  mailqueue_process_finished integer NOT NULL DEFAULT 0,
  mailqueue_process_aborted integer NOT NULL DEFAULT 0,
  output_xml text NOT NULL,
  hash character varying(255) NOT NULL,
  email_sender character varying(255) NOT NULL,
  email_sender_name character varying(255) NOT NULL,
  personalize_content smallint NOT NULL DEFAULT 0::smallint,
  CONSTRAINT cjwnl_edition_send_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_edition_send_edition_coid ON cjwnl_edition_send USING btree (edition_contentobject_id);
CREATE INDEX cjwnl_edition_send_edition_co_version ON cjwnl_edition_send USING btree (edition_contentobject_version);
CREATE INDEX cjwnl_edition_send_list_coid ON cjwnl_edition_send USING btree (list_contentobject_id);

CREATE TABLE cjwnl_edition_send_item (
  id integer NOT NULL DEFAULT nextval(('cjwnl_edition_send_item_s'::text)::regclass),
  edition_send_id integer NOT NULL DEFAULT 0,
  newsletter_user_id integer NOT NULL DEFAULT 0,
  output_format_id smallint NOT NULL DEFAULT 0::smallint,
  subscription_id integer NOT NULL DEFAULT 0,
  created integer NOT NULL DEFAULT 0,
  processed integer NOT NULL DEFAULT 0,
  status smallint NOT NULL DEFAULT 0::smallint,
  hash character varying(255) NOT NULL,
  bounced integer NOT NULL DEFAULT 0,
  CONSTRAINT cjwnl_edition_send_item_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_edition_send_item_esid ON cjwnl_edition_send_item USING btree (edition_send_id);
CREATE INDEX cjwnl_edition_send_item_nuid ON cjwnl_edition_send_item USING btree (newsletter_user_id);
CREATE INDEX cjwnl_edition_send_item_sid ON cjwnl_edition_send_item USING btree (subscription_id);

CREATE TABLE cjwnl_import (
  id integer NOT NULL DEFAULT nextval(('cjwnl_import_s'::text)::regclass),
  "type" character varying(255) NOT NULL,
  list_contentobject_id integer,
  created integer,
  creator_contentobject_id character varying(45) DEFAULT NULL::character varying,
  note text,
  data_text text NOT NULL,
  remote_id character varying(255) NOT NULL,
  data_xml text NOT NULL,
  imported integer NOT NULL DEFAULT 0,
  imported_user_count integer NOT NULL DEFAULT 0,
  imported_subscription_count integer NOT NULL DEFAULT 0,
  CONSTRAINT cjwnl_import_pkey PRIMARY KEY (id)
);

CREATE TABLE cjwnl_list (
  contentobject_attribute_id integer NOT NULL DEFAULT 0,
  contentobject_attribute_version integer NOT NULL DEFAULT 0,
  contentobject_id integer NOT NULL DEFAULT 0,
  contentclass_id integer NOT NULL DEFAULT 0,
  main_siteaccess character varying(255) NOT NULL,
  siteaccess_array_string character varying(255) NOT NULL,
  output_format_array_string character varying(255) NOT NULL,
  email_sender_name character varying(255) NOT NULL,
  email_sender character varying(255) NOT NULL,
  email_receiver_test character varying(255) NOT NULL,
  auto_approve_registered_user smallint NOT NULL DEFAULT 0::smallint,
  skin_name character varying(255) NOT NULL DEFAULT 'default'::character varying,
  personalize_content smallint NOT NULL DEFAULT 0::smallint,
  user_data_fields text NOT NULL,
  CONSTRAINT cjwnl_list_pkey PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version)
);

CREATE INDEX cjwnl_list_contentobject_attr_id ON cjwnl_list USING btree (contentobject_attribute_id);
CREATE INDEX cjwnl_list_contentobject_attr_version ON cjwnl_list USING btree (contentobject_attribute_version);
CREATE INDEX cjwnl_list_contentobject_id ON cjwnl_list USING btree (contentobject_id);

CREATE TABLE cjwnl_mailbox (
  id integer NOT NULL DEFAULT nextval(('cjwnl_mailbox_s'::text)::regclass),
  email character varying(255) DEFAULT NULL::character varying,
  server character varying(255) DEFAULT NULL::character varying,
  port integer,
  user_name character varying(255) DEFAULT NULL::character varying,
  "password" character varying(255) DEFAULT NULL::character varying,
  "type" character varying(10) DEFAULT 'imap'::character varying,
  delete_mails_from_server smallint NOT NULL DEFAULT 0::smallint,
  is_ssl smallint NOT NULL DEFAULT 0::smallint,
  is_activated smallint DEFAULT 1::smallint,
  last_server_connect integer,
  CONSTRAINT cjwnl_mailbox_pkey PRIMARY KEY (id)
);

CREATE TABLE cjwnl_mailbox_item (
  id integer NOT NULL DEFAULT nextval(('cjwnl_mailbox_item_s'::text)::regclass),
  mailbox_id integer,
  message_id integer,
  message_identifier character varying(50) DEFAULT NULL::character varying,
  message_size integer NOT NULL DEFAULT 0,
  created integer,
  processed integer,
  bounce_code character varying(255) DEFAULT NULL::character varying,
  email_from character varying(255) DEFAULT NULL::character varying,
  email_to character varying(255) DEFAULT NULL::character varying,
  email_subject character varying(255) DEFAULT NULL::character varying,
  email_send_date integer,
  edition_send_id integer,
  edition_send_item_id integer NOT NULL DEFAULT 0,
  newsletter_user_id integer,
  CONSTRAINT cjwnl_mailbox_item_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_mailbox_item_edition_sid ON cjwnl_mailbox_item USING btree (edition_send_id);
CREATE INDEX cjwnl_mailbox_item_mailbox_id ON cjwnl_mailbox_item USING btree (mailbox_id);
CREATE INDEX cjwnl_mailbox_item_newsletter_uid ON cjwnl_mailbox_item USING btree (newsletter_user_id);

CREATE TABLE cjwnl_subscription (
  id integer NOT NULL DEFAULT nextval(('cjwnl_subscription_s'::text)::regclass),
  list_contentobject_id integer NOT NULL DEFAULT 0,
  newsletter_user_id integer NOT NULL DEFAULT 0,
  hash character varying(255) NOT NULL,
  status smallint NOT NULL DEFAULT 0,
  output_format_array_string character varying(255) NOT NULL,
  creator_contentobject_id integer NOT NULL DEFAULT 0,
  created integer NOT NULL DEFAULT 0,
  modifier_contentobject_id integer NOT NULL DEFAULT 0,
  modified integer NOT NULL DEFAULT 0,
  confirmed integer NOT NULL DEFAULT 0,
  approved integer NOT NULL DEFAULT 0,
  removed integer NOT NULL DEFAULT 0,
  remote_id character varying(255) NOT NULL,
  import_id integer NOT NULL DEFAULT 0,
  CONSTRAINT cjwnl_subscription_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_subscription_import_id ON cjwnl_subscription USING btree (import_id);
CREATE INDEX cjwnl_subscription_list_contentobject_id ON cjwnl_subscription USING btree (list_contentobject_id);
CREATE INDEX cjwnl_subscription_newsletter_user_id ON cjwnl_subscription USING btree (newsletter_user_id);

CREATE TABLE cjwnl_user (
  id integer NOT NULL DEFAULT nextval(('cjwnl_user_s'::text)::regclass),
  email character varying(255) DEFAULT NULL::character varying,
  salutation smallint,
  first_name character varying(255) DEFAULT NULL::character varying,
  last_name character varying(255) DEFAULT NULL::character varying,
  organisation character varying(255) DEFAULT NULL::character varying,
  birthday character varying(10) DEFAULT NULL::character varying,
  data_xml text,
  hash character varying(255) DEFAULT NULL::character varying,
  ez_user_id integer,
  status smallint NOT NULL DEFAULT 0::smallint,
  creator_contentobject_id integer NOT NULL DEFAULT 0,
  created integer NOT NULL DEFAULT 0,
  modified integer NOT NULL DEFAULT 0,
  modifier_contentobject_id integer NOT NULL DEFAULT 0,
  confirmed integer NOT NULL DEFAULT 0,
  removed integer NOT NULL DEFAULT 0,
  bounced integer NOT NULL DEFAULT 0,
  blacklisted integer NOT NULL DEFAULT 0,
  note text,
  remote_id character varying(255) DEFAULT NULL::character varying,
  import_id integer,
  bounce_count smallint DEFAULT 0::smallint,
  data_text text,
  CONSTRAINT cjwnl_user_pkey PRIMARY KEY (id)
);

CREATE INDEX cjwnl_user_ez_import_id ON cjwnl_user USING btree (import_id);
CREATE INDEX cjwnl_user_ez_user_id ON cjwnl_user USING btree (ez_user_id);
