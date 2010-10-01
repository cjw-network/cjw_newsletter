<?php
/**
 * cjw newsletter Operator autoloading
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag// | $Id: $
 * @package cjw_newsletter
 * @subpackage operators
 * @filesource
 */

$eZTemplateOperatorArray = array();

// $text|cjw_newsletter_preg_replace( $search_string, $replace_string )
$eZTemplateOperatorArray[] = array( 'script' => 'extension/cjw_newsletter/autoloads/cjwnewsletteroperators.php',
                                    'class' => 'CjwNewsletterOperators',
                                    'operator_names' => array( 'cjw_newsletter_preg_replace', 'cjw_newsletter_str_replace' ) );

?>
