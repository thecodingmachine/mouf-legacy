<?php
/**
 * Interface for initializing the Registry.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer');
/**
 * Interface for initializing the Registry.
 *
 * @package     stubbles
 * @subpackage  lang_initializer
 */
interface stubRegistryInitializer extends stubInitializer
{
    /**
     * sets the config source
     *
     * @param  string  $source
     */
    public function setConfigSource($source);

    /**
     * returns the config source
     *
     * @return  string
     */
    public function getConfigSource();
}
?>