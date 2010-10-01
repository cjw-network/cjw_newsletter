<?php
/**
 * File containing CjwNewsletterListFilter class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage extendedattributefilter
 * @filesource
 */
/**
 * This filter allowed, fetch all lists by a specific siteaccess<br>
 * reserved word "current_siteaccess" == take current siteaccess to match<br>
 * param siteaccess : string or array
 *
 * <code>
 *    fetch('content','list',hash('parent_node_id', 2,
 *           'extended_attribute_filter',
 *           hash( 'id', 'CjwNewsletterListFilter',
 *                 'params', hash( 'siteaccess', 'current_siteaccess' ) )
 *         ) )
 * </code>
 *
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage extendedattributefilter
 */
class CjwNewsletterListFilter
{

    /**
     * Constructor
     *
     * @return void
     */
    function CjwNewsletterListFilter()
    {
        // Empty...
    }

    /**
     *
     * @param unknown_type $parameter
     * @return array
     */
    function createSqlParts( $parameter )
    {
        $db = eZDB::instance();
        $sqlCond = false;
        $sqlTables = false;
        $sqlColumns = false;

        $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

        $siteAccessArray = array();
        if ( array_key_exists( 'siteaccess', $parameter ) )
        {
            $paramSiteAccess = $parameter['siteaccess'];
            if ( !is_array( $paramSiteAccess ) )
            {
                if ( $paramSiteAccess == 'current_siteaccess' )
                    $siteAccessArray = array( $currentSiteAccess );
                else
                    $siteAccessArray = array( $paramSiteAccess );
            }
            else
            {
                foreach ( $paramSiteAccess as $name )
                {
                    if ( $name == 'current_siteaccess' )
                        $siteAccessArray = array( $currentSiteAccess );
                    else
                        $siteAccessArray = array( $name );
                }
            }
        }

        if ( count( $siteAccessArray ) > 0 )
        {
            $siteaccessSqlStringArray = '';
            foreach ( $siteAccessArray as $siteAccessName )
            {
                $siteaccessSqlStringArray[] = "c.siteaccess_array_string like '%;". $db->escapeString( $siteAccessName ) .";%'";
            }

            /*    $sqlHasCoordinate = "SELECT * FROM cjwnl_list c, ezcontentobject e
                                     WHERE e.id = c.contentobject_id
                                     AND e.current_version = c.contentobject_attribute_version
                                     AND c.siteaccess_array_string like \"%;jac-example_admin;%\"";*/
            $sqlHasSiteAccess = "SELECT c.contentobject_id FROM cjwnl_list c, ezcontentobject e
                                 WHERE e.id = c.contentobject_id
                                 AND e.current_version = c.contentobject_attribute_version
                                 AND ( ". implode( ' AND ', $siteaccessSqlStringArray ) ." )";

            $sqlHasSiteAccessResult = array( 0 );
            $result =  $db->arrayQuery( $sqlHasSiteAccess );
            foreach ( $result as $row )
            {
                $sqlHasSiteAccessResult[] = $row[ 'contentobject_id' ];
            }
            $sqlHasSiteAccessImplode = implode( ',', $sqlHasSiteAccessResult );

            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sqlHasSiteAccessImplode . ' ) AND ';

            return array( 'tables' => $sqlTables, 'joins'  => $sqlCond, 'columns' => $sqlColumns );
        }
        else
        {
            return array( 'tables' => false, 'joins'  => false, 'columns' => false );
        }
    }
}
?>
