<?php
/**
 * File containing the CjwNewsletterListVirtualType class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @author Felix Woldt
 * @subpackage datatypes
 * @filesource
 */
/**
 * Handles cjw_newsletter_list_virtual
 *
 * a list which can combine multiple static list witho option to filter subsriptions
 *
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage datatypes
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterListVirtualType extends eZDataType
{

    const DATA_TYPE_STRING = 'cjwnewsletterlistvirtual';

    /**
     * Constructor
     *
     * @return void
     */
    function __construct()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'cjw_newsletter/datatypes', 'CJW Newsletter List Virtual', 'Datatype name' ),
        array( 'serialize_supported' => true, 'translation_allowed' => false ) );
    }

    /**
     * Validates all variables given on content class level
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#validateClassAttributeHTTPInput($http, $base, $classAttribute)
     * @return EZ_INPUT_VALIDATOR_STATE
     */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#fixupClassAttributeHTTPInput($http, $base, $classAttribute)
     */
    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {

    }

    /**
     * Fetches all variables inputed on content class level
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#fetchClassAttributeHTTPInput($http, $base, $classAttribute)
     * @return boolean
     */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return true;
    }

    /**
     * Validates input on content object level
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#validateObjectAttributeHTTPInput($http, $base, $objectAttribute)
     * @return EZ_INPUT_VALIDATOR_STATE
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentclassAttribute = $contentObjectAttribute->attribute('contentclass_attribute');
        $inputValidationCheck = true;

        $validationErrorMesssageArray = array();

        $prefix = $base . '_CjwNewsletterList_';
        $postfix =  '_'. $contentObjectAttribute->attribute( 'id' );

        // ContentObjectAttribute_CjwNewsletterList_MainSiteaccess_123
        $postListData = array();
        // set placeholders to detect if we should get the values from statick list
        $postListData['main_siteaccess'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        //$postListData['siteaccess_array'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['output_format_array'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['email_sender'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['email_reply_to'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['email_return_path'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['email_sender_name'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['email_receiver_test'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['auto_approve_registered_user'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_NUMERIC;
        $postListData['skin_name'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        $postListData['personalize_content'] = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_NUMERIC;


        // try to fetch post

       /* $postListData['main_siteaccess'] = $http->postVariable(  $prefix . 'MainSiteaccess' . $postfix );
        $postListData['siteaccess_array'] = $http->hasPostVariable(  $prefix . 'SiteaccessArray' . $postfix ) ? $http->postVariable(  $prefix . 'SiteaccessArray' . $postfix ) : array();
        $postListData['output_format_array'] = $http->hasPostVariable(  $prefix . 'OutputFormatArray' . $postfix ) ? $http->postVariable(  $prefix . 'OutputFormatArray' . $postfix ) : array();
        $postListData['email_sender'] = $http->postVariable(  $prefix . 'EmailSender' . $postfix );
        $postListData['email_reply_to'] = $http->postVariable(  $prefix . 'EmailReplyTo' . $postfix );
        $postListData['email_return_path'] = $http->postVariable(  $prefix . 'EmailReturnPath' . $postfix );
        $postListData['email_sender_name'] = $http->postVariable(  $prefix . 'EmailSenderName' . $postfix );
        $postListData['email_receiver_test'] = $http->postVariable(  $prefix . 'EmailReceiverTest' . $postfix );
        $postListData['auto_approve_registered_user'] = $http->postVariable(  $prefix . 'AutoApproveRegisterdUser' . $postfix );
        */

        $postListData['skin_name'] = $http->hasPostVariable(  $prefix . 'SkinName' . $postfix ) ? $http->postVariable(  $prefix . 'SkinName' . $postfix ) : '';
        //$postListData['personalize_content'] = (int) $http->postVariable(  $prefix . 'PersonalizeContent' . $postfix );

        //$requireFieldArray = array( 'main_siteaccess', 'siteaccess_array', 'output_format_array', 'email_sender' );

        $requireFieldArray = array();

        foreach ( $postListData as $varName => $varValue )
        {
            if ( $varValue == CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING
                || $varValue == CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_NUMERIC )
            {
                continue;
            }
            else
            {

                switch ( $varName )
                {
                    case 'main_siteaccess':
                        if ( $postListData['main_siteaccess'] == '' )
                        {
                            $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "Main Siteaccess must be set", null , array(  ) );
                        }
                        else
                        {
                            // array_push( $postListData['siteaccess_array'], $postListData['main_siteaccess'] );
                            // $postListData['siteaccess_array'] = array_unique( $postListData['siteaccess_array'] );
                        }
                        break;

                    /*
                    case 'siteaccess_array':
                        if ( count( $postListData['siteaccess_array'] ) == 0 )
                        {
                            $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "You have to choose a siteaccess for the list", null , array(  ) );
                        }
                        break;
                    */

                    case 'output_format_array':
                        if ( count( $postListData['output_format_array'] ) == 0 )
                        {
                            $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "You have to choose an output format", null , array(  ) );
                        }
                        break;

                    case 'email_sender':
                        if ( $postListData['email_sender'] == '' or !eZMail::validate( $postListData['email_sender'] )  )
                        {
                            $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "You have to set a valid email adress", null , array(  ) );
                        }
                        break;
                    case 'email_receiver_test':
                        if ( $postListData['email_receiver_test'] == ''  )
                        {
                            $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "You have to set a valid semder email", null , array(  ) );
                           }
                           else
                           {
                            $explodeRecieverArr = explode( ';', $postListData['email_receiver_test'] );
                            foreach ( $explodeRecieverArr as $index => $reciever )
                            {
                                // check if email
                                if ( eZMail::validate( $reciever ) == false )
                                {
                                    $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletterlist', "You have to set a valid semder email adress >> $reciever", null , array(  ) );
                                }
                            }
                           }
                        break;

                    default:
                        break;
                }

            }
        }

        if (  $postListData['output_format_array'] == CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING )
            $outputFormatArrayString = CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING;
        else
            $outputFormatArrayString =  CjwNewsletterList::arrayToString( $postListData['output_format_array'] );


        $listObject = new CjwNewsletterListVirtual(
                                array(
                                'is_virtual' => 1,
                                 //'virtual_filter' => $contentObjectAttribute->attribute( 'virtual_filter' ),
                                'contentobject_attribute_id' => $contentObjectAttribute->attribute( 'id' ),
                                'contentobject_attribute_version' => $contentObjectAttribute->attribute( 'version' ),
                                'contentobject_id' => $contentObjectAttribute->attribute( 'contentobject_id' ),
                                'contentclass_id' =>  $contentclassAttribute->attribute('contentclass_id'),
                                'main_siteaccess' => $postListData['main_siteaccess'],

                                 //'siteaccess_array_string' => CjwNewsletterList::arrayToString( $postListData['siteaccess_array'] ),
                                 // not used - user can only subscribe to static lists
                                'siteaccess_array_string' => CjwNewsletterListVirtual::PARENT_LIST_VALUE_PLACEHOLDER_STRING,

                                'email_sender_name' => $postListData['email_sender_name'],
                                'email_sender' => $postListData['email_sender'],
                                'email_reply_to' => $postListData['email_reply_to'],
                                'email_return_path' => $postListData['email_return_path'],
                                'email_receiver_test' => $postListData['email_receiver_test'],
                                'output_format_array_string' => $outputFormatArrayString,
                                'auto_approve_registered_user' => $postListData['auto_approve_registered_user'],
                                'skin_name' => $postListData['skin_name'],
                                'personalize_content' => $postListData['personalize_content']
                                )
                            );

        $listObject->decodePostVariable( 'F' );

        $contentObjectAttribute->Content = $listObject;

        // $listObject->store();
        // $listObject->sync();

        if ( count( $validationErrorMesssageArray ) > 0 )
        {
            $inputValidationCheck = false;
        }

        if ( $inputValidationCheck == true )
        {
            // 3.x/ return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            return eZInputValidator::STATE_ACCEPTED;
        }
        else
        {
            $validationErrorMessage = implode( '<br \>', $validationErrorMesssageArray );
            $error =  $contentObjectAttribute->setValidationError( $validationErrorMessage );
            // 3.x/ return EZ_INPUT_VALIDATOR_STATE_INVALID;
            return eZInputValidator::STATE_INVALID;
        }

    }

    /**
     * Fetches all variables from the object
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#fetchObjectAttributeHTTPInput($http, $base, $objectAttribute)
     * @return boolean
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return true;
    }

    /**
     * Sets the default value
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#initializeObjectAttribute($objectAttribute, $currentVersion, $originalContentObjectAttribute)
     */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $data = $originalContentObjectAttribute->attribute( 'content' );

            if ( $data instanceof CjwNewsletterListVirtual )
            {
                $data->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
                $data->setAttribute( 'contentobject_attribute_version', $contentObjectAttribute->attribute( 'version' ) );
                $data->setAttribute( 'contentobject_id', $contentObjectAttribute->attribute( 'contentobject_id' ) );
                $contentObjectAttribute->setContent( $data );
                $contentObjectAttribute->store();
            }
        }
    }

    /**
     * Returns the content
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#objectAttributeContent($objectAttribute)
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );

        $dataObject = CjwNewsletterListVirtual::fetch( $id, $version );
        if ( !is_object( $dataObject ) )
        {
            $dataObject = new CjwNewsletterListVirtual();
            $dataObject->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
            $dataObject->setAttribute( 'contentobject_attribute_version', $contentObjectAttribute->attribute( 'version' ) );
            $dataObject->setAttribute( 'contentobject_id', $contentObjectAttribute->attribute( 'contentobject_id' ) );
        }
        return $dataObject;
    }

    /**
     * Returns the content data for the given content class attribute
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#classAttributeContent($classAttribute)
     */
    function classAttributeContent( $classAttribute )
    {
        $attrValue = array( 'available_output_format_array' => CjwNewsletterListVirtual::getAvailableOutputFormatArray() );
        return $attrValue;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#hasObjectAttributeContent($contentObjectAttribute)
     * @return boolean
     */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        if ( self::objectAttributeContent( $contentObjectAttribute ) )
            return true;
        else
            return false;
    }

    /**
     * Returns the meta data used for storing search indeces
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#metaData($contentObjectAttribute)
     */
    function metaData( $contentObjectAttribute )
    {
        // $geoData = $contentObjectAttribute->Content;
        // return $geoData->attribute('to_string');
        return '';
    }

    /**
     * Returns the value as it will be shown if this attribute is used in the object name pattern
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#title($objectAttribute, $name)
     */
    function title( $contentObjectAttribute, $name = null )
    {
        $content = $contentObjectAttribute->attribute('content');
        $mainSiteAccess = $content->attribute('main_siteaccess');

        // enclose mainsiteaccess with '[]'
        $newSiteAccessArray = $content->attribute('siteaccess_array');
        foreach ( $newSiteAccessArray as $index => $siteAccessName )
        {
            if ( $siteAccessName == $mainSiteAccess )
                $newSiteAccessArray[ $index ] = '[' .$siteAccessName.']';
        }

        $listTitle =  $contentObjectAttribute->attribute('contentobject_id')
                    . '; '. implode( ', ', $content->attribute('output_format_array') )
                   // . '; A'. $content->attribute('auto_approve_registered_user' )
                    . '; P'. $content->attribute('personalize_content' )
                    . '; '. implode( ', ', $newSiteAccessArray );
        return $listTitle;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#isIndexable()
     * @return boolean
     */
    function isIndexable()
    {
        return false;
    }

    /**
     * Store the content. Since the content has been stored in function
     * fetchObjectAttributeHTTPInput(), this function is with empty code
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#storeObjectAttribute($objectAttribute)
     */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $object = $contentObjectAttribute->Content;
        if ( is_object( $object ) )
        {
            $object->setAttribute( 'virtual_filter', $object->generateFilterXML() );
            $object->store();
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#deleteStoredObjectAttribute($objectAttribute, $version)
     */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $object = CjwNewsletterListVirtual::fetch( $contentObjectAttribute->attribute( "id" ), $contentObjectAttribute->attribute( "version" ) );
        if ( is_object( $object ) )
            $object->remove();
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#serializeContentObjectAttribute($package, $objectAttribute)
     */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $node = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:attribute' );
        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:id', $objectAttribute->attribute( 'id' ) );
        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:identifier', $objectAttribute->contentClassAttributeIdentifier() );
        $node->setAttribute( 'name', $objectAttribute->contentClassAttributeName() );
        $node->setAttribute( 'type', $this->isA() );

        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $value = $objectAttribute->attribute( $attributeName );
                    unset( $attributeNode );
                    $attributeNode = $dom->createElement( $xmlName, (string)$value );
                    $node->appendChild( $attributeNode );
                }
                else
                {
                    eZDebug::writeError( "The attribute '$attributeName' does not exists for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                         'eZDataType::serializeContentObjectAttribute' );
                }
            }
        }
        else
        {
            $cjwNewsletterContent = $objectAttribute->attribute('content');
            $cjwNewsletterContentSerialized = serialize( $cjwNewsletterContent );
            $dataTextNode = $dom->createElement( 'cjwnewsletterlist' );
            $serializedNode = $dom->createCDATASection( $cjwNewsletterContentSerialized );
            $dataTextNode->appendChild( $serializedNode );
            $node->appendChild( $dataTextNode );
        }
        return $node;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#unserializeContentObjectAttribute($package, $objectAttribute, $attributeNode)
     */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $contentclassAttribute = $objectAttribute->attribute('contentclass_attribute');

        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $elements = $attributeNode->getElementsByTagName( $xmlName );
                    if ( $elements->length !== 0 )
                    {
                        $value = $elements->item( 0 )->textContent;
                        $objectAttribute->setAttribute( $attributeName, $value );
                    }
                    else
                    {
                        eZDebug::writeError( "The xml element '$xmlName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                             'eZDataType::unserializeContentObjectAttribute' );
                    }
                }
                else
                {
                    eZDebug::writeError( "The attribute '$attributeName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                         'eZDataType::unserializeContentObjectAttribute' );
                }
            }
        }
        else
        {
            $cjwNewsletterListObjectSerialized = $attributeNode->getElementsByTagName( 'cjwnewsletterlist' )->item(0)->textContent;
            $cjwNewsletterListObject = unserialize( $cjwNewsletterListObjectSerialized );

            if ( is_object( $cjwNewsletterListObject ) )
            {
                 $cjwNewsletterListObject->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
                 $cjwNewsletterListObject->setAttribute( 'contentobject_attribute_version', $objectAttribute->attribute( 'version' ) );
                 $cjwNewsletterListObject->setAttribute( 'contentobject_id', $objectAttribute->attribute( 'contentobject_id' ) );
                 $cjwNewsletterListObject->setAttribute( 'contentclass_id',  $contentclassAttribute->attribute('contentclass_id') );
                 $cjwNewsletterListObject->store();
                 $objectAttribute->setAttribute( 'content', $cjwNewsletterListObject );
            }
            else
            {
                 $objectAttribute->setAttribute( 'content', null );
            }
        }
    }

    /**
     * Return string representation of an contentobjectattribute data for simplified export
     *
     * @see kernel/classes/eZDataType#toString($objectAttribute)
     */
    function toString( $contentObjectAttribute )
    {
        return serialize( $contentObjectAttribute->attribute('content') );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'content', unserialize( $string ) );
    }


    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {

        switch ( $action )
        {
            case 'new_filter' :
                {



                  //  $virtualListObject->addFilter( 'cjwnl_salutation' );
                  //  $virtualListObject->addFilter( 'cjwnl_email' );

                    $postVarName = 'ContentObjectAttribute' . '_new_filtertype_identifier_' . $contentObjectAttribute->attribute( 'id' );

                    if ( $http->hasPostVariable( $postVarName ) )
                    {
                        $newFilterIdentifier = $http->postVariable( $postVarName );
                        $virtualListObject = $contentObjectAttribute->content();

                        $virtualListObject->addFilter( $newFilterIdentifier );
                        $filterXml = $virtualListObject->generateFilterXML();
                        $virtualListObject->setAttribute( 'virtual_filter', $filterXml );

                        $contentObjectAttribute->setAttribute( 'content', $virtualListObject );
                        $contentObjectAttribute->store();

                    }

                }break;
            case 'remove_selected' :
                {
                    $postVarName = 'ContentObjectAttribute' . '_filter_remove_index_' . $contentObjectAttribute->attribute( 'id' );

                    if ( $http->hasPostVariable( $postVarName ) )
                    {
                        $arrayRemove = $http->postVariable( $postVarName );

                        $virtualListObject = $contentObjectAttribute->content();
                        foreach ( $arrayRemove as $index )
                        {
                            $virtualListObject->removeFilterByIdex( (int) $index );
                        }
                        $filterXml = $virtualListObject->generateFilterXML();
                        $virtualListObject->setAttribute( 'virtual_filter', $filterXml );

                        $contentObjectAttribute->setAttribute( 'content', $virtualListObject );
                        $contentObjectAttribute->store();
                    }
                }break;
            default :
                {
                    eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, __METHOD__ );
                }break;
        }

    }


}

eZDataType::register( CjwNewsletterListVirtualType::DATA_TYPE_STRING, 'CjwNewsletterListVirtualType' );

?>
