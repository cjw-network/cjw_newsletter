<?php
/**
 * File containing the CjwNewsletterOperators class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * php preg_replace | str_replace tpl operator
 *
 * tpl example:
 * {$text|cjw_newsletter_preg_replace( $search_string, $replace_string )}
 * {$text|cjw_newsletter_str_replace( $search_string, $replace_string )}
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterOperators
{
    var $Operators;

    function __construct()
    {
        $this->Operators = array( 'cjw_newsletter_preg_replace', 'cjw_newsletter_str_replace' );
    }

    /*! Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'cjw_newsletter_preg_replace' => array( 'string_search' => array( 'type' => 'string',
                                                                                       'required' => true,
                                                                                       'default' => '' ),
                                                             'string_replace' => array( 'type' => 'string',
                                                                                       'required' => true,
                                                                                       'default' => '' ) ),
                      'cjw_newsletter_str_replace' => array( 'string_search' => array( 'type' => 'string',
                                                                                       'required' => true,
                                                                                       'default' => '' ),
                                                             'string_replace' => array( 'type' => 'string',
                                                                                       'required' => true,
                                                                                       'default' => '' ) )
                     );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'cjw_newsletter_preg_replace':
            {
                $operatorValue = preg_replace( $namedParameters['string_search'], $namedParameters['string_replace'], $operatorValue );
            }
            break;
            case 'cjw_newsletter_str_replace':
            {
                $operatorValue = str_replace( $namedParameters['string_search'], $namedParameters['string_replace'], $operatorValue );
            }
            break;
        }
    }

}
?>