<?php
/**
 * File containing the CjwNewsletterEditionType class
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
 * Class description here
 *
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage datatypes
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterEditionType extends eZDataType
{

    const DATA_TYPE_STRING = 'cjwnewsletteredition';

    /**
     * Constructor
     *
     * @return void
     */
    function CjwNewsletterEditionType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'cjw_newsletter/datatypes', 'CJW Newsletter Edition', 'Datatype name' ),
        array( 'serialize_supported' => true, 'translation_allowed' => false ) );
    }

    /**
     * Validates all variables given on content class level
     *
     *
     * @see kernel/classes/eZDataType#validateClassAttributeHTTPInput($http, $base, $classAttribute)
     * @return EZ_INPUT_VALIDATOR_STATE
     */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     *
     * @see kernel/classes/eZDataType#fixupClassAttributeHTTPInput($http, $base, $classAttribute)
     */
    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {

    }

    /**
     * Fetches all variables inputed on content class level
     *
     *
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
     *
     * @see kernel/classes/eZDataType#validateObjectAttributeHTTPInput($http, $base, $objectAttribute)
     * @return EZ_INPUT_VALIDATOR_STATE
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentclassAttribute = $contentObjectAttribute->attribute('contentclass_attribute');
        $inputValidationCheck = true;
        $validationErrorMesssageArray = array();
        $prefix = $base . '_CjwNewsletterEdition_';
        $postfix =  '_'. $contentObjectAttribute->attribute( 'id' );

        // ContentObjectAttribute_CjwNewsletterEdition_MainSiteaccess_123
        $postListData = array();
        $postListData['status'] = $http->hasPostVariable(  $prefix . 'Status' . $postfix ) ? (int)$http->postVariable(  $prefix . 'Status' . $postfix ) : 0;

        $requireFieldArray = array( 'status' );

        foreach ( $postListData as $varName => $varValue )
        {
            switch ( $varName ) {

                case 'status':
                    /*
                    if ( $postListData['email_receiver_test'] == '' or !eZMail::validate( $postListData['email_receiver_test'] )  )
                    {
                        $validationErrorMesssageArray[] = ezi18n( 'cjw_newsletter/datatype/cjwnewsletteredition', "You have to set a valid email adress", null , array(  ) );
                    }
                    */
                    break;

                default:
                    break;
            }
        }

       $object = new CjwNewsletterEdition(
                            array(
                            'contentobject_attribute_id' => $contentObjectAttribute->attribute( 'id' ),
                            'contentobject_attribute_version' => $contentObjectAttribute->attribute( 'version' ),
                            'contentobject_id' => $contentObjectAttribute->attribute( 'contentobject_id' ),
                            'contentclass_id' =>  $contentclassAttribute->attribute('contentclass_id'),
                            'status' => $postListData['status'],
                            )
                            );


        $contentObjectAttribute->Content = $object;

        // if the current version != DRAFT, than abort, because a version can't has more than one status
        if ( $object->attribute( 'is_process' ) ||  $object->attribute( 'is_abort' ) || $object->attribute( 'is_archive' ) )
        {
            $error = $contentObjectAttribute->setValidationError( ezi18n( 'cjw_newsletter/datatype/cjwnewsletteredition', "The current edition is already in sending process - you have to create a new copy of this object", null , array(  ) ) );
            return eZInputValidator::STATE_INVALID;
        }

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
            $validationErrorMessage = implode( '<br />', $validationErrorMesssageArray );
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
            $data = $originalContentObjectAttribute->attribute( "content" );

            if ( $data instanceof CjwNewsletterEdition )
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

        $dataObject = CjwNewsletterEdition::fetch( $id, $version );
        if ( !is_object( $dataObject ) )
        {
            $dataObject = null;
        }
        return $dataObject;
    }

    /**
     *
     *
     * @see kernel/classes/eZDataType#hasObjectAttributeContent($contentObjectAttribute)
     * @return boolean
     */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        if ( CjwNewsletterEditionType::objectAttributeContent( $contentObjectAttribute ) )
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
        return '';
    }

    /**
     * Returns the value as it will be shown if this attribute is used in the object name pattern
     *
     * @see kernel/classes/eZDataType#title($objectAttribute, $name)
     */
    function title( $contentObjectAttribute, $name = null )
    {
        return "Newsletter edition title";
    }

    /**
     *
     * @see kernel/classes/eZDataType#isIndexable()
     */
    function isIndexable()
    {
        return false;
    }

    /**
     * Store the content. Since the content has been stored in function
     * fetchObjectAttributeHTTPInput(), this function is with empty code
     *
     * @see kernel/classes/eZDataType#storeObjectAttribute($objectAttribute)
     */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $object = $contentObjectAttribute->Content;
        if ( is_object( $object ) )
        {
            $object->store();
            return true;
        }
        return false;
    }

    /**
     * @see kernel/classes/eZDataType#deleteStoredObjectAttribute($objectAttribute, $version)
     */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $object = CjwNewsletterEdition::fetch( $contentObjectAttribute->attribute( "id" ), $contentObjectAttribute->attribute( "version" ) );
        if ( is_object( $object ) )
            $object->remove();
    }

    /**
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
            $dataTextNode = $dom->createElement( 'cjwnewsletteredition' );
            $serializedNode = $dom->createCDATASection( $cjwNewsletterContentSerialized );
            $dataTextNode->appendChild( $serializedNode );
            $node->appendChild( $dataTextNode );
        }
        return $node;
    }

    /**
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
            $cjwNewsletterEditionObjectSerialized = $attributeNode->getElementsByTagName( 'cjwnewsletteredition' )->item(0)->textContent;
            $cjwNewsletterEditionObject = unserialize( $cjwNewsletterEditionObjectSerialized );

            if ( is_object( $cjwNewsletterEditionObject ) )
            {
                 $cjwNewsletterEditionObject->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
                 $cjwNewsletterEditionObject->setAttribute( 'contentobject_attribute_version', $objectAttribute->attribute( 'version' ) );
                 $cjwNewsletterEditionObject->setAttribute( 'contentobject_id', $objectAttribute->attribute( 'contentobject_id' ) );
                 $cjwNewsletterEditionObject->setAttribute( 'contentclass_id',  $contentclassAttribute->attribute('contentclass_id') );
                 $cjwNewsletterEditionObject->store();
                 $objectAttribute->setAttribute( 'content', $cjwNewsletterEditionObject );
            }
            else
            {
                 $objectAttribute->setAttribute( 'content', null );
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#toString($objectAttribute)
     * @return string
     */
    function toString( $contentObjectAttribute )
    {
        return serialize( $contentObjectAttribute->attribute('content') );
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#fromString($objectAttribute, $string)
     */
    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'content', unserialize( $string ) );
    }
}

eZDataType::register( CjwNewsletterEditionType::DATA_TYPE_STRING, 'CjwNewsletterEditionType' );

?>
