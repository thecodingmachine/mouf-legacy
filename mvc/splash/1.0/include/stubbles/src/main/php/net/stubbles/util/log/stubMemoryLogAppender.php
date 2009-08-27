<?php
/**
 * A log appenders that writes log data into the memory.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogAppender');
/**
 * A log appenders that writes log data into the memory.
 *
 * @package     stubbles
 * @subpackage  util_log
 */
class stubMemoryLogAppender extends stubBaseObject implements stubLogAppender
{
    /**
     * stores the logged data and represents the storing medium (memory)
     *
     * @var  array<string,array<stubLogData>>
     */
    protected $logData = array();

    /**
     * getter method
     *
     * @return  array<string,array<stubLogData>>
     */
    public function getLogData()
    {
        return $this->logData;
    }

    /**
     * nothing to configure
     *
     * @param  array  $config
     */
    public function setConfig(array $config)
    {
        // intentionally empty
    }

    /**
     * nothing to get because nothing to configure
     *
     * @return  array
     */
    public function getConfig()
    {
        return array();
    }

    /**
     * append the log data to the log.
     * the target is used for the key.
     *
     * @param  stubLogData  $logData
     */
    public function append(stubLogData $logData)
    {
        $this->logData[$logData->getTarget()][] = $logData;
    }

    /**
     * finalize the log target
     */
    public function finalize()
    {
        unset($this->logData);
    }
}
?>