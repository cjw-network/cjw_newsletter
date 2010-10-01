<?php
/**
 * File containing CjwNewsletterErrorHandler File
 *
 * To use this errorhandler only
 * include_once( dirname( __FILE__ ).'/CjwNewsletterErrorHandler.php' );
 * it will automatically include if you use
 * $cjwMail = new CjwNewsletterMail;
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */

/**
 *
 * @param unknown_type $errno
 * @param string $errstr
 * @param string $errfile
 * @param string $errline
 * @return void
 */
function cjwNewsletterErrorHandler($errno, $errstr='', $errfile='', $errline='')
{

    $cfg = array();
    $cfg['debug'] = 1;
    $cfg['adminEmail'] = 'felix@jac-systeme.de';

    // if error has been supressed with an @
    if (error_reporting() == 0) {
        return;
    }


    // check if function has been called by an exception
    if (func_num_args() == 5) {
        // called by trigger_error()
        $exception = null;
        list($errno, $errstr, $errfile, $errline) = func_get_args();

        $backtrace = array_reverse(debug_backtrace());

    } else {
        // caught exception
        $exc = func_get_arg(0);
        $errno = $exc->getCode();
        $errstr = $exc->getMessage();
        $errfile = $exc->getFile();
        $errline = $exc->getLine();

        $backtrace = $exc->getTrace();
    }

    $errorType = array (
               E_ERROR            => 'ERROR',
               E_WARNING        => 'WARNING',
               E_PARSE          => 'PARSING ERROR',
               E_NOTICE         => 'NOTICE',
               E_CORE_ERROR     => 'CORE ERROR',
               E_CORE_WARNING   => 'CORE WARNING',
               E_COMPILE_ERROR  => 'COMPILE ERROR',
               E_COMPILE_WARNING => 'COMPILE WARNING',
               E_USER_ERROR     => 'USER ERROR',
               E_USER_WARNING   => 'USER WARNING',
               E_USER_NOTICE    => 'USER NOTICE',
               E_STRICT         => 'STRICT NOTICE',
               E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
               );

    // create error message
    if (array_key_exists($errno, $errorType)) {
        $err = $errorType[$errno];
    } else {
        $err = 'CAUGHT EXCEPTION';
    }

    $errMsg = "$err: $errstr in $errfile on line $errline";

    // start backtrace
    foreach ($backtrace as $v) {

        if (isset($v['class'])) {

            $trace = 'in class '.$v['class'].'::'.$v['function'].'(';

            if (isset($v['args'])) {
                $separator = '';

                foreach ($v['args'] as $arg ) {
                    $trace .= "$separator". getArgument($arg);
                    $separator = ', ';
                }
            }
            $trace .= ')';
        }

        elseif (isset($v['function']) && empty($trace)) {
            $trace = 'in function '.$v['function'].'(';
            if (!empty($v['args'])) {

                $separator = '';

                foreach ($v['args'] as $arg ) {
                    $trace .= "$separator". getArgument($arg);
                    $separator = ', ';
                }
            }
            $trace .= ')';
        }
    }

    // display error msg, if debug is enabled
    if ($cfg['debug'] == 1) {
        echo '<h2>Newsletter Debug Msg</h2>'.nl2br($errMsg).'<br />
            Trace: '.nl2br($trace).'<br />';
    }

    // what to do
    switch ($errno) {
        case E_NOTICE:
        case E_STRICT:
        case E_WARNING:
        case E_USER_NOTICE:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
            return;
            break;

        default:
            if ($cfg['debug'] == 0){
                // send email to admin
                if (!empty($cfg['adminEmail'])) {
                    @mail($cfg['adminEmail'],'critical error on '.$_SERVER['HTTP_HOST'], $errMsg,
                            'From: Error Handler');
                }
                // end and display error msg
                exit( displayClientMessage() );
            }
            else
                exit('<p>aborting.</p>');
            break;

    }

} // end of errorHandler()

/**
 *
 * @return void
 */
function displayClientMessage()
{

    /*
    ob_start();
    debug_print_backtrace();
    $backtrace = ob_get_clean();
    */
    echo '<h1>Sorry, An Error has accoured.</h1>';
    // /echo "<pre>$backtrace</pre";

}

/**
 *
 * @param unknown_type $arg
 * @return unknown_type
 */
function getArgument($arg)
{
    switch (strtolower(gettype($arg))) {

        case 'string':
            return( '"'.str_replace( array("\n"), array(''), $arg ).'"' );

        case 'boolean':
            return (bool)$arg;

        case 'object':
            return 'object('.get_class($arg).')';

        case 'array':
            $ret = 'array(';
            $separtor = '';

            foreach ($arg as $k => $v) {
                $ret .= $separtor . getArgument($k).' => '.getArgument($v);
                $separtor = ', ';
            }
            $ret .= ')';

            return $ret;

        case 'resource':
            return 'resource('.get_resource_type($arg).')';

        default:
            return var_export($arg, true);
    }
}

// eigenen error handler auschalten - zu debugzwecken einschalten

// set_error_handler("cjwNewsletterErrorHandler");

?>
