<?php
/**
* File containing the CjwNewsletterFilterTypeEmail class
*
* @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
* @license http://ez.no/licenses/gnu_gpl GNU GPL v2
* @author felix.woldt@jac-systeme.de
* @version //autogentag//
* @package cjw_newsletter
* @filesource
*/
/**
 * Class description here
 *
 * @todo define name, which db fields are required
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterFilterTypeEmail extends CjwNewsletterFilterType
{
    function __construct()
    {
        $this->CjwNewsletterFilterType( 'cjwnl_email',
                                        ezpI18n::tr( 'cjw_newsletter/filtertypes', 'Email', 'Filtertype name' ),
                                        array( /*'serialize_supported' => true*/ ) );


        $this->setValues( array() );
        $this->setValuesAvailable( 'text' );
        $this->setOperation( 'like' );
        $this->setOperationsAvailable( array( 'eq'   => ezpI18n::tr( 'cjw_newsletter/filtertypes', 'equal', 'Filtertype condition' ),
                                              'like' => ezpI18n::tr( 'cjw_newsletter/filtertypes', 'contains', 'Filtertype condition' )
                                            )
                                     );
    }

    function getDbQueryPartArray()
    {
        //TODO OR
        $filterArray[ 'fields' ] = array( );
        $filterArray[ 'tables' ] = array( 'cjwnl_user' );
        $filterArray[ 'conds' ]  = $this->createConditionArray( 'cjwnl_user.email', $this->Operation, $this->Values );

        return $filterArray;
    }
}

?>