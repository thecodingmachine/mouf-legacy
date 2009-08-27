<?php
/**
 * Class loader that loads classes from PEAR.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_ext
 */
/**
 * Class loader that loads classes from PEAR.
 *
 * @package     stubbles
 * @subpackage  util_ext
 * @see         http://pear.php.net/
 */
class stubPearClassLoader extends stubBaseObject implements stubForeignClassLoader
{
    /**
     * namespace where this classloader is responsible for
     *
     * @var  string
     */
    protected $namespace = 'net::php::pear';

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
     * @throws  Exception
     */
    public function load($fqClassName)
    {
        $nqClassName = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($nqClassName, false) == true) {
            return;
        }
        
        require str_replace('::', DIRECTORY_SEPARATOR, str_replace($this->namespace . '::', '', $fqClassName)) . '.php';
    }
}
?>