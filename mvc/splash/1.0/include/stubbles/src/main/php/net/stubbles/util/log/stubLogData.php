<?php
/**
 * Interface for log data.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 * @version     $Id: stubLogData.php 1928 2008-11-13 21:21:01Z mikey $
 */
/**
 * Interface for log data.
 * 
 * The log data object is used to collect the data to log.
 *
 * @package     stubbles
 * @subpackage  util_log
 */
interface stubLogData extends stubObject
{
    /**
     * registry key for request class to be used
     */
    const CLASS_REGISTRY_KEY = 'net.stubbles.util.log.class';
    /**
     * default seperator to be used to seperate the log fields
     */
    const SEPERATOR = '|';

    /**
     * adds data to the log object
     * 
     * Each call to this method will add a new field.
     *
     * @param   string       $data
     * @return  stubLogData
     */
    public function addData($data);

    /**
     * returns the whole log data as one line
     *
     * @return  string
     */
    public function get();

    /**
     * returns the level of the log data
     * 
     * @return  int
     * @see     stubLogger::LEVEL_*
     */
    public function getLevel();

    /**
     * returns the target where the log data should go to
     * 
     * How the target is interpreted depends on the log appender which
     * takes the log data. A file log appender might use this as the basename
     * of a file, while a database log appender might use this as the name
     * of the table to write the log data into. Therefore it is advisable to
     * only use ascii characters, numbers and underscores to be sure that the
     * log appender will not mess up the log data.
     * 
     * @return  string
     */
    public function getTarget();
}
?>