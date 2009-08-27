<?php
/**
 * Binding to bind an interface to an implementation
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::exceptions::stubBindingException',
                      'net::stubbles::ioc::stubBinding',
                      'net::stubbles::ioc::stubBindingScope',
                      'net::stubbles::ioc::stubDefaultInjectionProvider',
                      'net::stubbles::ioc::stubInjectionProvider',
                      'net::stubbles::ioc::stubValueInjectionProvider',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::reflection::stubBaseReflectionClass',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Binding to bind an interface to an implementation
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubClassBinding extends stubBaseObject implements stubBinding
{
    /**
     * The injector used by this binding
     *
     * @var  stubInjector
     */
    protected $injector = null;
    /**
     * The type for this binding
     *
     * @var  string
     */
    protected $type     = null;
    /**
     * The class that implements this binding
     *
     * @var  stubReflectionClass
     */
    protected $impl     = null;
    /**
     * Annotated with a name
     *
     * @var  string
     */
    protected $name     = null;
    /**
     * Scope of the binding
     *
     * @var  stubBindingScope
     */
    protected $scope    = null;
    /**
     * Instance this type is bound to
     *
     * @var  object
     */
    protected $instance = null;
    /**
     * The provider to use for this binding
     *
     * @var  stubInjectionProvider
     */
    protected $provider = null;

    /**
     * constructor
     *
     * @param  stubInjector  $injector
     * @param  string        $type
     */
    public function __construct($injector, $type)
    {
        $this->injector = $injector;
        $this->type     = $type;
        $this->impl     = $type;
    }

    /**
     * set the concrete implementation
     *
     * @param   stubBaseReflectionClass|string  $impl
     * @return  stubBinding
     * @throws  stubIllegalArgumentException
     */
    public function to($impl)
    {
        if (is_string($impl) === false && ($impl instanceof stubBaseReflectionClass) === false) {
            throw new stubIllegalArgumentException('$impl must be a string or an instance of net::stubbles::reflection::stubBaseReflectionClass');
        }
        
        $this->impl = $impl;
        return $this;
    }

    /**
     * set the concrete instance
     *
     * @param   object       $instance
     * @return  stubBinding
     * @throws  stubIllegalArgumentException
     */
    public function toInstance($instance)
    {
        if (($instance instanceof $this->type) === false) {
            throw new stubIllegalArgumentException('Instance of ' . $this->type . ' expectected, ' . get_class($instance) . ' given.');
        }
        
        $this->instance = $instance;
        return $this;
    }

    /**
     * set the provider that should be used to create instances for this binding.
     *
     * This cannot be used in conjuction with the 'toInstance()' method.
     *
     * @param   stubInjectionProvider  $provider
     * @return  stubBinding
     */
    public function toProvider(stubInjectionProvider $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * set the scope
     *
     * @param   stubBindingScope  $scope
     * @return  stubBinding
     */
    public function in(stubBindingScope $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Set the name of the injection
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
     * This method will at first look for an existing instance in the scope and
     * then create the instance using the injection provider.
     *
     * @return  stubInjectionProvider
     */
    public function getProvider()
    {
        if (null === $this->instance) {
            if (is_string($this->impl) === true) {
                $this->impl = new stubReflectionClass($this->impl);
            }
    
            if (null === $this->scope) {
                if ($this->impl->hasAnnotation('Singleton') === true) {
                    $this->scope = stubBindingScopes::$SINGLETON;
                }
            }
            
            if (null === $this->provider) {
                $this->provider = new stubDefaultInjectionProvider($this->injector, $this->impl);
            }
        } else {
            $this->provider = new stubValueInjectionProvider($this->instance);
        }

        if (null !== $this->scope) {
            return $this->scope->getProvider(new stubReflectionClass($this->type), $this->impl, $this->provider);
        }
        
        return $this->provider;
    }

    /**
     * creates a unique key for this binding
     *
     * @return  string
     */
    public function getKey()
    {
        if (null === $this->name) {
            return $this->type;
        }
        
        return $this->type . '#' . $this->name;
    }
}
?>