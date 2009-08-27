<?php
/**
 * Binder for the IoC functionality.
 *
 * Used to create various Binding instance used by
 * the same injector.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::reflection::stubReflectionClass',
                      'net::stubbles::ioc::stubBinding',
                      'net::stubbles::ioc::stubClassBinding',
                      'net::stubbles::ioc::stubConstantBinding',
                      'net::stubbles::ioc::stubInjector',
                      'net::stubbles::ioc::stubBindingScope',
                      'net::stubbles::ioc::stubBindingScopes',
                      'net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::stubbles::ioc::annotations::stubSingletonAnnotation',
                      'net::stubbles::ioc::annotations::stubNamedAnnotation',
                      'net::stubbles::ioc::annotations::stubImplementedByAnnotation');
/**
 * Binder for the IoC functionality.
 *
 * Used to create various Binding instance used by
 * the same injector.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubBinder extends stubBaseObject
{
    /**
     * Key for storing the binder in the registry
     */
    const REGISTRY_KEY = 'net.stubbles.ioc.stubBinder';

    /**
     * Injector used by this binder
     *
     * @var  stubInjector
     */
    protected $injector = null;

    /**
     * Create a new binder
     *
     * @param  stubInjector  $injector
     */
    public function __construct(stubInjector $injector = null)
    {
        if ($injector === null) {
            $this->injector = new stubInjector();
        } else {
            $this->injector = $injector;
        }
    }

    /**
     * Bind a new interface to a class
     *
     * @param   string  $interface
     * @return  stubClassBinding
     */
    public function bind($interface)
    {
        $binding = new stubClassBinding($this->injector, $interface);
        $this->injector->addBinding($binding);
        return $binding;
    }

    /**
     * Bind a new constant
     *
     * @return  stubConstantBinding
     */
    public function bindConstant()
    {
        $binding = new stubConstantBinding();
        $this->injector->addBinding($binding);
        return $binding;
    }

    /**
     * Get an injector for this binder
     *
     * @return  stubInjector
     */
    public function getInjector()
    {
        return $this->injector;
    }
}
?>