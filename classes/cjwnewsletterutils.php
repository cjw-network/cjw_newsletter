<?php
/**
 * File containing the CjwNewsletterUtils class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * class with some useful functions
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterUtils extends eZPersistentObject
{

    function __construct(){ }

    /**
     * generate a unique hash md5
     *
     * @param string $flexibleVar is used as a part of string for md5
     * @return string md5
     */
    static function generateUniqueMd5Hash( $flexibleVar = '' )
    {
        $stringForHash = $flexibleVar. '-'. microtime( true ). '-' . mt_rand(). '-' . mt_rand();
        return md5( $stringForHash );
    }

    /**
     * Replaces markers in newsletter content
     * @param string $content
     * @param CjwNewsletterEditionSend $newsletterEditionSend
     * @param CjwNewsletterUser $newsletterUser
     * @return string
     */
    static function replaceNewsletterMarkers( $content, CjwNewsletterEditionSend $newsletterEditionSend, CjwNewsletterUser $newsletterUser = null )
    {
        // TODO parse extra variables
        $editionSendHash = $newsletterEditionSend->attribute( 'hash' );
        $searchArray =  array( '#_hash_editionsend_#' );
        $replaceArray =  array( $editionSendHash );

        if( $newsletterUser )
        {
            $subscription = CjwNewsletterSubscription::fetchByListIdAndNewsletterUserId( $newsletterEditionSend->attribute('list_contentobject_id'), $newsletterUser->attribute( 'id' ) );

            $newsletterUnsubscribeHash = $subscription->attribute( 'hash' );
            $newsletterConfigureHash = $newsletterUser->attribute( 'hash' );

            $personalizeContent = (int) $newsletterEditionSend->attribute( 'personalize_content' );

            $searchArray = array_merge( $searchArray,
                                        array(
                                          '#_hash_unsubscribe_#',
                                          '#_hash_configure_#'
                                        ));

            $replaceArray = array_merge( $replaceArray,
                                         array(
                                             $newsletterUnsubscribeHash,
                                             $newsletterConfigureHash
                                         ));

            if( $personalizeContent === 1 )
            {
                $searchArray = array_merge( $searchArray,
                                            array(
                                               '[[name]]',
                                               '[[salutation_name]]',
                                               '[[first_name]]',
                                               '[[last_name]]'
                                            ));
                $replaceArray = array_merge( $replaceArray,
                                             array(
                                                    $newsletterUser->attribute( 'name' ),
                                                    $newsletterUser->attribute( 'salutation_name' ),
                                                    $newsletterUser->attribute( 'first_name' ),
                                                    $newsletterUser->attribute( 'last_name' )
                                                  ));
            }
        }

        return str_replace( $searchArray, $replaceArray, $content );
    }
}

?>