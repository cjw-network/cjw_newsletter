<?php
/**
 * File blacklist_item_add.php
 *
 * Add an blacklist item
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = 'design:newsletter/blacklist_item_add.tpl';

include_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();
$tpl = templateInit();

$isBlacklistDone = false;
$email = false;
$note = '';

if( $http->hasVariable( 'Email' ) )
{
    $email = trim( $http->variable( 'Email' ) );
}
if( $http->hasVariable( 'Note' ) )
{
    $note = trim( $http->variable( 'Note' ) );
}

// if email is ok than create a new blacklist item
// email to lowercase

$message = '';
$blacklistItemObject = false;

if( $email != '' )
{
    $existingBlacklistItemObject = CjwNewsletterBlacklistItem::fetchByEmail( $email );

    if( !is_object( $existingBlacklistItemObject ) )
    {
        $blacklistItemObject = CjwNewsletterBlacklistItem::create( $email, $note );
    }
    else
    {
        $blacklistItemObject = $existingBlacklistItemObject;
    }

}


// if AddButton was pushed than store new data
if ( $http->hasVariable( 'AddButton' ) )
{
    if( is_object( $blacklistItemObject ) )
    {
        $newsletterUserObject = $blacklistItemObject->attribute( 'newsletter_user_object' );

//        $newsletterUserObject->setBlacklisted();

        $blacklistItemObject->store();

        if( is_object( $newsletterUserObject ) )
        {

            $message = ezpI18n::tr( 'cjw_newsletter/blacklist_item_add', 'Successfully adding newsletter user %nl_user_id with email %email to blacklist', '',
                     array( '%nl_user_id' => $newsletterUserObject->attribute('id'),
                            '%email' => $newsletterUserObject->attribute('email') ));

            $isBlacklistDone = true;
        }
        else
        {
            $message = ezpI18n::tr( 'cjw_newsletter/blacklist_item_add', 'Successfully adding email address %email to blacklist', '',
                     array( '%email' => $blacklistItemObject->attribute('email') ));
            $isBlacklistDone = true;
        }

    }
}
// Cancel
elseif( $http->hasVariable( 'DiscardButton' ) )
{
    $module->redirectTo( '/newsletter/blacklist_item_list' );
}



$viewParameters = array( 'offset'     => 0,
                         'namefilter' => '' );

// variablen mit () in der url in viewparameter übernehmen
// z.B.  ../list/(offset)/4  setzt die viewparametervariable $offset = 3
$userParameters = $Params[ 'UserParameters' ];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl->setVariable( 'is_blacklist_done', $isBlacklistDone );
$tpl->setVariable( 'message', $message );
$tpl->setVariable( 'blacklist_item', $blacklistItemObject );

$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();

$Result[ 'content' ] = $tpl->fetch( $templateFile );
//$Result[ 'ui_context' ] = 'edit';
$Result['path'] =  array( array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/blacklist_item_add', 'Blacklist add' ) ) );
?>