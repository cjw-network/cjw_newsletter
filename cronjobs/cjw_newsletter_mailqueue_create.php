<?php

/**
 * Cronjob cjw_newsletter_mailqueue_create.php
 *
 * Create all cjwnl_edition_send_items (all subscribers of the list linked to the edtion)
 * for a newsletter edition which is waiting to send out
 *
 * - search all cjwnl_users with status STATUS_PENDING_EZ_USER_REGISTER ( if subscribing in ez user register process )
 *   and if it is enabled => confirm the relationg nl_user + subscriptions
 *
 * -search all cjwnl_edition_send object with status == STATUS_WAIT_FOR_PROCESS
 * -seach all cjwnl_user which are in the list with status == STATUS_APPROVED
 * -create for every user a cjwnl_edition_send_item with status == STATUS_NOT_SEND
 * -mutex support (no double execute of cronjobs)->integrate in runcronjobs.php
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage cronjobs
 * @filesource
 */

// to fetch instance in Cli mode for separate logdata, cause access rights phpcli + webserver
$logInstance = CjwNewsletterLog::getInstance( true );

$message = "START: cjw_newsletter_mailqueue_create";
$cli->output( $message );

// before we create the edition_send_items we will activate all nl_user with statua 20 - CjwNewlsetterUser::STATUS_PENDING_EZ_USER_REGISTER

$message = "--\n>> START: check nl users with status STATUS_PENDING_EZ_USER_REGISTER";
$cli->output( $message );

$pendingNlUserObjectArray = CjwNewsletterUser::fetchUserListByStatus( CjwNewsletterUser::STATUS_PENDING_EZ_USER_REGISTER, 10000, 0, true );

$message = ">>> NlUser Objects with STATUS_PENDING_EZ_USER_REGISTER found: ". count( $pendingNlUserObjectArray );
$cli->output( $message );

$nlUserCounter = 1;
foreach ( $pendingNlUserObjectArray as $nlUser )
{
    $ezUserObject = $nlUser->attribute( 'ez_user' );
    $nlUserId = $nlUser->attribute( 'id' );
    $nlUserEzUserId = $nlUser->attribute( 'ez_user_id' );

    if ( is_object( $ezUserObject ) )
    {
        $ezUserIsEnabled = $ezUserObject->attribute( 'is_enabled' );
        // if activated then confirm nl user
        if ( $ezUserIsEnabled )
        {
            $message = "+ [$nlUserCounter][NL_USER][$nlUserId] eZUser $nlUserEzUserId is enabled => confirm nl user";
            $cli->output( $message );
            // confirm user and all oben subscriptions
            $nlUser->confirmAll();
        }
        // if not activated do nothing
        else
        {
            $message = "o [$nlUserCounter][NL_USER][$nlUserId] eZUser $nlUserEzUserId is deactive => ignore ";
            $cli->output( $message );
        }
    }
    else
    {
        // if a ez_user_id is not available anymore
        // we set the Nl user Status from STATUS_PENDING_EZ_USER_REGISTER => STATUS_PENDING
        // so it is not processed again
        //$nlUser->setAttribute( 'ez_user_id', 0 );
        $nlUser->setAttribute( 'status', CjwNewsletterUser::STATUS_PENDING );

        $message = "! [$nlUserCounter][NL_USER][$nlUserId] eZUserId $nlUserEzUserId not existing anymore set status=STATUS_PENDING";
        $cli->output( $message );

        $nlUser->store();
    }
    $nlUserCounter++;
}

$message = ">> END: check nl users\n--";
$cli->output( $message );

// START schedule

$message = "--\n>> START: check NlEditionSend objects with status STATUS_WAIT_FOR_SCHEDULE";
$cli->output( $message );

// fetch all scheduled SEND objects
$waitForScheduleObjectList = CjwNewsletterEditionSend::fetchEditionSendListByStatus( array( CjwNewsletterEditionSend::STATUS_WAIT_FOR_SCHEDULE ) );

$message = ">>> NlEditionSend objects with STATUS_WAIT_FOR_SCHEDULE found: ". count($waitForScheduleObjectList);
$cli->output( $message );

foreach ( $waitForScheduleObjectList as $newsletterEdtionSendObject )
{
    $scheduleTimestamp = $newsletterEdtionSendObject->attribute( 'mailqueue_process_scheduled' );

    $escalateStatus = $scheduleTimestamp <= time();
    if ($escalateStatus){
        $message = ">>> schedule time has come ".date('Y-m-d H:i:s', $scheduleTimestamp)." escalate status to STATUS_WAIT_FOR_PROCESS";
        $cli->output( $message );
        $newsletterEdtionSendObject->setAttribute('status', CjwNewsletterEditionSend::STATUS_WAIT_FOR_PROCESS);
        $newsletterEdtionSendObject->store();
    }
}

