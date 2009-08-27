<?php
/**
 * Interface for log appenders.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogData');
/**
 * Interface for log appenders.
 * 
 * A log appender takes log data and writes it to the target. The target can be
 * a file, a database or anything else.
 *
 * @package     stubbles
 * @subpackage  util_log
 */
interface stubLogAppender extends stubObject
{
    /**
     * sets the configuration data
     *
     * @param  array  $config
     */
    public function setConfig(array $config);

    /**
     * returns the configuration
     *
     * @return  array
     */
    public function getConfig();

    /**
     * append the log data to the log target
     *
     * @param  stubLogData  $logData
     */
    public function append(stubLogData $logData);

    /**
     * finalize the log target
     * 
     * This will be called in case a logger is destroyed and can be used
     * to close file or database handlers or to write the log data if
     * append() just collects the data.
     */
    public function finalize();
}
?>