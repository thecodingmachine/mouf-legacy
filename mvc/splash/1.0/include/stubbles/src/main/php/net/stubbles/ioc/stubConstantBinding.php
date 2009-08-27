<?php
/**
 * Binding to bind a property to a constant value.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubBinding',
                      'net::stubbles::ioc::stubInjectionProvider'
);
/**
 * Binding to bind a property to a constant value.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubConstantBinding extends stubBaseObject implements stubBinding, stubInjectionProvider
{
    /**
     * This string is used when generating the key for a constant binding.
     */
    const TYPE               = '__CONSTANT__';
    /**
     * annotated with a name
     *
     * @var  string
     */
    protected $name          = null;
    /**
     * value to provide
     *
     * @var  mixed
     */
    protected $value;

    /**
     * set the constant value
     *
     * @param   mixed  $value
     * @return  stubBinding
     */
    public function to($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * set the name of the injection
     *
     * @param   string       $name
     * @return  stubBinding
     */
    public function named($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * returns the provider that has or creates the required instance
     *
     * @return  stubInjectionProvider
     */
    public function getProvider()
    {
        return $this;
    }

    /**
     * creates a unique key for this binding
     *
     * @return  string
     */
    public function getKey()
    {
        return self::TYPE . '#' . $this->name;
    }

    /**
     * returns the value to provide
     *
     * @param   string  $type
     * @param   string  $name
     * @return  mixed
     */
    public function get($type, $name = null)
    {
        return $this->value;
    }
}
?>