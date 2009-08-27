<?php
/**
 * Default implementation for the processor resolver.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPreSelectValidator',
                      'net::stubbles::lang::exceptions::stubConfigurationException',
                      'net::stubbles::websites::processors::stubAbstractProcessorResolver'
);
/**
 * Default implementation for the processor resolver.
 *
 * The default processor resolver is able to select the processor to be used
 * for the current request depending on the request parameter <em>processor</em>.
 * For instance, if you add two processors with
 * <code>
 *   $defaultProcessor->addProcessor('foo', 'org::stubbles::test::FooProcessor');
 *   $defaultProcessor->addProcessor('bar', 'org::stubbles::test::BarProcessor');
 * </code>
 * then the first processor class will be selected if the value of the request
 * param is <em>foo</em>.
 *
 * To make sure that a processor gets selected even in case the parameter is not
 * set or has a wrong value, one can set the default processor to be used:
 * <code>
 *   $defaultProcessor->setDefaultProcessor('foo');
 * </code>
 * Now, the processor with this key will be selected in such cases as well. You
 * should make sure that the default processor matches one of the added
 * processors, else an exception will be thrown.
 * 
 * Additionally one can set the interceptor descriptor and the page factory
 * class. While the first is used to determine the interceptor configuration to
 * be used in conjunction with this processor, the latter determines the page
 * factory class that will be injected into the processor's
 * <code>selectPage()</code> method if the processor implements the
 * <code>net::stubbles::websites::processors::stubPageBasedProcessor</code>
 * interface.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
class stubDefaultProcessorResolver extends stubAbstractProcessorResolver
{
    /**
     * the default processor to use
     * 
     * @var  string
     */
    protected $defaultProcessor       = null;
    /**
     * list of processors
     * 
     * @var  array<string,string>
     */
    protected $processors             = array();
    /**
     * list of interceptor descriptors
     *
     * @var  array<string,string>
     */
    protected $interceptorDescriptors = array();
    /**
     * list of page factory classes
     *
     * @var  array<string,string>
     */
    protected $pageFactoryClasses     = array();

    /**
     * adds a processor to the list of available processors
     * 
     * @param  string  $paramValue             value of the request parameter that identifies this processor
     * @param  string  $fqClassName            full qualified class name of the processor
     * @param  string  $interceptorDescriptor  optional  the interceptor descriptor
     * @param  string  $pageFactoryClass       optional  page factory class for the processor
     */
    public function addProcessor($paramValue, $fqClassName, $interceptorDescriptor = null, $pageFactoryClass = null)
    {
        $this->processors[$paramValue] = $fqClassName;
        if (null != $interceptorDescriptor) {
            $this->interceptorDescriptors[$fqClassName] = $interceptorDescriptor;
        } else {
            $this->interceptorDescriptors[$fqClassName] = 'interceptors';
        }
        
        if (null !== $pageFactoryClass) {
            $this->pageFactoryClasses[$fqClassName] = $pageFactoryClass;
        }
    }

    /**
     * sets the name of the default processor
     * 
     * @param  string  $defaultProcessor  value of the request parameter that identifies this processor
     */
    public function setDefaultProcessor($defaultProcessor)
    {
        $this->defaultProcessor = $defaultProcessor;
    }

    /**
     * does the real resolving work
     *
     * @param   stubRequest   $request   the current request
     * @param   stubSession   $session   the current session
     * @param   stubResponse  $response  the current response
     * @return  string        full qualified classname of the processor to create
     * @throws  stubConfigurationException
     */
    protected function doResolve(stubRequest $request, stubSession $session, stubResponse $response)
    {
        $paramValue = null;
        if ($request->hasValue('processor') === true) {
            $paramValue = $request->getValidatedValue(new stubPreSelectValidator(array_keys($this->processors)), 'processor');
        }
        
        if (null == $paramValue) {
            if (null == $this->defaultProcessor || isset($this->processors[$this->defaultProcessor]) === false) {
                throw new stubConfigurationException('Configuration error: the default processor ' . $this->defaultProcessor . ' is not set.');
            }
            
            $paramValue = $this->defaultProcessor;
        }
            
        $session->putValue('net.stubbles.websites.lastProcessor', $paramValue);
        return $this->processors[$paramValue];
    }

    /**
     * configures the processor
     *
     * @param  stubProcessor  $processor
     */
    protected function configure(stubProcessor $processor)
    {
        $processorClassName = $processor->getClassName();
        if (isset($this->interceptorDescriptors[$processorClassName]) === true && strlen($this->interceptorDescriptors[$processorClassName]) > 0) {
            $processor->setInterceptorDescriptor($this->interceptorDescriptors[$processorClassName]);
        }
    }

    /**
     * returns the page factory class for the processor
     *
     * @param   stubProcessor  $processor
     * @return  string
     */
    protected function getPageFactoryClass(stubProcessor $processor)
    {
        $processorClassName = $processor->getClassName();
        if (isset($this->pageFactoryClasses[$processorClassName]) === true) {
            return $this->pageFactoryClasses[$processorClassName];
        }
        
        return null;
    }
}
?>