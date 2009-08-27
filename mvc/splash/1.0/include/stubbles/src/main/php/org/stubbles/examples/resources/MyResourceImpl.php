<?php
/**
 * Example resource implementation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  resources
 */
stubClassLoader::load('org::stubbles::examples::resources::MyResource');
/**
 * Example resource implementation.
 *
 * @package     stubbles_examples
 * @subpackage  resources
 */
class MyResourceImpl extends stubSerializableObject implements MyResource
{
    /**
     * the counter
     *
     * @var  int
     */
    protected $count;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->count = 0;
    }

    /**
     * returns the current count value
     *
     * @return  int
     * @XMLTag(tagName='count')
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * increments the counter
     *
     * @XMLIgnore
     */
    public function incrementCount()
    {
        $this->count++;
    }
}
?>