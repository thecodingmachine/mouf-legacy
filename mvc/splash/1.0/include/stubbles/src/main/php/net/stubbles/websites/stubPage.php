<?php
/**
 * Container for a page.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::websites::stubPageElement');
/**
 * Container for a page.
 *
 * @package     stubbles
 * @subpackage  websites
 */
class stubPage extends stubBaseObject
{
    /**
     * list of kay => value properties
     *
     * @var  array<string,scalar>
     */
    protected $properties = array();
    /**
     * Resources that are used by this page
     *
     * @var  array<string,string>
     */
    protected $resources  = array();
    /**
     * list of the elements of this page
     *
     * @var  array<string,stubPageElement>
     */
    protected $elements   = array();

    /**
     * sets a property
     *
     * @param  string  $name   name of the property
     * @param  scalar  $value  value of the property
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * sets the list of properties
     *
     * @param  array  $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * sets the list of resources
     *
     * @param  array  $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * checks whether a property for the page exists
     *
     * @param   string  $name  name of the property
     * @return  bool
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * returns the property or null if it does not exist
     *
     * @param   string  $name  name of the property
     * @return  scalar
     */
    public function getProperty($name)
    {
        if (isset($this->properties[$name]) == true) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * returns the list of resources
     *
     * @return  array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * adds an element to the page
     *
     * @param  stubPageElement  $element
     */
    public function addElement(stubPageElement $element)
    {
        $this->elements[$element->getName()] = $element;
    }

    /**
     * returns the list of elements
     *
     * @return  array<string,stubPageElement>
     */
    public function getElements()
    {
        return $this->elements;
    }
}
?>