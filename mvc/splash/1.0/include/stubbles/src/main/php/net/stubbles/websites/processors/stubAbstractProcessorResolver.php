<?php
/**
 * Basic abstract implementation of a processor resolver.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubConfigurationException',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::websites::stubPageFactory',
                      'net::stubbles::websites::processors::stubPageBasedProcessor',
                      'net::stubbles::websites::processors::stubProcessorResolver'
);
/**
 * Basic abstract implementation of a processor resolver.
 * 
 * @package     stubbles
 * @subpackage  websites_processors
 */
abstract class stubAbstractProcessorResolver extends stubSerializableObject implements stubProcessorResolver
{
    /**
     * resolves the request and creates the appropriate processor
     *
     * @param   stubRequest    $request   the current request
     * @param   stubSession    $session   the current session
     * @param   stubResponse   $response  the current response
     * @return  stubProcessor
     * @throws  stubConfigurationException
     * @throws  stubRuntimeException
     */
    public function resolve(stubRequest $request, stubSession $session, stubResponse $response)
    {
        $processorClassName = $this->doResolve($request, $session, $response);
        if (null == $processorClassName) {
            throw new stubConfigurationException('Configuration error: no processor specified.');
        }
        
        stubClassLoader::load($processorClassName);
        $className = stubClassLoader::getNonQualifiedClassName($processorClassName);
        $processor = new $className($request, $session, $response);
        if (($processor instanceof stubProcessor) === false) {
            throw new stubRuntimeException($processorClassName . ' is not an instance of ' . stubClassLoader::getFullQualifiedClassName('stubProcessor'));
        }
        
        $this->configure($processor);
        return $processor;
    }

    /**
     * does the real resolving work
     *
     * @param   stubRequest   $request   the current request
     * @param   stubSession   $session   the current session
     * @param   stubResponse  $response  the current response
     * @return  string        full qualified classname of the processor to create
     */
    protected abstract function doResolve(stubRequest $request, stubSession $session, stubResponse $response);

    /**
     * configures the processor
     *
     * @param  stubProcessor  $processor
     */
    protected abstract function configure(stubProcessor $processor);

    /**
     * method to handle page based processors
     *
     * @param   stubProcessor  $processor
     * @throws  stubConfigurationException
     * @throws  stubRuntimeException
     */
    public function selectPage(stubProcessor $processor)
    {
        if (($processor instanceof stubPageBasedProcessor) === false) {
            return;
        }
        
        $pageFactoryClass = $this->getPageFactoryClass($processor);
        if (null == $pageFactoryClass) {
            throw new stubConfigurationException('Configuration error: processor ' . $processor->getClassName() . ' requires page factory, but no page factory class configured.');
        }
        
        $nqClassName = stubClassLoader::getNonQualifiedClassName($pageFactoryClass);
        if (class_exists($nqClassName, false) === false) {
            stubClassLoader::load($pageFactoryClass);
        }
        
        $pageFactory = new $nqClassName();
        if (($pageFactory instanceof stubPageFactory) === false) {
            throw new stubRuntimeException($processor->getClassName() . ' is not an instance of ' . stubClassLoader::getFullQualifiedClassName('stubPageFactory'));
        }
        
        $processor->selectPage($pageFactory);
    }

    /**
     * returns the page factory class for the processor
     *
     * @param   stubProcessor  $processor
     * @return  string
     */
    protected abstract function getPageFactoryClass(stubProcessor $processor);
}
?>