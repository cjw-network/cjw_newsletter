<?php
/**
 * File containing the CjwNewsletterListVirtual class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Data management datatyp cjwnewsletterlist
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterListVirtual extends CjwNewsletterList
{
    const PARENT_LIST_VALUE_PLACEHOLDER_STRING = '__parent_list_value__';
    // for INT and TINIY INT
    const PARENT_LIST_VALUE_PLACEHOLDER_NUMERIC = -1;

    private $FilterObject = null;

    /**
    * @return void
    */
    static function definition()
    {
        $listDefinition = parent::definition();
        // set Classname for Virtual List
        $listDefinition['class_name'] = 'CjwNewsletterListVirtual';

        $listDefinition['function_attributes']['parent_list_contentobject_id'] = 'getParentListContentObjectId';

        $listDefinition['function_attributes']['filtertypes_active'] = 'getFilterTypesActive';

        $listDefinition['function_attributes']['filtertypes_available'] = 'getFilterTypesAvailable';


        return $listDefinition;
    }

    /**
     * Initializes a new newsletter list virtual class
     *
     * @param array $row
     * @return void
     */
    function CjwNewsletterListVirtual( $row = array() )
    {
        $this->FilterObject = new CjwNewsletterFilter();

        $this->eZPersistentObject( $row );
        $this->setAttribute( 'is_virtual', 1 );

//        $this->FilterObject->fromXml( $this->attribute( 'virtual_filter' ) );

        $this->decodeFilterXML( $this->attribute( 'virtual_filter' ) );
    }


    /**
     * get the objectId of the parent nl list node
     * to fetch subscriptions
     */
    function getParentListContentObjectId()
    {
        return self::getParentListContentObjectIdByListId( $this->attribute( 'contentobject_id' ) );
    }

    /**
    * get the objectId of the parent nl list node
    * to fetch subscriptions
    */
    function getParentListContentObjectIdByListId ( $virtualListContentObjectId )
    {
        $listNode = eZContentObjectTreeNode::fetchByContentObjectID( $virtualListContentObjectId, true );
        if ( isset( $listNode[0] ) && is_object( $listNode[0] ) )
        {
            $parentListNode = $listNode[0]->attribute( 'parent' );
            if ( is_object( $parentListNode ) && $parentListNode->attribute( 'class_identifier' ) == 'cjw_newsletter_list' )
            {
                $parentListContentObjectId = $parentListNode->attribute( 'contentobject_id' );
                return $parentListContentObjectId;
            }
            else
                return 0;

        }
        else
            return 0;

    }

    /*
     * get $attr of parent list object
     */
    function getParentListAttribute( $attr )
    {
        $parentListObject = $this->getParentListObject();
        if ( is_object( $parentListObject ) )
            return $parentListObject->attribute( $attr );
        else
            return false;
    }

    /**
    * @return get the CjwNewsletterList Object of the parent nl list node
    */
    function getParentListObject()
    {
        // in draft modus no node is available only node assignment
        $nodeAssignements = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ),
                                                                $this->attribute( 'contentobject_attribute_version' ),
                                                                1,
                                                                true );

        if ( isset( $nodeAssignements[0] ) && is_object( $nodeAssignements[0] ) )
        {
            $parentListNodeId = $nodeAssignements[0]->attribute( 'parent_node' );
            $parentListNode = eZContentObjectTreeNode::fetch( $parentListNodeId );
        }

        if ( is_object( $parentListNode )
            && $parentListNode->attribute( 'class_identifier' ) == 'cjw_newsletter_list' )
        {
            $parentListAttributeDataMap = $parentListNode->attribute( 'data_map' );

            if ( isset( $parentListAttributeDataMap['newsletter_list'] ) )
            {
                $parentListAttribute = $parentListAttributeDataMap['newsletter_list'];
                if ( !is_object( $parentListAttribute ) )
                    return 0;

                $parentListObject = $parentListAttribute->attribute( 'content' );

                // CjwNewsletterListObject
                if ( !is_object( $parentListObject ) )
                    return 0;
                else
                    return $parentListObject;

            }
            else
                return 0;
        }
        else
            return 0;



    }


     /**
     * Return array of list subscribers groub by status
     *
     * @return array
     */
    function getUserCountStatistic()
    {
        // TODO get virtual user statistic array
        $userCountStatisticArray = CjwNewsletterSubscriptionVirtual::fetchSubscriptionListStatistic( $this );
        return $userCountStatisticArray;
        /*return array(
                                'all'       => 9999,
                                'approved'  => 1111 );*/
    }

    /**
     * Return count of alle subribed users
     *
     * @return unknown_type
     */
    function getUserCount()
    {
        // TODO get virtual user count
        $userCount = CjwNewsletterSubscriptionVirtual::fetchSubscriptionListByListIdCount( $this );
        return $userCount;
        //return 1111;
    }


    /**
    * Used in datatype cjwnewsletter_list
    *
    * @param integer $attributeId
    * @param integer $version
    * @return object
    */
    static function fetch( $attributeId, $version )
    {
        $objectList = eZPersistentObject::fetchObjectList(
            self::definition(),
            null,
            array( 'contentobject_attribute_id' => $attributeId,
                   'contentobject_attribute_version' => $version,
                   'is_virtual' => 1  ),
            null,
            null,
            true
        );

        if ( count( $objectList ) > 0 )
            return $objectList[0];
    }

   /**
     * get all subscriptions of current this virtual list
     *
     * @param integer $status e.g. @param integer $status e.g. CjwNewsletterSubscription::STATUS_APPROVED
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return Ambigous <multitype:, NULL, unknown, multitype:unknown >
     *
     * @see CjwNewsletterList::getSubscriptionObjectArray()
     */
    function getSubscriptionObjectArray( $subscriptionStatus = false, $limit = 0, $offset = 0, $asObject = true )
    {
        $virtualListObject = $this;
        $subscriptionObjectList = CjwNewsletterSubscriptionVirtual::fetchSubscriptionListByListIdAndStatus( $virtualListObject, $subscriptionStatus, $limit, $offset, $asObject  );
        return $subscriptionObjectList;
    }

    /**
     *
     * get count of subscription for this virtual list
     * @param integer $status e.g. CjwNewsletterSubscription::STATUS_APPROVED
     * @return number
     * @see CjwNewsletterList::getSubscriptionObjectCount()
     */
    function getSubscriptionObjectCount( $status )
    {
        $virtualListObject = $this;
        $subscriptionObjectListCount = CjwNewsletterSubscriptionVirtual::fetchSubscriptionListByListIdCount( $virtualListObject, $status );
        return $subscriptionObjectListCount;
    }

    /**
     * @return array of FilterObjects which are available @see ini setting
     */
    function getFilterTypesAvailable()
    {
        // TODO

        return $this->FilterObject->getFilterTypesAvailable();
    }

    /**
    * @return array of FilterObjects which are active at the moment
    */
    function getFilterTypesActive()
    {
        // TODO
    /*    $filterArray = array();

        $filterArray[] = new CjwNewsletterFilterTypeSalutation();
        $filterArray[] = new CjwNewsletterFilterTypeSalutation();
        $filterArray[] = new CjwNewsletterFilterTypeEmail();
*/

        return $this->FilterObject->getFilterTypesActive();

    }

    function addFilter( $filterIdentifier )
    {
        return $this->FilterObject->addFilter( $filterIdentifier );
    }

    function removeFilterByIdex( $filterIndex )
    {
        return $this->FilterObject->removeFilterByIndex( $filterIndex );
    }

    /**
    * set FilterTypes Object from xml
    */
    function decodePostVariable( $filterPostVariable  )
    {

        $this->FilterObject->fromPostVariable( $filterPostVariable );

        /*$this->FilterObject->addFilter( 'cjwnl_email' );
         $this->FilterObject->addFilter( 'cjwnl_salutaion' );
        $this->FilterObject->addFilter( 'cjwnl_email' );*/
    }



    /**
    * set FilterTypes Object from xml
    */
    function decodeFilterXML( $xmlString )
    {
        if ( $xmlString != null )
            $this->FilterObject->fromXml( $xmlString );

        /*$this->FilterObject->addFilter( 'cjwnl_email' );
        $this->FilterObject->addFilter( 'cjwnl_salutaion' );
        $this->FilterObject->addFilter( 'cjwnl_email' );*/
    }

    /**
     * retrun xml for alle filters
     */
    function generateFilterXML()
    {
        return $this->FilterObject->toXml();
    }



 /**
    * get interal filters
    * TODO read from list configuration
    *
    * @param unknown_type $subscriptionStatus
    * @return Ambigous <multitype:number , multitype:string >
    */
    public function getFilterInternalArray( $subscriptionStatus )
    {
        $parentListContentObjectId = $this->getParentListContentObjectId();

        $filterArray['fields']= array(
                                'cjwnl_subscription.*'
        );
        $filterArray['tables']= array(
                                'cjwnl_user',
                                'cjwnl_subscription'
        );
        $filterArray['conds'] = array();
        $filterArray['conds'][] = array(
                'cjwnl_user.id' => array( '=', 'cjwnl_subscription.newsletter_user_id', 'sql' ) );


        // internal field filters  => fields from cjwnl_user and cjwnl_subscription

        //$filterArray['conds'][] = array( 'cjwnl_user.email' => array( 'OR', array( 'like', '%@%' ), array( 'like', '%abc@%' ) ) );
        //$filterArray['conds'][] = array( 'cjwnl_user.email' => array( 'OR', array( 'AND', 'woldt', 'acd'  ), array( 'like', '%abc@%' ) ) );
        //$filterArray['conds'][] = array( 'cjwnl_user.last_name' => array( array( 'woldt', 'acd' ) ) );
        //$filterArray['conds'][] = array( 'cjwnl_user.last_name' => array( 'AND', 'woldt', 'acd'  ) );
        $filterArray['conds'][] = array(
            'cjwnl_subscription.list_contentobject_id' => array( '=', $parentListContentObjectId )
        );
        //$filterArray['cond'] = array( 'cjwnl_subscription.status' => CjwNewsletterSubscription::STATUS_APPROVED );
    /*    $filterArray['conds'][] = array(
        'cjwnl_user.email' => array(
        'like', '%@%gmx.de'
                )
        );
*/

    // frontend status filter
        if ( $subscriptionStatus !== false || $subscriptionStatus != null )
        {
            $filterArray['conds'][] = array(
            'cjwnl_subscription.status' =>  array( '=', (int) $subscriptionStatus )
            );
        }

        // cjwnnl_user filter
        /*$filterArray['conds'][] = array(
            'cjwnl_user.salutation' => '2'
        );*/

        $listFilterArray = $this->FilterObject->getDbQueryPartArray();

        if ( is_array( $listFilterArray ) && isset( $listFilterArray['cjwnl'] ) )
            $filterArray = CjwNewsletterFilter::mergeDbQueryArray( $filterArray, $listFilterArray['cjwnl'] );


    /*    echo '<pre>';
        echo print_r( $filterArray, true );
        echo '</pre>';*/

        return $filterArray;
    }

    /**
     * get external filter
    * TODO read from listconfiguration
     * @return multitype:multitype:string
    */
    public function getFilterExternalArray( $subscriptionStatus )
    {
        $externalFilterArray[0]['fields'] = array( 'clubuser.id AS external_user_id' );
        $externalFilterArray[0]['tables'] = array( 'clubuser_optin', 'clubuser_optin_relation', 'clubuser' );
        $externalFilterArray[0]['conds'] = array();

            $externalFilterArray[0]['conds'][] = array(
                            'clubuser_optin_relation.optinid' => array( '=', 'clubuser_optin.id', 'sql' )
            );
        $externalFilterArray[0]['conds'][] = array(
        'cjwnl_user.external_user_id' => array( '=', 'clubuser.id', 'sql' )
        );
        // 30-40
            $externalFilterArray[0]['conds'][] = array(
                             'clubuser.geburtsdatum' => array( '<=', 'DATE_SUB(curdate(), INTERVAL 30 YEAR)', 'sql' )
            );
        $externalFilterArray[0]['conds'][] = array(
                              'clubuser.geburtsdatum' => array( '>=', 'DATE_SUB(curdate(), INTERVAL 40 YEAR)', 'sql' )
            );

            //-- optin sport (35)
        //-- AND clubuser_optin.name = 'sport'
        $externalFilterArray[0]['conds'][] = array(
        'clubuser_optin.id' => '35'
            );

            // -- plz 9x
            $externalFilterArray[0]['conds'][] = array(
        'clubuser.plz' => array( 'like', '9%' )
        );

        $externalFilterArray = array();
        $listFilterArray = $this->FilterObject->getDbQueryPartArray();

        foreach( $listFilterArray as $filterNameSpace => $filterQueryPartArr )
        {
            if( $filterNameSpace != 'cjwnl' )
            {
                $externalFilterArray[] = $filterQueryPartArr;
            }
        }

        return $externalFilterArray;
    }


    /**
    * if an attribute has a placeholdervalue fetch value from parent list
    * @see eZDataType::attribute()
    */
    function attribute( $attr, $noFunction = false  )
    {
        $vListValue = parent::attribute( $attr );

        switch( $vListValue )
        {
            case self::PARENT_LIST_VALUE_PLACEHOLDER_NUMERIC:
            case self::PARENT_LIST_VALUE_PLACEHOLDER_STRING:
                return $this->getParentListAttribute( $attr );
            default:
                return $vListValue;
        }
    }

    /**
     * if an attribute has a placeholdervalue fetch value from parent list
     * @see attribute() method
     * but if it is called from storeObject use the placeholder values
     */
    function attributeContentToStore( $attr, $noFunction = false  )
    {
        return parent::attribute( $attr );
    }

    /**
     * use modified store function for virtual lists
     * @see eZPersistentObject::store()
     */
    function store( $fieldFilters = null )
    {
        self::storeObject( $this, $fieldFilters );
    }

    /**
     *  Stores the data in \a $obj to database.
     *
     *  a little modification - stores tehe placeholdervalues for the virtual list so we now if we should get
     *  a value from parent list
     *
     *  fieldFilters If specified only certain fields will be stored.
     * \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param unknown_type $obj
     * @param unknown_type $fieldFilters
     * @see eZPersistentObject::storeObject
     */
    static function storeObject( $obj, $fieldFilters = null )
    {
        $db = eZDB::instance();
        $useFieldFilters = ( isset( $fieldFilters ) && is_array( $fieldFilters ) && $fieldFilters );

        $def = $obj->definition();
        $fields = $def["fields"];
        $keys = $def["keys"];
        $table = $def["name"];
        $relations = isset( $def["relations"] ) ? $def["relations"] : null;
        $insert_object = false;
        $exclude_fields = array();
        foreach ( $keys as $key )
        {
            // $value = $obj->attribute( $key );
            $value = $obj->attributeContentToStore( $key );
            if ( $value === null )
            {
                $insert_object = true;
                $exclude_fields[] = $key;
            }
        }

        if ( $useFieldFilters )
        $insert_object = false;

        $use_fields = array_diff( array_keys( $fields ), $exclude_fields );
        // If we filter out some of the fields we need to intersect it with $use_fields
        if ( is_array( $fieldFilters ) )
        $use_fields = array_intersect( $use_fields, $fieldFilters );
        $doNotEscapeFields = array();
        $changedValueFields = array();
        $numericDataTypes = array( 'integer', 'float', 'double' );

        foreach ( $use_fields as $field_name  )
        {
            $field_def = $fields[$field_name];
            //$value = $obj->attribute( $field_name );
            $value = $obj->attributeContentToStore( $field_name );

            if ( $value === null )
            {
                if ( ! is_array( $field_def ) )
                {
                    $exclude_fields[] = $field_name;
                }
                else
                {
                    if ( array_key_exists( 'default', $field_def ) &&
                    ( $field_def['default'] !== null ||
                    ( $field_name == 'data_int' &&
                    array_key_exists( 'required', $field_def ) &&
                    $field_def[ 'required' ] == false ) ) )
                    {
                        $obj->setAttribute( $field_name, $field_def[ 'default' ] );
                    }
                    else
                    {
                        //if ( in_array( $field_def['datatype'], $numericDataTypes )
                        $exclude_fields[] = $field_name;
                    }
                }
            }

            if ( strlen( $value ) == 0 &&
            is_array( $field_def ) &&
            in_array( $field_def['datatype'], $numericDataTypes  ) &&
            array_key_exists( 'default', $field_def ) &&
            ( $field_def[ 'default' ] === null || is_numeric( $field_def[ 'default' ] ) ) )
            {
                $obj->setAttribute( $field_name, $field_def[ 'default' ] );
            }

            if ( $value !== null                                &&
            $field_def['datatype'] === 'string'            &&
            array_key_exists( 'max_length', $field_def )   &&
            $field_def['max_length'] > 0                   &&
            strlen( $value ) > $field_def['max_length'] )
            {
                $obj->setAttribute( $field_name, substr( $value, 0, $field_def['max_length'] ) );
                eZDebug::writeDebug( $value, "truncation of $field_name to max_length=". $field_def['max_length'] );
            }
            $bindDataTypes = array( 'text' );
            if ( $db->bindingType() != eZDBInterface::BINDING_NO &&
            strlen( $value ) > 2000 &&
            is_array( $field_def ) &&
            in_array( $field_def['datatype'], $bindDataTypes  )
            )
            {
                $boundValue = $db->bindVariable( $value, $field_def );
                //                $obj->setAttribute( $field_name, $value );
                $doNotEscapeFields[] = $field_name;
                $changedValueFields[$field_name] = $boundValue;
            }

        }
        $key_conds = array();
        foreach ( $keys as $key )
        {
            //$value = $obj->attribute( $key );
            $value = $obj->attributeContentToStore( $key );
            $key_conds[$key] = $value;
        }
        unset( $value );

        $important_keys = $keys;
        if ( is_array( $relations ) )
        {
            //            $important_keys = array();
            foreach( $relations as $relation => $relation_data )
            {
                if ( !in_array( $relation, $keys ) )
                $important_keys[] = $relation;
            }
        }
        if ( count( $important_keys ) == 0 && !$useFieldFilters )
        {
            $insert_object = true;
        }
        else if ( !$insert_object )
        {
            $rows = eZPersistentObject::fetchObjectList( $def, $keys, $key_conds,
            array(), null, false,
            null, null );
            if ( count( $rows ) == 0 )
            {
                /* If we only want to update some fields in a record
                 * and that records does not exist, then we should do nothing, only return.
                */
                if ( $useFieldFilters )
                return;

                $insert_object = true;
            }
        }

        if ( $insert_object )
        {
            // Note: When inserting we cannot hone the $fieldFilters parameters

            $use_fields = array_diff( array_keys( $fields ), $exclude_fields );
            $use_field_names = $use_fields;
            if ( $db->useShortNames() )
            {
                $use_short_field_names = $use_field_names;
                eZPersistentObject::replaceFieldsWithShortNames( $db, $fields, $use_short_field_names );
                $field_text = implode( ', ', $use_short_field_names );
                unset( $use_short_field_names );
            }
            else
            $field_text = implode( ', ', $use_field_names );

            $use_values_hash = array();
            $escapeFields = array_diff( $use_fields, $doNotEscapeFields );

            foreach ( $escapeFields as $key )
            {
                //$value = $obj->attribute( $key );
                $value = $obj->attributeContentToStore( $key );
                $field_def = $fields[$key];

                if ( $field_def['datatype'] == 'float' || $field_def['datatype'] == 'double' )
                {
                    if ( $value === null )
                    {
                        $use_values_hash[$key] = 'NULL';
                    }
                    else
                    {
                        $use_values_hash[$key] = sprintf( '%F', $value );
                    }
                }
                else if ( $field_def['datatype'] == 'int' || $field_def['datatype'] == 'integer' )
                {
                    if ( $value === null )
                    {
                        $use_values_hash[$key] = 'NULL';
                    }
                    else
                    {
                        $use_values_hash[$key] = sprintf( '%d', $value );
                    }
                }
                else
                {
                    // Note: for more colherence, we might use NULL for sql strings if the php value is NULL and not an empty sring
                    //       but to keep compatibility with ez db, where most string columns are "not null default ''",
                    //       and code feeding us a php null value without meaning it, we do not.
                    $use_values_hash[$key] = "'" . $db->escapeString( $value ) . "'";
                }
            }
            foreach ( $doNotEscapeFields as $key )
            {
                $use_values_hash[$key] = $changedValueFields[$key];
            }
            $use_values = array();
            foreach ( $use_field_names as $field )
            $use_values[] = $use_values_hash[$field];
            unset( $use_values_hash );
            $value_text = implode( ", ", $use_values );

            $sql = "INSERT INTO $table ($field_text) VALUES($value_text)";
            $db->query( $sql );

            if ( isset( $def["increment_key"] ) &&
            is_string( $def["increment_key"] ) &&
            //!( $obj->attribute( $def["increment_key"] ) > 0 ) )
            !( $obj->attributeContentToStore( $def["increment_key"] ) > 0 ) )
            {
                $inc = $def["increment_key"];
                $id = $db->lastSerialID( $table, $inc );
                if ( $id !== false )
                $obj->setAttribute( $inc, $id );
            }
        }
        else
        {
            $use_fields = array_diff( array_keys( $fields ), array_merge( $keys, $exclude_fields ) );
            if ( count( $use_fields ) > 0 )
            {
                // If we filter out some of the fields we need to intersect it with $use_fields
                if ( is_array( $fieldFilters ) )
                $use_fields = array_intersect( $use_fields, $fieldFilters );
                $use_field_names = array();
                foreach ( $use_fields as $key )
                {
                    if ( $db->useShortNames() && is_array( $fields[$key] ) && array_key_exists( 'short_name', $fields[$key] ) && strlen( $fields[$key]['short_name'] ) > 0 )
                    $use_field_names[$key] = $fields[$key]['short_name'];
                    else
                    $use_field_names[$key] = $key;
                }

                $field_text = "";
                $field_text_len = 0;
                $i = 0;


                foreach ( $use_fields as $key )
                {
                    //$value = $obj->attribute( $key );
                    $value = $obj->attributeContentToStore( $key );

                    if ( $fields[$key]['datatype'] == 'float' || $fields[$key]['datatype'] == 'double' )
                    {
                        if ( $value === null )
                        $field_text_entry = $use_field_names[$key] . '=NULL';
                        else
                        $field_text_entry = $use_field_names[$key] . "=" . sprintf( '%F', $value );
                    }
                    else if ($fields[$key]['datatype'] == 'int' || $fields[$key]['datatype'] == 'integer' )
                    {
                        if ( $value === null )
                        $field_text_entry = $use_field_names[$key] . '=NULL';
                        else
                        $field_text_entry = $use_field_names[$key] . "=" . sprintf( '%d', $value );
                    }
                    else if ( in_array( $use_field_names[$key], $doNotEscapeFields ) )
                    {
                        $field_text_entry = $use_field_names[$key] . "=" .  $changedValueFields[$key];
                    }
                    else
                    {
                        $field_text_entry = $use_field_names[$key] . "='" . $db->escapeString( $value ) . "'";
                    }

                    $field_text_len += strlen( $field_text_entry );
                    $needNewline = false;
                    if ( $field_text_len > 60 )
                    {
                        $needNewline = true;
                        $field_text_len = 0;
                    }
                    if ( $i > 0 )
                    $field_text .= "," . ($needNewline ? "\n    " : ' ');
                    $field_text .= $field_text_entry;
                    ++$i;
                }
                $cond_text = eZPersistentObject::conditionText( $key_conds );
                $sql = "UPDATE $table SET $field_text$cond_text";
                $db->query( $sql );
            }
        }
        $obj->setHasDirtyData( false );
    }

}

?>
