<?php
/**
 * Common interface for XJConf based initializers and factories that use the
 * net::stubbles::util::xjconf::stubXJConfProxy.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::util::xjconf::stubXJConfInitializer');
/**
 * Common interface for XJConf based initializers and factories that use the
 * net::stubbles::util::xjconf::stubXJConfProxy.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 */
abstract class stubXJConfAbstractInitializer extends stubBaseObject implements stubXJConfInitializer
{
    /**
     * initialize the interceptors
     *
     * @throws  stubXJConfException
     */
    public function init()
    {
        $xjconfProxy = new stubXJConfProxy($this);
        $xjconfProxy->process();
    }

    /**
     * returns definitions that are additionally required beyond the default definition
     *
     * @return  array<string>
     */
    public function getAdditionalDefinitions()
    {
        return array();
    }

    /**
     * returns a list of extensions for the parser
     *
     * @return  array<Extension>
     */
    public function getExtensions()
    {
        return array(new stubConfigXJConfExtension());
    }
}
?>