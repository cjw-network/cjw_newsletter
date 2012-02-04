<?php
/**
 * File containing the CjwNewsletterSubscriptionType class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @author Felix Woldt
 * @subpackage datatypes
 * @filesource
 */
/**
 * Class description here
 *
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage datatypes
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterSubscriptionType extends eZDataType
{
    const DATA_TYPE_STRING = 'cjwnewslettersubscription';

    function CjwNewsletterSubscriptionType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'cjw_newsletter/datatypes', 'CJW Newsletter Subscription', 'Datatype name' ),
                           array( 'serialize_supported' => false,
                                  'object_serialize_map' => array( 'data_text' => 'subscription' ) ) );
    }

    /**
     * Sets the default value.
     */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    function isInformationCollector()
    {
        return true;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        // if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        // TODO if email is given
        // if email is already in the system  send an email with configure link similar to passwort forget ...
        // if email is not in the system send registration nl email

        return eZInputValidator::STATE_ACCEPTED;
        /*$contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'The email address is empty.' ) );
        return eZInputValidator::STATE_INVALID;*/
    }

    /**
     * Fetches the http post variables for collected information
     */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        // may be store the email and list selection / nl user id / name ???
        // subscripe to newsletter if checked

        $dataArray = $this->fetchCurrentDataFromCollection( $collection );
        if ( isset( $dataArray[ 'subscription_data_array' ] ) )
        {
            if( isset( $dataArray[ 'subscription_data_array' ][ 'list_array' ] ) )
            {
                // only if a user wants to have a nl we will create/update nl user + subscriptions
                $listArray = $dataArray[ 'subscription_data_array' ][ 'list_array' ];
                if( count( $listArray ) > 0 )
                {
                    $context = 'datatype_collect';
                    $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray( $dataArray[ 'subscription_data_array' ],
                                                                                                     CjwNewsletterUser::STATUS_PENDING_EZ_USER_REGISTER,
                                                                                                     false,
                                                                                                     $context );

                    // store all collected information into db
                    $newsletterSelectionSerialize = serialize( $dataArray );
                    // if newsletter active set collection attribute
                    if( $newsletterSelectionSerialize )
                    {
                        $collectionAttribute->setAttribute( 'data_text', $newsletterSelectionSerialize );
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Store the content.
     */
    function storeObjectAttribute( $attribute )
    {
    }

    function isIndexable()
    {
        return false;
    }

    /**
     * Returns the text.
     */
    function title( $contentObjectAttribute, $name = null )
    {
        return 'subscription';
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    function sortKeyType()
    {
        return 'string';
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return false;
    }

    /**
     * Validates the input and returns true if the input was
     * valid for this datatype.
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();

        // if datatype is used as informationcollector
        if( $classAttribute->attribute( 'is_information_collector' ) )
        {

        }
        // if datatype is used in user class
        else
        {
            $userDataArray = $this->fetchCurrentContentObjectData( $contentObjectAttribute );
            if( is_array( $userDataArray ) == false )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'cjw_newsletter/datatype/cjwnewslettersubcription/validation_error',
                                                                     'Datatype can not be used here - user_account required.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            // new value form POST
            $ezUserId = (int) $userDataArray['subscription_data_array']['ez_user_id'];
            $email = strtolower( trim( $userDataArray['subscription_data_array']['email'] ) );

            $checkResult = CjwNewsletterUser::checkIfUserCanBeUpdated( $email, $ezUserId, $updateNewEmail = false );

            switch ( $checkResult )
            {
                // create new user
                case 40:
                    break;
                // update user
                case 41:
                    break;
                // update user with new mail
                case 42:

                    break;
                case -20:
                    // the email is already used by an other nl_user with a different user_id
                    // we ignore the check because we think that the email is the unique key
                    // between ezuser and nl_user
                    break;
                case -21:
                    // the email is emty
                    break;
                case -1:
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'cjw_newsletter/datatype/cjwnewslettersubcription/validation_error',
                                                                         'No user account found' ) );
                    return eZInputValidator::STATE_INVALID;
                    break;
                default:
                    return eZInputValidator::STATE_INVALID;
            }
        }

        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Fetches the http post var string input and stores it in the data instance.
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return true;
    }

    /**
     * on Publish update newsletter user and all related subscriptions
     * may be create a new newsletter user / subscription if not exists
     * @see kernel/classes/eZDataType#onPublish($contentObjectAttribute, $contentObject, $publishedNodes)
     */
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $dataArray = $this->fetchCurrentContentObjectData( $contentObjectAttribute );
        if ( isset( $dataArray[ 'subscription_data_array' ] ) )
        {
            $context = 'datatype_edit';
            $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray( $dataArray[ 'subscription_data_array' ],
                                                                                             CjwNewsletterUser::STATUS_PENDING_EZ_USER_REGISTER,
                                                                                             false,
                                                                                             $context );
        }
        //eZContentCacheManager::clearObjectViewCache( $contentObject->attribute( 'id' ), true );
    }

    /**
     * Returns the content.
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $this->fetchCurrentContentObjectData( $contentObjectAttribute );
    }

    /**
     * fetch email, first_name, ... from informationcollection to use for
     * subscription
     */
    private function fetchCurrentDataFromCollection( $collection )
    {

        $http = eZHTTPTool::instance();
        // mapping of user data from contentobject for the newsletter user
        // the current object must be a User!!!

        $dataMap = $collection->attribute('data_map');
        $subscriptionDataArr = array(     'ez_user_id'  => 0,
                                          'email'       => '',
                                          'salutation'  => 0,
                                          'first_name'  => '',
                                          'last_name'   => '',
                                          'id_array'    => array(),
                                          'list_array'  => array()
                                          );
        // get email from informationcollection
        if( isset( $dataMap[ 'email' ] ) )
        {
            $subscriptionDataArr['email'] = $dataMap[ 'email' ]->content();
        }
        else
        {
            return false;
        }

        if( isset( $dataMap[ 'salutation' ] ) )
            $subscriptionDataArr['salutation'] = (int) $dataMap[ 'salutation' ]->content();
        if( isset( $dataMap[ 'first_name' ] ) )
            $subscriptionDataArr['first_name'] = $dataMap[ 'first_name' ]->content();
        if( isset( $dataMap[ 'last_name' ] ) )
            $subscriptionDataArr['last_name'] = $dataMap[ 'last_name' ]->content();

        if ( $http->hasPostVariable( 'Subscription_IdArray' ) )
            $subscriptionDataArr['id_array'] = $http->postVariable( 'Subscription_IdArray' );
        if ( $http->hasPostVariable( 'Subscription_ListArray' ) )
            $subscriptionDataArr['list_array'] = $http->postVariable( 'Subscription_ListArray' );

        foreach ( $subscriptionDataArr['id_array'] as $listId )
        {
            if ( $http->hasPostVariable( "Subscription_OutputFormatArray_$listId" ) )
                $subscriptionDataArr['list_output_format_array'][ $listId ] = $http->postVariable( "Subscription_OutputFormatArray_$listId" );
            else
            {
                $defaultOutputFormatId = 0;
                $subscriptionDataArr['list_output_format_array'][ $listId ] = array( $defaultOutputFormatId );
             }
        }

        $existingNewsletterUserObject = false;
        $subscriptionResultArray = false;

        // only create update subscriptions if a list is select
        if( count( $subscriptionDataArr['list_array'] ) > 0 )
        {

            if ( $subscriptionDataArr['email'] != '' )
            {
                $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
            }

            // email exist but subscription for email is done again
            // => email send with configure link
            if ( is_object( $existingNewsletterUserObject) )
            {
                // $existingNewsletterUserObject->sendSubriptionInfoMail();
                $mailSendResult = $existingNewsletterUserObject->sendSubcriptionInformationMail();
            }
            // all is ok -> send confirmation email similar to newsletter/subscribe
            else
            {
                $context = 'datatype_collect';
                // subscribe to all selected lists
                $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray(
                                                                            $subscriptionDataArr,
                                                                            CjwNewsletterUser::STATUS_PENDING,
                                                                            false,
                                                                            $context );

                $confirmationResultArray = array();
                $newNewsletterUser = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
                $mailSendResult = $newNewsletterUser->sendSubcriptionConfirmationMail();

            }
        }

        $returnArray = array(
                              'subscription_data_array' => $subscriptionDataArr,
                              'subscription_data_result_array' => $subscriptionResultArray,
                              'existing_newsletter_user' => $existingNewsletterUserObject,
                              'mail_send_result' => $mailSendResult
                               );

      // var_dump( $returnArray );

        return $returnArray;

    }


    /**
     * fetch current data from contentobject for use in newsletter user object
     * @return array if success , false if user_account is not found in dataMap
     */
    private function fetchCurrentContentObjectData( &$contentObjectAttribute )
    {
        $http = eZHTTPTool::instance();
        // mapping of user data from contentobject for the newsletter user
        // the current object must be a User!!!
        $contentObject = $contentObjectAttribute->attribute( 'object' );

        $contentObjectCurrentVersionId = (int) $contentObjectAttribute->attribute( 'version' );
        $contentObjectIsPublished = $contentObject->attribute( 'is_published' );

        // only for user/register  if version = 1 and published = false
        // don't fetch existing nl user because
        // otherwise you will see his registration
        $isNewObjectDraft = false;

        if( $contentObjectCurrentVersionId == 1 &&
            $contentObjectIsPublished == false )
        {
            $isNewObjectDraft = true;
        }

        $dataMap = $contentObject->attribute('data_map');
        $userAccount = null;

        $subscriptionDataArr = array(     'ez_user_id'  => 0,
                                          'email'       => '',
                                          'salutation'  => 0,
                                          'first_name'  => '',
                                          'last_name'   => '',
                                          'id_array'    => array(),
                                          'list_array'  => array()
                                          );
        if( isset( $dataMap[ 'user_account' ] ) )
        {
            $userAccount = $dataMap[ 'user_account' ];
        }
        else
        {
            return false;
        }

        if( isset( $dataMap[ 'salutation' ] ) )
            $subscriptionDataArr['salutation'] = (int) $dataMap[ 'salutation' ]->content();
        if( isset( $dataMap[ 'first_name' ] ) )
            $subscriptionDataArr['first_name'] = $dataMap[ 'first_name' ]->content();
        if( isset( $dataMap[ 'last_name' ] ) )
            $subscriptionDataArr['last_name'] = $dataMap[ 'last_name' ]->content();

        if( is_object( $userAccount ) )
        {
            $userAccountContent = $userAccount->content();

            // we are fetching the email directly from Post because we need this for checking
            // $subscriptionDataArr['email'] = $userAccountContent->attribute( 'email' );
            // has the value stored in db but not this from Post => onPublish has the Post value
            // only need for email
            // only in edit mode

            $base = 'ContentObjectAttribute';
            if ( $http->hasPostVariable( $base . "_data_user_email_" . $userAccount->attribute( "id" ) ) )
            {
                $subscriptionDataArr['email'] = $http->postVariable( $base . "_data_user_email_" . $userAccount->attribute( "id" ) );
            }
            else
            {
                $subscriptionDataArr['email'] = $userAccountContent->attribute( 'email' );
            }

            $subscriptionDataArr['ez_user_id'] = (int) $userAccountContent->attribute( 'contentobject_id' );

            // is_eabled is modified in user/register after this call
            // so we can't use this here
            //$newsletterUserDataArray['ez_user_is_enabled'] = $userAccount->attribute( 'is_enabled' );
        }

        if ( $http->hasPostVariable( 'Subscription_IdArray' ) )
            $subscriptionDataArr['id_array'] = $http->postVariable( 'Subscription_IdArray' );
        if ( $http->hasPostVariable( 'Subscription_ListArray' ) )
            $subscriptionDataArr['list_array'] = $http->postVariable( 'Subscription_ListArray' );

        foreach ( $subscriptionDataArr['id_array'] as $listId )
        {
            if ( $http->hasPostVariable( "Subscription_OutputFormatArray_$listId" ) )
                $subscriptionDataArr['list_output_format_array'][ $listId ] = $http->postVariable( "Subscription_OutputFormatArray_$listId" );
            else
            {
                $defaultOutputFormatId = 0;
                $subscriptionDataArr['list_output_format_array'][ $listId ] = array( $defaultOutputFormatId );
             }
        }

        if ( $isNewObjectDraft === true )
        {
            $existingNewsletterUser = false;
        }
        // first fethc ez_user_id to update existion nl user
        elseif ( $subscriptionDataArr['ez_user_id'] > 0 )
        {
            $existingNewsletterUser = CjwNewsletterUser::fetchByEzUserId( $subscriptionDataArr['ez_user_id'] );
            if( is_object( $existingNewsletterUser ) === false )
            {
                if ( $subscriptionDataArr['email'] != '' )
                {
                    $existingNewsletterUser = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
                }
            }
        }
        elseif ( $subscriptionDataArr['email'] != '' )
        {
            $existingNewsletterUser = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
        }
        else
        {
            $existingNewsletterUser = false;
        }
        $returnArray = array(
                              'is_new_object_draft' => $isNewObjectDraft,
                              'subscription_data_array' => $subscriptionDataArr,
                              'existing_newsletter_user' => $existingNewsletterUser,
                               );

      // var_dump( $returnArray );

        return $returnArray;
    }
}

eZDataType::register( CjwNewsletterSubscriptionType::DATA_TYPE_STRING, 'CjwNewsletterSubscriptionType' );
?>
