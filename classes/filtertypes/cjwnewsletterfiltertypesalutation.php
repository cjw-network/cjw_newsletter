<?php
/**
* File containing the CjwNewsletterFilterTypeSalutation class
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
class CjwNewsletterFilterTypeSalutation extends CjwNewsletterFilterType
{
    function __construct()
    {
        $salutationNameArray = CjwNewsletterUser::getAvailableSalutationNameArrayFromIni();

        $this->CjwNewsletterFilterType( 'cjwnl_salutation',
                                        ezpI18n::tr( 'cjw_newsletter/filtertypes', 'Salutation', 'Filtertype name' ),
                                        array( /*'serialize_supported' => true*/ ) );

        $this->setValues( array() );
        $this->setValuesAvailable( array( '1' => $salutationNameArray[ 1 ],
                                          '2' => $salutationNameArray[ 2 ]
                                        )
                                 );
        $this->setOperation( 'eq' );
        $this->setOperationsAvailable( array( 'eq' => ezpI18n::tr( 'cjw_newsletter/filtertypes', 'equal', 'Filtertype condition' ),
                                              'ne' => ezpI18n::tr( 'cjw_newsletter/filtertypes', 'not equal', 'Filtertype condition' )
                                            )
                                     );

    }

    /**
    * @return an array with all requirted parts for this filter
    * uses the selected values and operation
    */
    function getDbQueryPartArray()
    {
        $filterArray[ 'fields'] = false;
        $filterArray[ 'tables'] = array( 'cjwnl_user' );
        $filterArray[ 'conds']  = $this->createConditionArray( 'cjwnl_user.salutation', $this->Operation, $this->Values );

        return $filterArray;
    }
}

?>