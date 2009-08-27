<?php
/**
 * Default injection provider.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubInjectionProvider',
                      'net::stubbles::ioc::stubInjector'
);
/**
 * Default injection provider.
 *
 * Creates objects and injects all dependencies via
 * the default stubInjector.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubDefaultInjectionProvider extends stubBaseObject implements stubInjectionProvider
{
    /**
     * injector to use for dependencies
     *
     * @var  stubInjector
     */
    protected $injector;
    /**
     * concrete implementation to use
     *
     * @var  stubBaseReflectionClass
     */
    protected $impl;

    /**
     * constructor
     *
     * @param  stubInjector             $injector
     * @param  stubBaseReflectionClass  $impl
     */
    public function __construct(stubInjector $injector, stubBaseReflectionClass $impl)
    {
        $this->injector = $injector;
        $this->impl     = $impl;
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
        $constructor = $this->impl->getConstructor();
        if (null === $constructor) {
            $instance = $this->impl->newInstance();
        } elseif ($constructor->hasAnnotation('Inject') === false) {
            $instance = $this->impl->newInstance();
        } else {
            $paramValues = array();
            foreach ($constructor->getParameters() as $param) {
                $class         = $param->getClass();
                $paramValues[] = $this->injector->getInstance($class->getName());
            }
            
            $instance = $this->impl->newInstanceArgs($paramValues);
        }

        $this->injector->handleInjections($instance, $this->impl);
        return $instance;
    }
}
?>