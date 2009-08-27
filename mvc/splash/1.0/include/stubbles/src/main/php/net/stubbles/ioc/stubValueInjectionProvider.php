<?php
/**
 * Injection provider that provides the value it was created with.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubInjectionProvider');
/**
 * Injection provider that provides the value it was created with.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubValueInjectionProvider extends stubBaseObject implements stubInjectionProvider
{
    /**
     * value to provide
     *
     * @var  mixed
     */
    protected $value;

    /**
     * constructor
     *
     * @param  mixed  $value  the value to provide
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * sets the value to provide
     *
     * @param  mixed  $value  the value to provide
     */
    public function setValue($value)
    {
        $this->value = $value;
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