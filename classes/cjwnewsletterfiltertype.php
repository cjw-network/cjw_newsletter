<?php
/**
* File containing the CjwNewsletterFilterType class
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
class CjwNewsletterFilterType
{

    protected $NameSpace = 'cjwnl';

    // The descriptive name of the filtertype, usually used for displaying to the user
    protected $Name;

    // The filtertype string ID, used for uniquely identifying a filter
    protected $Identifier;

    protected $Operation;

    protected $OperationsAvailable;

    protected $Values;

    protected $ValuesAvailable;





    protected $Attributes;


    protected function CjwNewsletterFilterType( $filterTypeIdentifier, $name, $properties = array() )
    {

        $this->Identifier = $filterTypeIdentifier;
        $this->Name = $name;
        $this->Operation = false;
        $this->Values = array();
        $this->OperationsAvailable = array();
        $this->ValuesAvailable = array();


     /*   $this->Attributes = array();
        $this->Attributes['identifier'] =& $this->Identifier;
        $this->Attributes['name'] =& $this->Name;
        $this->Attributes['operation'] =& $this->Operation;
        $this->Attributes['values'] =& $this->Values;

        $this->Attributes['operations_available'] =& $this->OperationsAvailable;
        $this->Attributes['values_available'] =& $this->ValuesAvailable;

        $this->Attributes['properties'] =& $properties;
*/

        //$this->Attributes["is_indexable"] = $this->isIndexable();
        //$this->Attributes["is_information_collector"] = $this->isInformationCollector();

        /*$this->Attributes["information"] = array( 'string'   => $this->FilterTypeString,
                                                  'name'     => $this->Name );*/
       // $this->Attributes["properties"] = array( 'multiple_values_allowed' => $mulitpleValuesAllowed );

    }


    /**
     * @return the template name to use for viewing the filter.
    */
    function viewTemplate()
    {
        return $this->Identifier;
    }

    /**
     * @return the template name to use for editing the filter.
    */
    function editTemplate()
    {
        return $this->Identifier;
    }



    /**
     * @return the attributes for this datatype.
    */
    function attributes()
    {
        $attributeArray = array( 'identifier',
                                'name',
                                'operation',
                                'values',
                                'operations_available',
                                'values_available',
                                'db_query_parts',
                                'namespace'
                                );

        return $attributeArray;
    }

    /**
     * @return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        //return isset( $this->Attributes[$attr] );

        $attributeArray = $this->attributes();

        return in_array( $attr, $attributeArray);
    }

    /**
     * @return the data for the attribute $attr or null if it does not exist.
    */
    function attribute( $attr )
    {

        $attributeArray = $this->attributes();

        if( in_array( $attr, $attributeArray) )
        {
            switch ( $attr )
            {
                case 'namespace':
                    return $this->NameSpace;
                    break;
                case 'identifier':
                    return $this->Identifier;
                    break;
                case 'name':
                    return $this->Name;
                    break;
                case 'operation':
                    return $this->Operation;
                    break;
                case 'operations_available':
                    return $this->OperationsAvailable;
                    break;
                case 'values':
                    return $this->Values;
                    break;
                case 'values_available':
                    return $this->ValuesAvailable;
                    break;
                case 'db_query_parts':
                    return $this->DbFields;
                    break;
                case 'db_tables':
                    return $this->DbTables;
                    break;
                case 'db_conditions':
                    return $this->getDbQueryPartArray();
                    break;
            }
        }



      /*  if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->Attributes[$attr];
        }*/

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        $attributeData = null;
        return $attributeData;
    }


    function setOperation( $operation )
    {
        $this->Operation = $operation;
        return true;
    }

    function setValues( $values )
    {
        $this->Values = $values;
        return true;
    }

    function setValuesAvailable( $keyValueArray )
    {
        $this->ValuesAvailable = $keyValueArray;
        return true;
    }

    function setOperationsAvailable( $keyValueArray )
    {
        $this->OperationsAvailable = $keyValueArray;
        return true;
    }

    function setNameSpace( $string )
    {
        $this->NameSpace = $string;
    }

    /**
     * @return an array with all requirted parts for this filter
     * uses the selected values and operation
     */
    function getDbQueryPartArray()
    {
        return array( 'fields' => false,
                      'tables' => false,
                      'conds' => false );
    }

    /**
    * create db part array
    *
    * map vor exampl eq => =
    */
    function createConditionArray( $field, $operator, $values )
    {
        $condAndArray = array();
        $condOrArray = array();

        $valueSearchString = false;

        switch( $operator )
        {
            case 'eq':
                $sqlOperation = '=';
                break;
            case 'gt':
                $sqlOperation = '>';
                break;
            case 'ge':
            case 'gte':
                $sqlOperation = '>=';
                break;
            case 'lt':
                $sqlOperation = '<';
                break;
            case 'le':
            case 'lte':
                $sqlOperation = '<=';
                break;
            case 'ne':
                $sqlOperation = '<>';
                break;
            case 'like':
            case '*like*':
                $sqlOperation = 'like';
                $valueSearchString = '%__value__%';
                break;
            // beginns with
            case 'like*':
                $sqlOperation = 'like';
                $valueSearchString = '__value__%';
                break;
            // ends with
            case '*like':
                $sqlOperation = 'like';
                $valueSearchString = '%__value__';
                break;
            default:
                // TODO error message
                break;
        }

        if ( is_array( $this->Values ) )
        {
            if ( count( $this->Values ) == 1 )
            {
                $value = $this->Values[0];

                if ( $valueSearchString !== false )
                    $value = str_replace( '__value__', $value, $valueSearchString );

                $condAndArray[] =  array( $field => array( $sqlOperation, $value ) );
            }
            else
            {

                switch ( $sqlOperation )
                {
                    case '=':
                        $condAndArray[] = array( $field => array( 'IN', $this->Values ) );
                        break;

                    case '<>':
                        $condAndArray[] = array( $field => array( 'NOT IN', $this->Values ) );
                        break;

                    default:
                        foreach( $this->Values as $value )
                        {
                            if ( $valueSearchString !== false )
                                $value = str_replace( '__value__', $value, $valueSearchString );

                            $condOrArray[] =   array( $field => array( $sqlOperation, $value ) );
                        }
                        break;
                }


            }
        }

        if( count( $condOrArray ) > 0 )
        $condAndArray[] = array( 'OR' =>  $condOrArray );

        return $condAndArray;
    }



}
?>
