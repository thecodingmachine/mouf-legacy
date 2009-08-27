<?php
/**
 * Class loader that loads classes from XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
/**
 * Class loader that loads classes from XJConf.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 * @uses        http://php.xjconf.net/
 */
class stubXJConfClassLoader extends stubBaseObject implements stubForeignClassLoader
{
    /**
     * namespace where this classloader is responsible for
     *
     * @var  string
     */
    protected $namespace = 'net::xjconf';

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        stubClassLoader::registerForeignClassLoader(new self());
    }
    // @codeCoverageIgnoreEnd

    /**
     * sets the namespace where this classloader is responsible for
     *
     * @param  string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
    
    /**
     * returns the namespace where this classloader is responsible for
     *
     * @return  string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * loads the given class
     *
     * @param   string  $fqClassName  the full qualified class name of the class to load
     * @throws  stubClassNotFoundException
     */
    public function load($fqClassName)
    {
        $nqClassName = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($nqClassName, false) == true) {
            return;
        }
        
        if (class_exists('XJConfLoader', false) == false) {
            $this->init();
        }
        
        if (XJConfLoader::classFileExists($fqClassName) == false) {
            throw new stubClassNotFoundException($fqClassName, true);
        }
        
        XJConfLoader::load($fqClassName);
    }
    
    /**
     * initializes XJConf
     *
     * @throws  stubRuntimeException
     */
    protected function init()
    {
        $xjConfUri = StarClassRegistry::getUriForClass('net::xjconf::XJConfLoader');
        if (null === $xjConfUri) {
            // Try to include XJConf from PEAR installation
            if ((@include 'XJConf/XJConfLoader.php') == false) {
                throw new stubRuntimeException('XJConf could not be found in lib nor in include path.');
            }
        } else {
            // Include XJConf via Star
            require $xjConfUri;
        }
    }
}
?>