<?php
/**
 * File subscription_list_csvimport.php
 *
 * import csv data to a subscription list
 * if an nl user with email of csv already exists => override existing data with csv data if it is not empty
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$nodeId = (int) $Params['NodeId'];
$importId = (int) $Params['ImportId'];
$listNode = eZContentObjectTreeNode::fetch( $nodeId );
if ( !$listNode )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$listContentObjectId = $listNode->attribute( 'contentobject_id' );
$systemNode = $listNode->attribute( 'parent' );

$csvDataArray = array();
$csvDelimiter = ';';
$csvSupportedDelims = array( ',', ';', '|' );
$csvFilePath = false;
$importCsvFile = false;
$selectedOutputFormatArray = array( 0 );
$firstRowIsLabel = false;
$listSubscriptionArray = array();
$note = '';
$importObject = false;

if ( eZHTTPFile::canFetch( 'UploadCsvFile' ) )
{
    $importId = 0;
}
if ( $http->hasPostVariable( 'CsvFilePath' ) )
{
    $csvFilePath = $http->variable( 'CsvFilePath' );
}
if ( $http->hasPostVariable( 'SelectedOutputFormatArray' ) )
{
    $selectedOutputFormatArray = $http->variable( 'SelectedOutputFormatArray' );
}
if ( $http->hasPostVariable( 'CsvDelimiter' ) )
{
    $csvDelimiter = $http->variable( 'CsvDelimiter' );
    if ( !in_array( $csvDelimiter, $csvSupportedDelims  ) )
        return $module->redirectToView( 'subscription_list_csvimport', array( $nodeId, $importId ), null, array( 'error' => 'CSV_DELIM_ERROR' ) );
}
if ( $http->hasPostVariable( 'FirstRowIsLabel' ) )
{
    $firstRowIsLabel = true;
}
if ( $http->hasPostVariable( 'Note' ) )
{
    $note = $http->variable( 'Note' );
}

if ( $http->hasPostVariable( 'CancelButton' ) )
{
    $csvFilePath = '';
    return $module->redirectToView( 'subscription_list', array( $nodeId ) );
}
elseif ( $http->hasPostVariable( 'ImportButton' ) )
{
    // check if user has rights to import the users
    $user = eZUser::currentUser();
    $access = $user->hasAccessTo( 'newsletter', 'subscription_list_csvimport_import' );
    if ( $access['accessWord'] == 'yes' )
    {
        $importCsvFile = true;
    }
    else
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

// upload csv and store data
if ( eZHTTPFile::canFetch( 'UploadCsvFile' ) && $importId == 0 )
{

    $importType = 'cjwnl_csv';
    $dataText = '';
    $remoteId = false;
    //$remoteId = 'csv:' . md5( $csvFilePath );

    // create new Import Object
    $importObject = CjwNewsletterImport::create( $listContentObjectId,
                                                 $importType,
                                                 $note,
                                                 $dataText,
                                                 $remoteId );
    $importObject->store();

    $binaryFile = eZHTTPFile::fetch( 'UploadCsvFile' );
    $filePathUpload = $binaryFile->attribute( 'filename' );

    $fileSep = eZSys::fileSeparator();
    // $fileSize = filesize( $filePathUpload );
    //$siteDir =  eZSys::siteDir();
    $dir = eZSys::varDirectory() . $fileSep . 'cjw_newsletter' . $fileSep . 'csvimport';

    $importId = $importObject->attribute('id');
    $fileName = $importId .'-'. date( "Ymd-His", $importObject->attribute('created') ) .'-'. $binaryFile->attribute( 'original_filename' );
    $csvFilePath = $dir . $fileSep . $fileName;
    $importObject->setAttribute( 'data_text', $csvFilePath );
    $importObject->setAttribute( 'note', $note );

    // create dir
    eZDir::mkdir( $dir, false, true );
    $createResult = copy( $filePathUpload, $csvFilePath );

    $importObject->store();

    // after import object is created redirect to view with import_id
    //return $module->redirectToView( 'subscription_list_csvimport', array( $nodeId, $importId ) );
}
// load import Id if not 0
else
{
    if ( $importId != 0 )
    {
        $importObject = CjwNewsletterImport::fetch( $importId );
        if ( !is_object( $importObject ) )
        {
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        // get stored csv filepath from db
        $csvFilePath = $importObject->attribute( 'data_text' );
    }
}

// read import result
if ( file_exists( getImportResultFilePath( $importId ) ) )
{
    $listSubscriptionArray = getImportResultFromFile( $importId );
    $csvParserObject = new CjwNewsletterCsvParser( $csvFilePath, $csvDelimiter, $firstRowIsLabel );
    $csvDataArray = $csvParserObject->getCsvDataArray();
}
else
{
    if ( file_exists( $csvFilePath )  )
    {
        $csvParserObject = new CjwNewsletterCsvParser( $csvFilePath, $csvDelimiter, $firstRowIsLabel );
        $csvDataArray = $csvParserObject->getCsvDataArray();
    }
}
// read csv data


//if ( file_exists( $csvFilePath )  )
//{
    //$csvParserObject = new CjwNewsletterCsvParser( $csvFilePath, $csvDelimiter, $firstRowIsLabel );
    //$csvDataArray = $csvParserObject->getCsvDataArray();

    // start data import
    if ( $importCsvFile == true )
    {
        CjwNewsletterLog::writeNotice(
                                            'subscription_list_csvimport',
                                            'import',
                                            'start',
                                             array( 'import_id' => $importObject->attribute( 'id' ),
                                                    'csv_array_count' => count( $csvDataArray ),
                                                    'current_user' => eZUser::currentUserID() ) );


        foreach ( $csvDataArray as $rowId => $item )
        {
            if( isset( $item[ 'email' ] ) )
                $email = trim( $item[ 'email' ] );
            else
                $email = '';

            if( isset( $item[ 'salutation' ] ) )
                $salutation = (int) $item[ 'salutation' ];
            else
                $salutation = 0;

            if( isset( $item[ 'first_name' ] ) )
                $firstName = $item[ 'first_name' ];
            else
                $firstName = '';

            if( isset( $item[ 'last_name' ] ) )
                $lastName = $item[ 'last_name' ];
            else
                $lastName = '';

            $eZUserId = false;
            $newsletterUserId = 0;

            $emailOk = ezcMailTools::validateEmailAddress( $email );
            $subscriptionObject = null;
            $createNewUser = 0; // 0 - no, 1 - yes, 2 - updated
            $createNewSubscription = 0;

            // store status from existing objects and new stati after import/update
            $existingUserStatus = -1;
            $existingSubscriptionStatus = -1;
            $newUserStatus = -1;
            $newSubscriptionStatus = -1;

            $userIsBlacklistedOrRemoved = false;

            if ( !$emailOk )
            {
                $emailOk = 0;
            }
            else
            {
                 $emailOk = 1;

                // 1. check if an nl user for email already exists
                //    no   -> create new one with status """confirmed"""
                //         -> subscribe to nl list with status """approved"""
                //    yes  -> subscribe to nl list with status """approved"""
                $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $email );

                // update existing
                if ( is_object( $existingNewsletterUserObject ) )
                {
                    $userObject = $existingNewsletterUserObject;
                    $updateUserDataIfExists = true;
                    $existingUserStatus = $userObject->attribute( 'status' );

                    if ( $userObject->isOnBlacklist() ||
                         $userObject->isRemovedSelf() )
                    {
                        $userIsBlacklistedOrRemoved = true;
                    }

                    // only user which are not blacklisted or not self removed
                    // can get a new subscription
                    if ( $userIsBlacklistedOrRemoved === true )
                    {
                        // 0
                        $createNewUser = 0;
                    }
                    elseif ( $updateUserDataIfExists === true )
                    {
                        // updated
                        $createNewUser = 2;

                        if ( $salutation != 0 )
                            $userObject->setAttribute( 'salutation', $salutation );
                        if ( $firstName != '' )
                            $userObject->setAttribute( 'first_name', $firstName );
                        if ( $lastName != '' )
                            $userObject->setAttribute( 'last_name', $lastName );

                        $userObject->setAttribute( 'status', CjwNewsletterUser::STATUS_CONFIRMED );
                        $userObject->setAttribute( 'import_id', $importId );
                        // set new remote_id
                        $userObject->setAttribute( 'remote_id', 'cjwnl:csvimport:'. CjwNewsletterUtils::generateUniqueMd5Hash( $userObject->attribute( 'id' ) ) );
                        $userObject->store();

                        $newUserStatus = $userObject->attribute('status');
                    }
                }
                // create new object
                else
                {
                    $createNewUser = 1;
                    $userObject = CjwNewsletterUser::createUpdateNewsletterUser( $email,
                                                             $salutation,
                                                             $firstName,
                                                             $lastName,
                                                             $eZUserId,
                                                             CjwNewsletterUser::STATUS_CONFIRMED );
                    $userObject->setAttribute( 'import_id', $importId );
                    // set new remote_id
                    $userObject->setAttribute( 'remote_id', 'cjwnl:csvimport:'. CjwNewsletterUtils::generateUniqueMd5Hash( $userObject->attribute( 'id' ) ) );
                    $userObject->store();
                    $newUserStatus = $userObject->attribute('status');
                }
                $newsletterUserId = $userObject->attribute( 'id' );
                $outputFormatArray = $selectedOutputFormatArray;

                // only user which are not blacklisted can get a new subscription
                if ( $newsletterUserId != null &&
                     $userIsBlacklistedOrRemoved === false )
                {
                    $existingSubscription = CjwNewsletterSubscription::fetchByListIdAndNewsletterUserId( $listContentObjectId, $newsletterUserId );
                    // if subscription exists do nothing
                    if ( is_object( $existingSubscription )  )
                    {
                        $existingSubscriptionStatus = $existingSubscription->attribute('status');

                        // if user has removed a subscription by himself
                        // don't activate it again
                        if ( $existingSubscription->isRemovedSelf() ||
                             $existingSubscription->isBlacklisted() )
                        {
                            // no
                            $createNewSubscription = 0;
                        }
                        else
                        {
                            // 2 - update
                            $createNewSubscription = 2;
                            $subscriptionObject = $existingSubscription;

                            $subscriptionObject->setAttribute( 'status', CjwNewsletterSubscription::STATUS_APPROVED );
                            $subscriptionObject->setAttribute( 'import_id', $importId );
                            // set new remote_id
                            $subscriptionObject->setAttribute( 'remote_id', 'cjwnl:csvimport:'. CjwNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId . $importId ) );
                            $subscriptionObject->store();
                        }
                    }
                    // create new subscription
                    else
                    {
                        $createNewSubscription = 1;
                        $newListSubscription =  CjwNewsletterSubscription::create(
                                                 $listContentObjectId,
                                                 $newsletterUserId,
                                                 $outputFormatArray,
                                                 CjwNewsletterSubscription::STATUS_APPROVED );
                        $newListSubscription->setAttribute( 'import_id', $importId );
                        // set new remote_id
                        $newListSubscription->setAttribute( 'remote_id', 'cjwnl:csvimport:'. CjwNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId . $importId ) );
                        $newListSubscription->store();
                        $subscriptionObject = $newListSubscription;
                        $newSubscriptionStatus = $subscriptionObject->attribute( 'status' );
                    }
               }
            }
            $listSubscriptionArray[ $rowId ] = array( 'subscription_object'  => $subscriptionObject,
                                                      'email_ok'             => $emailOk,
                                                      'user_created'         => $createNewUser,
                                                      'newsletter_user_id'   => $newsletterUserId,
                                                      'subscription_created' => $createNewSubscription,
                                                      'user_status_old'      => $existingUserStatus,
                                                      'user_status_new'      => $newUserStatus,
                                                      'subscription_status_old' => $existingSubscriptionStatus,
                                                      'subscription_status_new'  => $newSubscriptionStatus
                                                      //'user_object' => $userObject
            );

        }
        // imported timestamp + set count for imported users + subscriptions
        $importObject->setImported();

        CjwNewsletterLog::writeNotice(
                                            'subscription_list_csvimport',
                                            'import',
                                            'end',
                                             array( 'import_id' => $importObject->attribute( 'id' ),
                                                    'current_user' => eZUser::currentUserID() ) );


        // store result to File
        storeImportResultToFile( $importId, $listSubscriptionArray );
    }
//}

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'list_node', $listNode );
$tpl->setVariable( 'import_id', $importId );
$tpl->setVariable( 'import_object', $importObject );
$tpl->setVariable( 'selected_output_format_array', $selectedOutputFormatArray );
$tpl->setVariable( 'csv_data_array', $csvDataArray );
$tpl->setVariable( 'list_subscription_array', $listSubscriptionArray );
$tpl->setVariable( 'csv_delimiter', $csvDelimiter );
$tpl->setVariable( 'csv_file_path', $csvFilePath );
$tpl->setVariable( 'first_row_is_label', $firstRowIsLabel );
$tpl->setVariable( 'note', $note );
if ( $http->hasPostVariable( 'RowNum' ) )
{
    $tpl->setVariable( 'RowNum', $http->postVariable( 'RowNum' ) );
}
if ( isset( $warning ) )
{
    $tpl->setVariable( 'warning', $warning );
}
$Result = array();
$Result['content'] = $tpl->fetch( 'design:newsletter/subscription_list_csvimport.tpl' );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),

                          array( 'url'  => $systemNode->attribute( 'url_alias' ),
                                 'text' => $systemNode->attribute( 'name' ) ),

                          array( 'url'  => $listNode->attribute( 'url_alias' ),
                                 'text' => $listNode->attribute( 'name' ) ),

                          array( 'url'  => 'newsletter/subscription_list/' . $nodeId,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/subscription_list', 'Subscriptions' ) ),

                          array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/subscription_list_csvimport', 'CSV import' ) ) );




function storeImportResultToFile( $importId, $data )
{
    $fileName = getImportResultFilePath( $importId );

    $dir = dirname( $fileName );
    $file = basename( $fileName );

    // return content string of mail item
    $messageData = serialize( $data );

    // create file in path with content
    $createResult = eZFile::create( $file, $dir, $messageData );
}

function getImportResultFromFile( $importId )
{

    $fileName = getImportResultFilePath( $importId );
    $data = file_get_contents( $fileName );
    if ( $data )
    {
        return unserialize( $data );
    }
    else
    {
        return false;
    }
}

function getImportResultFilePath( $importId )
{
    $fileSep = eZSys::fileSeparator();
    $dir = eZSys::varDirectory() . $fileSep . 'cjw_newsletter' . $fileSep . 'csvimport';
    $file = $importId.'-import_result.serialize';

    return $dir. $fileSep . $file;
}



?>
