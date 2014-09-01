<?php
/**
* File containing the CjwNewsletterFilter class
*
* @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
* @license http://ez.no/licenses/gnu_gpl GNU GPL v2
* @version //autogentag//
* @package cjw_newsletter
* @filesource
*/
/**
 * Base Class to manage Filter
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterFilter
{

    /**
    * Stores the instance of this class
    *
    * @var CjwNewsletterFilter
    */
   // protected static $Instance = null;

    /**
     * Stores all instances of available Filtertyes as key value pair
     * FilterTypeString => Instance of FilterTypeClass
     */
    private $FilterTypesAvailable = array();

    private $FilterTypesActive = array();

    /**
    * Returns the instance of the class
    *
    * @return CjwNewsletterFilter
    */
   /* public static function getInstance()
    {
        if ( is_null( self::$Instance ) )
        {
            self::$Instance = new self();
        }
        return self::$Instance;
    }*/

    /**
     * Constructs an empty CjwNewsletterFilter instance
     * @return void
     */
    public function __construct( )
    {
        $this->loadAndRegisterAllAvailableFilterTypes();
    }

    /**
     *
     * Enter description here ...
     * @return array with instances of alle available CjwNewsletterFilterTypes (key is the FilterTypeString)
     */
    public function getFilterTypesAvailable()
    {
        return $this->FilterTypesAvailable;
    }

    public function getFilterTypesActive()
    {
        return $this->FilterTypesActive;
    }


    /**
    *
    * register all available FiltertTypes which are definied in cjw_newsletter.ini
    * and store them in globals Array
    */
    private function loadAndRegisterAllAvailableFilterTypes()
    {
        $newsletterIni = eZINI::instance( 'cjw_newsletter.ini' );

        $availableFilterTypeClassArray = $newsletterIni->variable( 'NewsletterFilterSettings', 'AvailableFilterTypeClassArray' );
        foreach( $availableFilterTypeClassArray as $filterClassName )
        {
            $this->registerFilter( $filterClassName );
        }
    }

    /**
     *
     * RegisterFilter By ClassName
     *
     * @param unknown_type $filterClassName
     */
    private function registerFilter( $filterClassName )
    {
        if ( class_exists( $filterClassName ) )
        {
            $classParentArray = class_parents( $filterClassName );
            if ( !isset( $classParentArray['CjwNewsletterFilterType'] ) )
            {
                eZDebug::writeError( "Can not register CjwNewsletterFilterType '$filterClassName' - The Class do not extend Class CjwNewsletterFilterType!" , __METHOD__ );
                return false;
            }
            else
            {
                $filterInstance = new $filterClassName();
                $filterTypeString = $filterInstance->attribute( 'identifier' );
                $this->FilterTypesAvailable[$filterTypeString] = $filterInstance;
                // eZDebug::writeDebug( "Register CjwNewsletterFilterType: '$filterTypeString' - '$filterClassName'" , __METHOD__ );
            }
        }
        else
        {
            eZDebug::writeError( "Can not register CjwNewsletterFilterType '$filterClassName' - Class not existes!" , __METHOD__ );
            return false;
        }
    }

    /**
     * add a new filter
     */
    public function addFilter( $filterTypeIdentifier, $operation = false, $values = false )
    {
        if ( count( $this->FilterTypesAvailable > 0 )
             && $filterTypeIdentifier != null
             && $filterTypeIdentifier != false
             && isset( $this->FilterTypesAvailable[ $filterTypeIdentifier ] ) )
        {
            $filter = clone $this->FilterTypesAvailable[ $filterTypeIdentifier ];

            $filter->setOperation( $operation );
            $filter->setValues( $values );

            $this->FilterTypesActive[] = $filter;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * removing a filter from index
    */
    public function removeFilterByIndex( $filterIndex )
    {
        if ( isset( $this->FilterTypesActive[ $filterIndex ] ) )
        {
            unset( $this->FilterTypesActive[ $filterIndex ] );
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
    * set FilterTypes Objects from xml
    */
    function fromPostVariable( $filterPostVar )
    {
        $http = eZHTTPTool::instance();

        if ( $http->hasPostVariable( $filterPostVar ) )
        {
            $this->resetFilterTypesActiveArray();

            $postVarFilterArray = $http->variable( $filterPostVar );

            foreach( $postVarFilterArray as $filter )
            {
                $this->addFilter( (string) $filter['i'], (string) $filter['o'], $filter['v'] );
            }
        }
    }


    /**
     * set FilterTypes Objects from xml
     */
    function fromXML( $xmlString )
    {
        $this->resetFilterTypesActiveArray();

        try
        {
            $loadedXml = new SimpleXMLElement( $xmlString );

            foreach( $loadedXml->filters->filter as $filter )
            {
                $valueArr = array();
                foreach( $filter->values->value as $value )
                {
                    $valueArr[] = (string) $value;
                }
                $this->addFilter( (string) $filter->identifier, (string) $filter->operation, $valueArr );
            }
        }
        catch( Exception $e )
        {
            // TODO error
        }
    }

    /**
    * Filter To xml
    */
    function toXML()
    {
        $listFilterXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><cjw_newsletter></cjw_newsletter>');

        $filterXml = $listFilterXml->addChild( 'filters' );

        foreach( $this->FilterTypesActive as $filter )
        {
            $filterSubnode = $filterXml->addChild( 'filter' );
            $filterSubnode->addChild( 'identifier', $filter->attribute( 'identifier' ) );
            $filterSubnode->addChild( 'operation', $filter->attribute( 'operation' ) );
            $valuesSubnode = $filterSubnode->addChild( 'values' );
            $filterValues = $filter->attribute( 'values' );
            if ( $filterValues )
            {
                foreach( $filterValues as $value )
                {
                    $valuesSubnode->addChild( 'value', $value );
                }
            }

        }

        $xmlString = $listFilterXml->asXML();

        return $xmlString;
    }

    /**
     *
     * reset FilterArray => empty
     */
    function resetFilterTypesActiveArray()
    {
        $this->FilterTypesActive = array();
    }

    /**
     * generete query parts for all active filters
     */
    function getDbQueryPartArray()
    {
        $tmpArray = array( 'fields' => array(),
                           'tables' => array(),
                           'conds' => array() );

        $partArray = array();

        foreach ( $this->FilterTypesActive as $filter )
        {
            $nameSpace = $filter->attribute( 'namespace' );
            if ( !isset( $partArray[ $nameSpace ] ) )
            {
                $partArray[ $nameSpace ] = $tmpArray;
            }
            $partArray[ $nameSpace ] = self::mergeDbQueryArray( $partArray[ $nameSpace ], $filter->getDbQueryPartArray() );
        }

        return $partArray;

    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $arr1
     * @param unknown_type $arr2
     *
     * @return array( 'fields' => array(),
                      'tables' => array(),
                      'conds' => array() );
     */
    static function mergeDbQueryArray( $arr1, $arr2 )
    {
        if ( !is_array( $arr2 ) )
            return $arr1;

        if ( isset( $arr2['fields'] ) && is_array( $arr2['fields'] ) )
        {
            foreach( $arr2['fields'] as $value )
            {
                $arr1['fields'][] = $value;
            }

            $arr1['fields'] = array_unique( $arr1['fields'] );

        }

        if ( isset( $arr2['tables'] ) && is_array( $arr2['tables'] ) )
        {
            foreach( $arr2['tables'] as $value )
            {
                $arr1['tables'][] = $value;
            }

            $arr1['tables'] = array_unique( $arr1['tables'] );

        }

        if ( isset( $arr2['conds'] ) && is_array( $arr2['conds'] ) )
        {
            foreach( $arr2['conds'] as $value )
            {
                $arr1['conds'][] = $value;
            }
        }

        return $arr1;
    }

}


?>