// END

$message = "--\n>> START: check NlEditionSend objects with status STATUS_WAIT_FOR_PROCESS";
$cli->output( $message );

// 1. search all SEND objects which create for the mail list
$waitForProcessObjectList = CjwNewsletterEditionSend::fetchEditionSendListByStatus( array( CjwNewsletterEditionSend::STATUS_WAIT_FOR_PROCESS ) );

$message = ">>> NlEditionSend objects with STATUS_WAIT_FOR_PROCESS found: ". count( $waitForProcessObjectList );
$cli->output( $message );

// 2. every SEND object true
foreach ( $waitForProcessObjectList as $newsletterEdtionSendObject )
{
    $sendId = $newsletterEdtionSendObject->attribute('id');
    $listContentObjectId = $newsletterEdtionSendObject->attribute('list_contentobject_id');
    $listContentObjectVersion = $newsletterEdtionSendObject->attribute('list_contentobject_version');

    $message = "## Procsessing: cjw_newsletter_mailqueue_create - sendObjectId: ". $sendId;
    $cli->output( $message );

    // 3. search all user which corresponding with list and has CjwNewslettersSubscription::STATUS_APPROVED
    // create a new send_item-entry
    $limit = 0;
    $offset = 0;


    //$subscriptionObjectList = CjwNewsletterSubscription::fetchSubscriptionListByListIdAndStatus( $listContentObjectId, CjwNewsletterSubscription::STATUS_APPROVED, $limit, $offset  );
    $subscriptionObjectList = $newsletterEdtionSendObject->getSubscriptionObjectArray( CjwNewsletterSubscription::STATUS_APPROVED, 0, 0 );

    $message = "++ Find SubscriptionObjects with STATUS_APPROVED: ". count( $subscriptionObjectList );
    $cli->output( $message );

    $counter = 0;
    foreach ( $subscriptionObjectList as $subscriptionObject )
    {
        $subscriptionId = $subscriptionObject->attribute('id');
        $editionContentObjectId = $subscriptionObject->attribute('edition_contentobject_id');
        $newsletterUserId = $subscriptionObject->attribute('newsletter_user_id');
        $subscriptionOutputFormatArray = $subscriptionObject->attribute('output_format_array');

        $counter++;
        // status == STATUS_WAIT_FOR_PROCESS || != ABORT ?
        $newsletterEdtionSendObject->sync();
        if ( $newsletterEdtionSendObject->attribute('status') == CjwNewsletterEditionSend::STATUS_WAIT_FOR_PROCESS )
        {
            // every subscription can have multiple outputformats
            // create for every outputformat one send_item
            foreach ( $subscriptionOutputFormatArray as $outputFormatId => $outputFormatName )
            {
                $newSendItemResult = CjwNewsletterEditionSendItem::create( $sendId,
                                                                     $newsletterUserId,
                                                                     $outputFormatId,
                                                                     $subscriptionId );
                if ( is_object( $newSendItemResult ) )
                {
                    // create edtion_send_item
                    $message = "++ [SEND_ITEM][$counter] create new sendItem id: " . $newSendItemResult->attribute('id');
                    $cli->output( $message );
                }
                else
                {
                    // create edtion_send_item
                    $message = "++ [Error][SEND_ITEM][$counter] sendItem already exist do nothing with it!";
                    $cli->output( $message );
                }
            }
        }
        else
        {
            $message = "++ [ABBORT][$counter] Abborting EditionSendObject has not Status STATUS_WAIT_FOR_PROCESS or : ". $sendId ;
            $cli->output( $message );
        }
    } // end foreach subscriptions

    // if are create all send_item entry's, set status == STATUS_MAILQUEUE_CREATED
    $message = "+ [STATUS_MAILQUEUE_CREATED] $counter sendItems has be processed (create / or do nothing)  SendId: ". $sendId ;
    $cli->output( $message );
    $newsletterEdtionSendObject->setAttribute('status', CjwNewsletterEditionSend::STATUS_MAILQUEUE_CREATED );
    $newsletterEdtionSendObject->store();
}

$message = ">> END: check NlEditionSend objects\n--";
$cli->output( $message );

$message = "END: cjw_newsletter_mailqueue_create";
$cli->output( $message );

?>
