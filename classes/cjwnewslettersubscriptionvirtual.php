<?php
/**
 * File containing the CjwNewsletterSubscriptionirtual class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * CjwNewsletterSubscriptionVirtual handels spezific function which are
 * only for virtual newsletter lists
 *
 * @version 1.0.0beta
 * @package cjw_newsletter
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterSubscriptionVirtual extends CjwNewsletterSubscription
{

    /**
     * @return void
     */
    static function definition( )
    {
        $listDefinition = parent::definition( );
        // set Classname for Virtual List
        $listDefinition['class_name'] = 'CjwNewsletterSubscriptionVirtual';

        return $listDefinition;
    }

    /**
     * (non-PHPdoc)
     * @see CjwNewsletterSubscription::isVirtual()
     */
    public function isVirtual( )
    {
        return true;
    }

    /**
     * Returns all static subscriptions which are connected to this list and user
     *
     * @return array
     */
    /*  function getStaticSubscriptionArray()
      {
          // TODO filter only subscriptions which are possible for this virtual list
          $listSubscriptionArray = array();
          $subscriptionArray = CjwNewsletterSubscription::fetchSubscriptionListByNewsletterUserId( $this->attribute('id') );
          foreach ( $subscriptionArray as $subscriptionObject )
          {
              $subscriptionStatus = $subscriptionObject->attribute('status');
              $listSubscriptionArray[ $subscriptionObject->attribute( 'list_contentobject_id') ] = $subscriptionObject;
          }
          return $listSubscriptionArray;
      }*/

    /**
     * set Modifed data if somebody store content
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#store($fieldFilters)
     */
    public function store( $fieldFilters = null )
    {
        // do nothing
        // virtual newsletter subscriptions are read only

        //$this->setModified();
        //parent::store( $fieldFilters );
    }

    /**
     * remove the current subscription
     * @see kernel/classes/eZPersistentObject#remove($conditions, $extraConditions)
     */
    function remove( $conditions = null, $extraConditions = null )
    {
        // do nothing
        // virtual newsletter subscriptions are read only
    }

    /**
     * Search all subsciptions to a list + status
     *
     * @param CjwNewsletterListVirtual $virtualListObject
     * @param integer $status
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByListIdAndStatus( $virtualListObject, $subscriptionStatus, $limit = 50, $offset = 0, $asObject = true )
    {
        // TODO $virtualListContentObjectId get filter settings

        // AND - all filter should match
        // OR - 1 one the filter should be match
        // AND-NOT - none of the filter should be matched

        // $sortArr = array( 'created' => 'desc' );
        $limitArr = null;


        $filterArray = $virtualListObject->getFilterInternalArray( $subscriptionStatus );

        $externalFilterArray = $virtualListObject->getFilterExternalArray( $subscriptionStatus );

        //
        // external filters
        // Filters from extra db table not cjwnl
        //


        // fetch all user as rows
        $subscriptionObjectList = self::fetchByFilter( $filterArray,
                                                       $externalFilterArray,
                                                       $limit,
                                                       $offset,
                                                       true );

   //     $virtualSubscriptionObjectList = self::createFromUserRowArray( $userListSearch,
   //                                                                    $virtualListContentObjectId,
   //                                                                    $asObject );

        return $subscriptionObjectList;
    }

    /**
    * Count all user who subscripe to list
    *
    * @param CjwNewsletterListVirtual $virtualListObject
    * @param mixed int|array $statusIds
    * @return integer
    */
    static function fetchSubscriptionListByListIdCount( $virtualListObject, $subscriptionStatus = false )
    {

        $filterArray = $virtualListObject->getFilterInternalArray( $subscriptionStatus );
        $externalFilterArray = $virtualListObject->getFilterExternalArray( $subscriptionStatus );

        //
        // external filters
        // Filters from extra db table not cjwnl
        //

        // fetch all user as rows
        $count = self::fetchByFilterCount( $filterArray,
                                           $externalFilterArray
        );


        return $count;
    }






    /**
     *
     * Enter description here ...
     * @param unknown_type $filterArray
     * @return int $count
     */
    static function fetchByFilterCount( $filterArray, $externalFilterArray )
    {

        $filterArray['fields'] = array( 'COUNT( cjwnl_subscription.id ) as count' );

        // fetch all user as rows
        $count = self::fetchByFilter( $filterArray,
                                       $externalFilterArray,
                                       0,
                                       0,
                                       false,
                                       true );

        return $count;
    }




    /**
     * Fetch all Newsletter user with extended field
     * whith option to Filter data
     *

     *
     * @param array $filterArray condtions which will be combined with 'AND'
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array with CjwNewsletterUser objects
     */
     static function fetchByFilter( $filterInternalArray,
                                   $filterExternalArray,
                                   $limit = 50,
                                   $offset = 0,
                                   $asObject = true,
                                   $isCount = false )
    {

        //  var_dump( $filterArray );

        /*

         -- alle Frauen
-- zw 30 und 40 Jahre
-- die 'sport' aboniert haben
-- plz bereich 9xxxx

-- SELECT count( cjwnl_subscription.id )
SELECT cjwnl_subscription.*
FROM cjwnl_user, cjwnl_subscription
-- , clubuser
WHERE cjwnl_user.id = cjwnl_subscription.newsletter_user_id
-- AND cjwnl_user.external_user_id = clubuser.id
AND cjwnl_subscription.list_contentobject_id IN ( '109' )
-- AND cjwnl_user.email like '%@%gmx.de'

-- AND clubuser_optin.name = 'Oldie 95 Newsletter'
-- AND clubuser.anrede = 'Frau'
AND cjwnl_user.salutation = 2

-- filter auf externe tabellen nicht cjwnl
-- nur nutzen wenn ein externer Filter ausgewählt wurde
AND EXISTS (
    SELECT clubuser.id AS external_user_id
    -- , clubuser.*
    FROM clubuser_optin, clubuser_optin_relation, clubuser
    WHERE clubuser.id = clubuser_optin_relation.clubuserid
    AND clubuser_optin_relation.optinid = clubuser_optin.id

    AND cjwnl_user.external_user_id = clubuser.id

    -- AND clubuser_optin.name = 'Oldie 95 Newsletter'
    -- AND clubuser.anrede = 'Frau'
    -- AND cjwnl_user.salutation = 2

    -- age 30 - 40
    AND clubuser.geburtsdatum <= DATE_SUB(curdate(), INTERVAL 30 YEAR)
    AND clubuser.geburtsdatum >= DATE_SUB(curdate(), INTERVAL 40 YEAR)
    -- optin sport (35)
    -- AND clubuser_optin.name = 'sport'
     AND clubuser_optin.id = 35
    -- plz 9x
     AND clubuser.plz like '9%'

);



         */

        $db = eZDB::instance( );

        $field_filters = null;
        $conditions = null;
        $sorts = null;
        //   $limit = null;
        //   $asObject = true;
        $grouping = false;
        $custom_fields = null;
        $custom_tables = null;
        $custom_conds = null;

        $def = self::definition( );

        $fields = $def["fields"];
        $tables = $def["name"];
        $class_name = $def["class_name"];


        if ( (int) $limit != 0 )
        {
            $limit = array(
                'limit' => $limit, 'offset' => $offset
            );
        }


        $sqlInternal = self::getFilterInternalSql( $filterInternalArray );

        $sqlExternalCondAndString = self::getFilterExternalSql( $filterExternalArray );


        $sql = "$sqlInternal
                $sqlExternalCondAndString";


        eZDebug::writeDebug( $sql );

        //$db->arrayQuery( $sql );
        if ( $isCount  )
        {
            $rows = $db->arrayQuery( $sql );
            return $rows[0]['count'];

        }
        else
        {
            $rows = $db->arrayQuery( $sql, $limit );
            $objectList = eZPersistentObject::handleRows( $rows,
                                                          $class_name,
                                                          $asObject );
            return $objectList;
        }

        /*       $objectList = eZPersistentObject::fetchObjectList(
        self::definition(),
        $field_filters,
        $conds,
        $sorts,
        $limit,
        $asObject,
        $grouping,
        $custom_fields,
        $custom_tables,
        $custom_conds );
        return $objectList;
         */
    }

    /**
    * TODO move to utility class
    *
    * Generates an SQL sentence from the conditions \a $conditions and row data \a $row.
    * If \a $row is empty (null) it uses the condition data instead of row data.
    * @param unknown_type $conditions
    * @param unknown_type $row
    * @return string
    */
    static function filterText( $conditions, $row = null )
    {
        $db = eZDB::instance();

        $where_text = "";
        if ( is_array( $conditions ) and count( $conditions ) > 0 )
        {
            $where_text = '';//" WHERE  ";
            $i = 0;
            foreach ( $conditions as $id => $cond )
            {

                //  array( 'OR' => array(  $condArr1, $condArr2 ... ) )
                if( $id == 'OR' )
                {
                    $orConditionArray = array();
                    /*for ( $i = 1; $i < count( $cond ); $i++ )
                     {
                    $orConditionArray[] = self::filterText( array( $id => $cond[$i] ) );
                    }*/


                    if (  is_array( $cond ) )
                    {
                        foreach( $cond as $conditionArr )
                        {
                            $orConditionArray[] = self::filterText( $conditionArr );
                        }

                        if ( count( $orConditionArray ) >  0 )
                        {
                            $orText = '( '. implode( "\n    OR "  , $orConditionArray ) . ' )';
                            $where_text .=  $orText;
                        }
                    }
                    continue;
                }
                elseif( $id == 'AND' )
                {
                    foreach( $cond as $conditionArr )
                    {
                        $andConditionArray[] = self::filterText( $conditionArr );
                    }

                    if ( count( $andConditionArray ) >  0 )
                    {
                        $andText = '( '. implode( ' AND ', $andConditionArray ) . ' )';
                        $where_text .= $andText;
                    }
                    continue;
                }


                if ( $i > 0 )
                    $where_text .= " AND ";
                if ( is_array( $row ) )
                {
                    $where_text .= $cond . "='" . $db->escapeString( $row[$cond] ) . "'";
                }
                else
                {
                    if ( is_array( $cond ) )
                    {
                        if ( ( $cond[0] == 'IN' OR $cond[0] == 'NOT IN' ) && is_array( $cond[1] ) )
                        {
                            $sqlOperation = $cond[0];
                            $where_text .= $id . " $sqlOperation ( ";
                            $j = 0;
                            foreach ( $cond[1] as $value )
                            {
                                if ( $j > 0 )
                                $where_text .= ", ";
                                $where_text .= "'" . $db->escapeString( $value ) . "'";
                                ++$j;
                            }
                            $where_text .= ' ) ';
                        }
                        else if ( $cond[0] == 'BETWEEN' && is_array( $cond[1] ) )
                        {
                            $range = $cond[1];
                            $where_text .= "$id BETWEEN '" . $db->escapeString( $range[0] ) . "' AND '" . $db->escapeString( $range[1] ) . "'";
                        }
                        else
                        {
                            switch ( $cond[0] )
                            {
                                case '>=':
                                case '<=':
                                case '<':
                                case '>':
                                case '=':
                                case '<>':
                                case '!=':
                                case 'like':
                                    {
                                        //  raw sql
                                        if ( isset( $cond[2] ) && $cond[2] == 'sql' )
                                        {
                                            $where_text .= $db->escapeString( $id ) . " " . $cond[0] . " " . $db->escapeString( $cond[1] ) ;
                                        }
                                        else
                                        {
                                            if ( is_array( $cond[1] ) )
                                            {
                                                $orArray = array();
                                                foreach( $cond[1] as $value )
                                                {
                                                     $orArray[] =  $db->escapeString( $id ) . " " . $cond[0] . " '" . $db->escapeString( $value ) . "'";
                                                }

                                                $where_text .= implode( ' OR ', $orArray );
                                            }
                                            else
                                                $where_text .= $db->escapeString( $id ) . " " . $cond[0] . " '" . $db->escapeString( $cond[1] ) . "'";
                                        }
                                    } break;


                                default:
                                    {
                                        eZDebug::writeError( "Conditional operator '$cond[0]' is not supported.",'eZPersistentObject::conditionTextByRow()' );
                                    } break;
                            }

                        }
                    }
                    else
                        $where_text .= $db->escapeString( $id ) . "='" . $db->escapeString( $cond ) . "'";
                }
                ++$i;
            }
        }
        return $where_text;
    }




    /**
     *
     * use newsletter user row data to creat a virtual subscirption object

     * @param array $newsletterUserRow
     * @param int $virtualListContentObjectId
     * @param bool $asObject
     * @return object or array
     */
    static function createFromUserRow( $newsletterUserRow, $virtualListContentObjectId, $asObject )
    {
        $subcriptionVirtualObjectList = self::createFromUserRows( array(
                                                                  $newsletterUserRow
                                                                  ),
                                                                  $virtualListContentObjectId,
                                                                  $asObject );
        if ( isset( $subcriptionVirtualObjectList[0] ) )
            return $subcriptionVirtualObjectList[0];
        else
            return false;
    }

    /**
     *
     * Enter description here ...

     * @param unknown_type $newsletterUserRows
     * @param unknown_type $virtualListContentObjectId
     * @param unknown_type $asObject
     * @return multitype:unknown |unknown
     */
    static function createFromUserRowArray( $newsletterUserRows, $virtualListContentObjectId, $asObject )
    {
        $def = self::definition( );
        $className = $def['class_name'];

        $objects = array();
        if ( is_array( $newsletterUserRows ) )
        {
            foreach ( $newsletterUserRows as $index => $row )
            {
                // merge cjwnl_user data to virtual subscription
                $row['list_contentobject_id'] = $virtualListContentObjectId;
                $row['newsletter_user_id'] = $row['id'];
                $row['id'] = 'v.' . $virtualListContentObjectId . '.' . $row['newsletter_user_id'];
                // set status to null so no is confused because the row status is from cjwnl_user
                // and is different to cjwnl_subscription status
                $row['status'] = null;
                // new virtual subscription hash
                // v - $listid - $userhash (configure)
                // v.1202678.4cf476294bf40fdef0e3c6ca1ef4eb50
                $row['hash'] = "v.$virtualListContentObjectId." . $row['hash'];
                $row['remote_id'] = $row['hash'];
                // set default outputformat
                // TODO get setting from user profile
                $row['output_format_array_string'] = ';0;';

                if ( $asObject )
                {
                    $objects[] = new $className( $row);
                }
                else
                {
                    $newsletterUserRows[$index] = $row;
                }
            }
        }

        if ( $asObject )
            return $objects;
        else
            return $newsletterUserRows;
    }

    /**
     * creates a virtual subscriptioobejct
     * for a userId and editionSendId
     * @param int $newsletterUserId
     * @param int $editionSendId
     * @return false or CjwNewsletterSubscriptionVirtual object
     */
    static function createByUserIdAndEditionSendId( $newsletterUserId, $editionSendId )
    {
        $newsletterUserRow = CjwNewsletterUser::fetch( $newsletterUserId, false );
        if ( !is_array( $newsletterUserRow ) )
            return false;

        $editionSendObject = CjwNewsletterEditionSend::fetch( $editionSendId );

        if ( is_object( $editionSendObject ) )
        {
            $subcriptionVirtualObject = self::createFromUserRow( $newsletterUserRow,
                                                                 $editionSendObject->attribute( 'list_contentobject_id' ),
                                                                 true );
            return $subcriptionVirtualObject;
        }
        else
        {
            return false;
        }

    }


    static function getFilterInternalSql( $filterArray )
    {
        eZDebug::writeDebug( 'internal: ' . print_r( $filterArray, true ) );

        return self::createFilterSqlByArray( $filterArray );
    }

    /**
     * array[] = filterArray1[]
     * array[] = filterArray2[]
     * Enter description here ...
     * @param unknown_type $filterExternalArray
     * @return string
     */
    static function getFilterExternalSql( $filterExternalArray )
    {
        eZDebug::writeDebug( 'external: ' . print_r( $filterExternalArray, true ) );

        $externalSqlArray = array();
        foreach( $filterExternalArray as $filterArray )
        {
            $exernalFilterQuery = self::createFilterSqlByArray( $filterArray );
            $externalSqlArray[] = "AND EXISTS ( $exernalFilterQuery )";
        }
        $sqlExternalCondAndString = implode( ',', $externalSqlArray );
        return $sqlExternalCondAndString;
    }

    /**
     * $filterArray['fields']
     * $filterArray['tables']
     * $filterArray['conds']
     *
     * @param unknown_type $filterArray
     */
    static function createFilterSqlByArray( $filterArray )
    {
       // print_r( $filterArray );

        $sqlCondArrayExternal = array();
        $sqlFieldStringExternal = '';

        if ( count( $filterArray['fields'] ) > 0 )
        {
            $sqlFieldStringExternal = implode( ', ', $filterArray['fields'] );
        }

        $sqlTableStringExternal = '';
        if ( count( $filterArray['tables'] ) > 0 )
        {
            $sqlTableStringExternal = implode( ', ', $filterArray['tables'] );
        }

        foreach ( $filterArray['conds'] as $filter )
        {
            $sqlCondArrayExternal[] = self::filterText( $filter );
        }

        //        var_dump( $sqlCondArray );

        $sqlCondAndStringExternal = '';
        if ( count( $sqlCondArrayExternal ) > 0 )
        {
            $sqlCondAndStringExternal = 'WHERE ' . implode( "\n AND ", $sqlCondArrayExternal ) . ' ';
        }



        $exernalFilterQuery =  "SELECT $sqlFieldStringExternal
                                FROM $sqlTableStringExternal
                                $sqlCondAndStringExternal";

        return $exernalFilterQuery;
    }


    /**
    * Count all user who subscripe to list group by status
    *
    * @param object $virtualListObject
    * @return array
    */
    static function fetchSubscriptionListStatistic( $virtualListObject )
    {

        /*

         SELECT cjwnl_subscription.status, COUNT( cjwnl_subscription.id ) as count
                                FROM cjwnl_user, cjwnl_subscription
                                WHERE cjwnl_user.id = cjwnl_subscription.newsletter_user_id
 AND cjwnl_subscription.list_contentobject_id IN ( '109' )
 AND cjwnl_user.email like '%@%gmx.de'
 AND cjwnl_user.salutation='2'
                AND EXISTS ( SELECT clubuser.id AS external_user_id
                                FROM clubuser_optin, clubuser_optin_relation, clubuser
                                WHERE clubuser_optin_relation.optinid = clubuser_optin.id
 AND cjwnl_user.external_user_id = clubuser.id
 AND clubuser.geburtsdatum <= DATE_SUB(curdate(), INTERVAL 30 YEAR)
 AND clubuser.geburtsdatum >= DATE_SUB(curdate(), INTERVAL 40 YEAR)
 AND clubuser_optin.id='35'
 AND clubuser.plz like '9%'  )
GROUP BY cjwnl_subscription.status;

         */


        $filterArray = $virtualListObject->getFilterInternalArray( false );

        $filterExternalArray = $virtualListObject->getFilterExternalArray( false );

        $filterArray['fields'] = array( 'cjwnl_subscription.status',
                                        'COUNT( cjwnl_subscription.id ) as count' );


        $sqlfilter = self::getFilterInternalSql( $filterArray );
        $sqlfilterExternal = self::getFilterExternalSql( $filterExternalArray );


        $db = eZDB::instance();
       /* $query = "SELECT status, COUNT(id) as count
                      FROM cjwnl_subscription
                      WHERE list_contentobject_id=". (int) $listConentObjectId .
                    " GROUP BY status";*/
        $query = "$sqlfilter
                  $sqlfilterExternal
                  GROUP BY cjwnl_subscription.status";

        eZDebug::writeDebug( $query, __METHOD__ );

        $rows = $db->arrayQuery( $query );

        $statistikArray = array(
                                    'all'       => 0,
                                    'pending'   => 0,
                                    'confirmed' => 0,
                                    'approved'  => 0,
                                    'removed'   => 0,
                                    'bounced'   => 0,
                                    'blacklisted' => 0 );

        foreach( $rows as $row  )
        {
            $count = $row['count'];

            switch ( (int) $row['status'] )
            {
                case self::STATUS_PENDING:
                    $statistikArray[ 'pending' ] += $count;
                    break;

                case self::STATUS_CONFIRMED:
                    $statistikArray[ 'confirmed' ] += $count;
                    break;

                case self::STATUS_APPROVED:
                    $statistikArray[ 'approved' ] += $count;
                    break;

                case self::STATUS_REMOVED_ADMIN:
                case self::STATUS_REMOVED_SELF:
                    $statistikArray[ 'removed' ] += $count;
                    break;

                case self::STATUS_BOUNCED_SOFT:
                case self::STATUS_BOUNCED_HARD:
                    $statistikArray[ 'bounced' ] += $count;
                    break;

                case self::STATUS_BLACKLISTED:
                    $statistikArray[ 'blacklisted' ] += $count;
                    break;
            }
            $statistikArray[ 'all' ] += $count;
        }
        return $statistikArray;
    }



}

?>
