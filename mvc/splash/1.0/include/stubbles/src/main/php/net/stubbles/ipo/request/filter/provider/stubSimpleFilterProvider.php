<?php
/**
 * Filter provider simple filters.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
stubClassLoader::load('net::stubbles::reflection::stubReflectionClass');
/**
 * Filter provider simple filters.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
class stubSimpleFilterProvider extends stubBaseObject implements stubFilterProvider
{
    /**
     * shortcut for the filter
     *
     * @var  array<string>
     */
    protected $shortcut  = array();
    /**
     * full qualified class name of the filter to provide
     *
     * @var  string
     */
    protected $classname;

    /**
     * constructor
     *
     * @param  array<string>  $shortcut   shortcut for the filter
     * @param  string         $classname  full qualified class name of the filter to provide
     */
    public function __construct(array $shortcut, $classname)
    {
        $this->shortcut  = $shortcut;
        $this->classname = $classname;
    }

    /**
     * checks whether the filter provider is responsible for given filter
     *
     * @param   string  $shortcut
     * @return  bool
     */
    public function isResponsible($shortcut)
    {
        return in_array($shortcut, $this->shortcut);
    }

    /**
     * returns a filter instance
     *
     * @param   array       $args  optional  constructor arguments
     * @return  stubFilter
     */
    public function getFilter(array $args = null)
    {
        if (null !== $args) {
            $refClass = new stubReflectionClass($this->classname);
            return $refClass->newInstanceArgs($args);
        }
        
        stubClassLoader::load($this->classname);
        $classname = stubClassLoader::getNonQualifiedClassName($this->classname);
        return new $classname();
    }
}
?>