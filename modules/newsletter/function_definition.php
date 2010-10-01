<?php
/**
 * File function_definition.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$FunctionList = array();

// {fetch('modul1','list', hash('as_object', true()))|attribute(show)}
$FunctionList['subscription_list'] = array( 'name' => 'subscription_list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchSubscriptionList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'list_contentobject_id',
                                                             'type' => 'integer',
                                                             'required' => true ),
                                                      array( 'name' => 'status',
                                                             'type' => 'string',
                                                             'required' => true ),
                                                      array( 'name' => 'limit',
                                                             'type' => 'integer',
                                                             'default' => 50,
                                                             'required' => false ),
                                                      array( 'name' => 'offset',
                                                             'type' => 'integer',
                                                             'default' => 0,
                                                             'required' => false ),
                                                      array( 'name' => 'as_object',
                                                             'type' => 'integer',
                                                             'default' => true,
                                                             'required' => false ) )
                        );

//{fetch('modul1','count', hash())}
$FunctionList['subscription_list_count'] = array( 'name' => 'subscription_list_count',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'include_file' => 'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                        'class' => 'CjwNewsletterFunctionCollection',
                                                        'method' => 'fetchSubscriptionListCount' ),
                                'parameter_type' => 'standard',
                                'parameters' => array(
                                                      array( 'name' => 'list_contentobject_id',
                                                             'type' => 'integer',
                                                             'required' => true ),
                                                      array( 'name' => 'status',
                                                             'type' => 'string',
                                                             'required' => true ),

                                                      )
                        );

// {fetch('modul1','list', hash('as_object', true()))|attribute(show)}
$FunctionList['import_subscription_list'] = array( 'name' => 'import_subscription_list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchImportSubscriptionList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'import_id',
                                                             'type' => 'integer',
                                                             'required' => true ),
                                                      array( 'name' => 'limit',
                                                             'type' => 'integer',
                                                             'default' => 50,
                                                             'required' => false ),
                                                      array( 'name' => 'offset',
                                                             'type' => 'integer',
                                                             'default' => 0,
                                                             'required' => false ),
                                                      array( 'name' => 'as_object',
                                                             'type' => 'integer',
                                                             'default' => true,
                                                             'required' => false ) )
                        );


$FunctionList['import_subscription_list_count'] = array( 'name' => 'import_subscription_list_count',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'include_file' => 'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                        'class' => 'CjwNewsletterFunctionCollection',
                                                        'method' => 'fetchImportSubscriptionListCount' ),
                                'parameter_type' => 'standard',
                                'parameters' => array(
                                                      array( 'name' => 'import_id',
                                                             'type' => 'integer',
                                                             'required' => true )
                                                     )

                        );


// {fetch('newsletter','user_list', hash('as_object', true()))|attribute(show)}
$FunctionList['user_list'] = array( 'name' => 'user_list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchUserList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'limit',
                                                             'type' => 'integer',
                                                             'default' => 50,
                                                             'required' => false ),
                                                      array( 'name' => 'offset',
                                                             'type' => 'integer',
                                                             'default' => 0,
                                                             'required' => false ),
                                                      array( 'name' => 'email_search',
                                                             'type' => 'string',
                                                             'default' => '',
                                                             'required' => false ),
                                                      array( 'name' => 'sort_by',
                                                             'type' => 'array',
                                                             'default' => array( 'created' => 'desc'),
                                                             'required' => false ),
                                                      array( 'name' => 'as_object',
                                                             'type' => 'integer',
                                                             'default' => true,
                                                             'required' => false ) )
                        );
// {fetch('newsletter','user_list_count', hash()))|attribute(show)}
$FunctionList['user_list_count'] = array( 'name' => 'user_list_count',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchUserListCount' ),
                               'parameter_type' => 'standard',
                               'parameters' => array(
                                                      array( 'name' => 'email_search',
                                                             'type' => 'string',
                                                             'default' => '',
                                                             'required' => false )
                                                     )
                        );

// {fetch('newsletter','edition_send_item_list', hash('as_object', true()))|attribute(show)}
$FunctionList['edition_send_item_list'] = array( 'name' => 'edition_send_item_list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchEditonSendItemList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'limit',
                                                             'type' => 'integer',
                                                             'default' => 50,
                                                             'required' => false ),
                                                      array( 'name' => 'offset',
                                                             'type' => 'integer',
                                                             'default' => 0,
                                                             'required' => false ),
                                                      array( 'name' => 'newsletter_user_id',
                                                             'type' => 'string',
                                                             'default' => '',
                                                             'required' => false ),

                                                      array( 'name' => 'as_object',
                                                             'type' => 'integer',
                                                             'default' => true,
                                                             'required' => false ) )
                        );
// {fetch('newsletter','edition_send_item_list_count', hash()))|attribute(show)}
$FunctionList['edition_send_item_list_count'] = array( 'name' => 'edition_send_item_list_count',
                               'operation_types' => array( 'read' ),
                               'call_method' => array('include_file' =>'extension/cjw_newsletter/modules/newsletter/cjwnewsletterfunctioncollection.php',
                                                      'class' => 'CjwNewsletterFunctionCollection',
                                                      'method' => 'fetchEditonSendItemListCount' ),
                               'parameter_type' => 'standard',
                               'parameters' => array(
                                                      array( 'name' => 'newsletter_user_id',
                                                             'type' => 'integer',
                                                             'default' => 0,
                                                             'required' => false )
                                                     )
                        );
?>