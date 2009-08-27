<?php
/**
 * Class for initializing the stubRegistry via XJConf.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::lang::initializer::stubRegistryInitializer',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class for initializing the stubRegistry via XJConf.
 *
 * @package     stubbles
 * @subpackage  lang_initializer
 */
class stubRegistryXJConfInitializer extends stubXJConfAbstractInitializer implements stubRegistryInitializer
{
    /**
     * name of the configuration file to use
     *
     * @var  string
     */
    protected $source = 'config';

    /**
     * sets the config source
     *
     * @param  string  $source
     */
    public function setConfigSource($source)
    {
        $this->source = $source;
    }

    /**
     * returns the config source
     *
     * @return  string
     */
    public function getConfigSource()
    {
        return $this->source;
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     * @throws  stubIllegalArgumentException
     */
    public function getDescriptor($type)
    {
        switch ($type) {
            case stubXJConfInitializer::DESCRIPTOR_CONFIG:
                return $this->source;
            
            case stubXJConfInitializer::DESCRIPTOR_DEFINITION:
                return 'config';
            
            default:
                // intentionally empty
        }
        
        throw new stubIllegalArgumentException('Invalid descriptor type.');
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array();
        foreach (stubRegistry::getConfigKeys() as $key) {
            $cacheData[$key] = stubRegistry::getConfig($key);
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
        foreach ($cacheData as $key => $value) {
            stubRegistry::setConfig($key, $value);
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
        // intentionally empty
    }
}
?>