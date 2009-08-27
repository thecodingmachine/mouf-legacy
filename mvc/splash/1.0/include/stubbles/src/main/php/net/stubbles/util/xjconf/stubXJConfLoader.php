<?php
/**
 * Class loader to use for XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::xjconf::XJConfClassLoader');
/**
 * Class loader to use for XJConf.
 *
 * Maps the stubClassLoader.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 * @uses        http://php.xjconf.net/
 */
class stubXJConfLoader extends stubBaseObject implements XJConfClassLoader
{
    /**
     * instance of the class loader
     *
     * @var  stubClassLoader
     */
    private static $instance;

    /**
     * forbidden constructor (singleton)
     */
    private final function __construct()
    {
        // nothing to do
    }

    /**
     * returns an instance of the class loader
     *
     * @return  stubClassLoader
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * forbidden cloning (singleton)
     */
    private final function __clone()
    {
        // nothing to do
    }

    /**
     * load the file with the given class
     *
     * @param  string  $fqClassName  the full qualified class name
     */
    public function loadClass($fqClassName)
    {
        stubClassLoader::load($fqClassName);
    }

    /**
     * returns short class name
     *
     * @param   string  $fqClassName  the full qualified class name
     * @return  string
     */
    public function getType($fqClassName)
    {
        $className = explode('::', $fqClassName);
        return $className[count($className) - 1];
    }
}
?>