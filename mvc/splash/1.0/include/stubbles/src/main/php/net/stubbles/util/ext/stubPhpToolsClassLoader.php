<?php
/**
 * Class loader that loads classes from the PHP-Tools project.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_ext
 */
/**
 * Class loader that loads classes from the PHP-Tools project.
 *
 * @package     stubbles
 * @subpackage  util_ext
 * @link        http://php-tools.net/
 */
class stubPhpToolsClassLoader extends stubBaseObject implements stubForeignClassLoader
{
    /**
     * namespace where this classloader is responsible for
     *
     * @var  string
     */
    protected $namespace = 'net::php-tools';
    /**
     * path to pat classes
     *
     * @var  string
     */
    protected $path;

    /**
     * constructor
     *
     * @param  string  $path  path to pat classes
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

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
        
        $fileName = $this->path . DIRECTORY_SEPARATOR . str_replace('::', DIRECTORY_SEPARATOR, str_replace($this->namespace . '::', '', $fqClassName)) . '.php';
        if (file_exists($fileName) == false) {
            throw new stubClassNotFoundException($fqClassName, true);
        }
        
        require $fileName;
    }
}
?>