<?php
/**
 * Factory to create log data objects.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 * @version     $Id: stubLogDataFactory.php 1931 2008-11-13 22:25:24Z mikey $
 */
stubClassLoader::load('net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::util::log::stubLogData',
                      'net::stubbles::util::log::stubLogger'
);
/**
 * Factory to create log data objects.
 *
 * @static
 * @package     stubbles
 * @subpackage  util_log
 */
class stubLogDataFactory extends stubBaseObject
{
    /**
     * creates a stubLogData object
     *
     * @param   string       $target  target where the log data should go to
     * @param   int          $level   optional  log level of the log data
     * @return  stubLogData
     * @throws  stubRuntimeException
     */
    public static function create($target, $level = stubLogger::LEVEL_INFO)
    {
        $fqClassName = stubRegistry::getConfig(stubLogData::CLASS_REGISTRY_KEY, 'net::stubbles::util::log::stubBaseLogData');
        $nqClassName = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($nqClassName, false) === false) {
            stubClassLoader::load($fqClassName);
        }
        
        $logData = new $nqClassName($target, $level);
        if (($logData instanceof stubLogData) === false) {
            throw new stubRuntimeException('Configured ' . stubLogData::CLASS_REGISTRY_KEY . ' is not an instance of net::stubbles::util::log::stubLogData.');
        }
        
        if (stubBinderRegistry::hasInstance() === true) {
            stubBinderRegistry::get()->getInjector()->handleInjections($logData);
        }
        
        return $logData;
    }
}
?>