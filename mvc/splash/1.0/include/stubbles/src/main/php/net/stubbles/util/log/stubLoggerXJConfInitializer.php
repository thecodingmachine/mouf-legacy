<?php
/**
 * Class to create the logging infrastructure from an xml configuration.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogger',
                      'net::stubbles::util::log::stubLoggerInitializer',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class to create the logging infrastructure from an xml configuration.
 *
 * @static
 * @package     stubbles
 * @subpackage  util_log
 * @uses        http://php.xjconf.net/
 */
class stubLoggerXJConfInitializer extends stubXJConfAbstractInitializer implements stubLoggerInitializer
{
    /**
     * descriptor used for the config file
     *
     * @var  string
     */
    protected $descriptor = 'logging';

    /**
     * set the descriptor used for the config file
     *
     * @param  string  $descriptor
     */
    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        switch ($type) {
            case stubXJConfInitializer::DESCRIPTOR_CONFIG:
                return $this->descriptor;
            
            case stubXJConfInitializer::DESCRIPTOR_DEFINITION:
                // break omitted
            
            default:
                return 'logging';
        }
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array();
        $loggerIds = stubLogger::getInstanceList();
        foreach ($loggerIds as $loggerId) {
            $logger = stubLogger::getInstance($loggerId);
            $cacheData[$loggerId] = array('level'    => $logger->getLevel(),
                                          'appender' => array()
                                    );
            foreach ($logger->getLogAppenders() as $logAppender) {
                $cacheData[$loggerId]['appender'][$logAppender->getClassname()] = $logAppender->getConfig();
            }
        }
        
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        foreach ($cacheData as $loggerId => $loggerConfig) {
            $logger = stubLogger::getInstance($loggerId, $loggerConfig['level']);
            foreach ($loggerConfig['appender'] as $logAppenderClassName => $logAppenderConfig) {
                $nqClassName = stubClassLoader::getNonQualifiedClassName($logAppenderClassName);
                if (class_exists($nqClassName, false) == false) {
                    stubClassLoader::load($logAppenderClassName);
                }
                
                $logAppender = new $nqClassName();
                $logAppender->setConfig($logAppenderConfig);
                $logger->addLogAppender($logAppender);
            }
        }
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        // intentionally empty, all stubLogger instances are inside a static member of stubLogger
    }
}
?>