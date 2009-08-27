<?php
/**
 * Common interface for XJConf based initializers and factories that use the
 * net::stubbles::util::xjconf::stubXJConfProxy.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer',
                      'net::stubbles::util::xjconf::stubXJConfFacade'
);
/**
 * Common interface for XJConf based initializers and factories that use the
 * net::stubbles::util::xjconf::stubXJConfProxy.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 */
interface stubXJConfInitializer extends stubObject, stubInitializer
{
    /**
     * descriptor type: config
     */
    const DESCRIPTOR_CONFIG     = 'config';
    /**
     * descriptor type: definition
     */
    const DESCRIPTOR_DEFINITION = 'definition';

    /**
     * returns the descriptor that identifies this initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     * @throws  stubIllegalArgumentException
     */
    public function getDescriptor($type);

    /**
     * returns the data to cache
     * 
     * The concrete implementation has to ensure that only serializable data
     * is returned within the array. If any specific classes should be
     * serialized the concrete implementation has to ensure that the class is
     * loaded.
     *
     * @return  array
     */
    public function getCacheData();

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData);

    /**
     * returns definitions that are additionally required beyond the default definition
     *
     * @return  array<string>
     */
    public function getAdditionalDefinitions();

    /**
     * returns a list of extensions for the parser
     *
     * @return  array<Extension>
     */
    public function getExtensions();
    
    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf);
}
?>