<?php
/**
 * Injector for the IoC functionality.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::exceptions::stubBindingException',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Injector for the IoC functionality.
 *
 * Used to create the instances.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubInjector extends stubBaseObject implements stubClonable
{
    /**
     * bindings used by the injector that are not yet in the index
     *
     * @var  array<stubBinding>
     */
    protected $bindings   = array();
    /**
     * index for faster access to bindings
     *
     * Do not access this array directly, use getIndex() instead. The binding
     * index is a requirement because the key for a binding is not necessarily
     * complete when the binding is added to the injector.
     *
     * @var  array<string,stubBinding>
     * @see  stubInjector::getIndex()
     */
    private $bindingIndex = array();

    /**
     * get an instance
     *
     * @param   string  $type
     * @param   string  $name
     * @return  object
     * @throws  stubBindingException
     */
    public function getInstance($type, $name = null)
    {
        $binding = $this->getBinding($type, $name);
        if (null === $binding) {
            throw new stubBindingException('No binding for ' . $type . ' defined');
        }
        
        return $binding->getProvider()->get($type, $name);
    }

    /**
     * adds a new binding to the injector
     *
     * @param  stubBinding  $binding
     */
    public function addBinding(stubBinding $binding)
    {
        $this->bindings[] = $binding;
    }

    /**
     * returns the binding for a name and type
     *
     * @param   string       $type
     * @param   string       $name
     * @return  stubBinding
     */
    protected function getBinding($type, $name = null)
    {
        $bindingIndex = $this->getIndex();
        if (null !== $name) {
            if (isset($bindingIndex[$type.'#'.$name]) === true) {
                return $bindingIndex[$type.'#'.$name];
            }
        }
        
        if (isset($bindingIndex[$type]) === true) {
            return $bindingIndex[$type];
        }
        
        // check for default implementation
        $typeClass = new stubReflectionClass($type);
        if ($typeClass->hasAnnotation('ImplementedBy') === true) {
            $implementedBy = $typeClass->getAnnotation('ImplementedBy');
            $binding       = new stubClassBinding($this, $type);
            $binding->to($implementedBy->getDefaultImplementation());
            $this->addBinding($binding);
            return $binding;
        }

        // try implicit binding
        if ($typeClass->isInterface() === false) {
            $binding = new stubClassBinding($this, $type);
            $binding->to($typeClass);
            $this->addBinding($binding);
            return $binding;
        }
        
        return null;
    }

    /**
     * returns the binding index
     *
     * @return  array<string,stubBinding>
     */
    protected function getIndex()
    {
        if (empty($this->bindings) === true) {
            return $this->bindingIndex;
        }
        
        foreach ($this->bindings as $binding) {
            $this->bindingIndex[$binding->getKey()] = $binding;
        }
        
        $this->bindings = array();
        return $this->bindingIndex;
    }

    /**
     * check whether a binding for a type is available
     *
     * @param   string   $type
     * @param   string   $name
     * @return  boolean
     */
    public function hasBinding($type, $name = null)
    {
        return ($this->getBinding($type, $name) != null);
    }

    /**
     * handle injections for given instance
     *
     * @param   object                   $instance
     * @param   stubBaseReflectionClass  $class     optional
     * @throws  stubBindingException
     */
    public function handleInjections($instance, stubBaseReflectionClass $class = null)
    {
        if (null === $class) {
            $class = new stubReflectionClass(get_class($instance));
        }
        
        foreach ($class->getMethods() as $method) {
            if (strncmp($method->getName(), '__', 2) === 0 || $method->isPublic() === false || $method->hasAnnotation('Inject') === false) {
                continue;
            }
            
            $paramValues = array();
            foreach ($method->getParameters() as $param) {
                $paramClass = $param->getClass();
                if (null !== $paramClass) {
                    $type = $paramClass->getName();
                } else {
                    $type = stubConstantBinding::TYPE;
                }

                $name = null;
                if ($method->hasAnnotation('Named') === true) {
                    $name = $method->getAnnotation('Named')->getName();
                }
                
                if ($this->hasBinding($type, $name) === false) {
                    if ($method->getAnnotation('Inject')->isOptional() === true) {
                        continue 2;
                    }
                    
                    throw new stubBindingException('Could not create instance of ' . $type . '. No binding for type ' . $type . ' specified.');
                }
                
                $paramValues[] = $this->getInstance($type, $name);
            }
            
            $method->invokeArgs($instance, $paramValues);
        }
    }
}
?>