<?php
/**
 * File containing the CjwNewsletterCsvExport class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Export different data in csv format
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterCsvExport extends eZPersistentObject
{
    /**
     * Contains data for csv export
     *
     * @var array
     */
    var $Data = array();

    /**
     * Contains all array keys for csv title's
     *
     * @var array
     */
    var $AllKeys = array();

    /**
     * A part of sorted $AllKeys, which display in csv
     *
     * @var array
     */
    var $ChoosenKeys = array();

    /**
     * Delimiter char for csv items
     *
     * @var string
     */
    var $Delimiter;

    /**
     * Contains new CSV data
     *
     * @var string
     */
    var $CsvResult = '';

    /**
     * Constructor
     *
     * Set required informations
     *
     * @param array $data : data for csv export
     * @param string $delimiter : delimiter char for csv items
     * @param array $choosenKeys : see $this->ChoosenKeys definition
     * @return none
     */
    public function __construct( $data, $delimiter = ';', $choosenKeys = array() )
    {
        // set delimiter
        $this->Delimiter = $delimiter;

        // set data array
        $this->Data = $data;

        // set keys array
        $this->AllKeys = $data[ 0 ];

        // set choosen keys
        $this->setChoosenKeys( $choosenKeys );
    }

    /**
     * Set user choosen keys of all collect keys
     *
     * @param array $choosenKeys : contains identifier
     * @return none
     */
    public function setChoosenKeys( $choosenKeys )
    {
        // only choosen Keys
        if ( is_array( $choosenKeys ) && count( $choosenKeys ) > 0 )
        {
            // set
            $this->ChoosenKeys = $choosenKeys;
            $this->manipulateKeys();
        }
        // else all keys
        else
        {
            // swap identifier with values, required for writeCsv()
            $this->ChoosenKeys = array_flip( $this->AllKeys );
        }
    }

    /**
     * Write data from array in csv format
     *
     * @return none
     */
    public function writeCsv()
    {
        // Char for end of line => break
        $charEol = chr( 13) . chr( 10 );

        // ascii char for delimiter
        $asciiChar = ord( $this->Delimiter );

        // loop arrays for create csv string
        foreach ( $this->Data as $index => $arrDataItem )
        {
            // array index 0 == headers => ignore
            // csv data items
            if ( $index > 0 )
            {
                // loop headers
                // exists a item-key === the current header-key => write in string => result => sorted items in string
                foreach ( $this->ChoosenKeys as $row => $value )
                {
                    if ( isset( $arrDataItem[ $row ] ) )
                    {
                        // maybe there are delimiter chars in string => convert theys
                        $strValidCsvItem = str_replace( $this->Delimiter, "[c$asciiChar]", $arrDataItem[ $row ] );

                        // cat string with delimiter
                        $this->CsvResult .= $strValidCsvItem . $this->Delimiter;
                    }
                }
            }
            // headers
            else
            {
                foreach ( $this->ChoosenKeys as $row => $value )
                {
                    $this->CsvResult .= $row . $this->Delimiter;
                }
            }

            // wash the string
            $this->washCsvString();

            // cut last delimiter
            $this->CsvResult = rtrim( $this->CsvResult, $this->Delimiter );

            // set EOL => End Of Line Char => for line break
            $this->CsvResult .= $charEol;
        }
    }

    /**
     * Write csv string in file for download im webbrowser
     *
     * @param string $csvString : string with csv data
     * @param string $listContentObjectId : listContentObjectId
     * @return none
     */
    public function downloadCsvFile( $listContentObjectId )
    {
        // header informations
        header( 'Content-Type: text/x-csv' );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header( 'Content-Disposition: attachment; filename=' . gmdate( 'Ymd_H-i_' ) . 'subscription_list_export_' . $listContentObjectId . '.csv' );
        header( 'Pragma: no-cache' );

        // output up to 20MB is kept in memory, if it becomes bigger it will automatically be written to a temporary file
        $csvFile = fopen( 'php://temp/maxmemory:'. ( 20 * 1024 * 1024 ), 'r+' );

        // if filehandler opened
        if ( $csvFile )
        {
            // write csv string in file
            fwrite( $csvFile, $this->CsvResult );

            // set file pointer at begin
            rewind( $csvFile );

            // put it all in a variable
            $output = stream_get_contents( $csvFile );

            echo $output;

            eZExecution::cleanExit();
        }
    }

    /**
     * See php doku for details
     *
     * @return none
     */
    private function washCsvString()
    {
        $this->CsvResult = strip_tags( $this->CsvResult );
        $this->CsvResult = trim( $this->CsvResult );
    }

    /**
     * Manipulate array keys for csv title, display, sort, individual functions
     *
     * @return none
     */
    private function manipulateKeys()
    {
        $arrSortedKeyItems = array();

        // loop identifier
        foreach ( $this->ChoosenKeys as $index => $displayItem )
        {
            // if identifier in key array of mysql query
            if ( in_array( $displayItem, $this->AllKeys ) )
            {
                // add params for key => individual function => default value is raw, that means normal output
                switch ( $displayItem )
                {
                    default:
                        $arrSortedKeyItems[ $displayItem ] = 'raw';
                        break;
                }
            }
        }

        // save new array
        $this->ChoosenKeys = $arrSortedKeyItems;
    }
}

?>