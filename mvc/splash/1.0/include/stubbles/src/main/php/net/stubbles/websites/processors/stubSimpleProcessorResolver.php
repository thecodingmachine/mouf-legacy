<?php
/**
 * A very simple implementation for the processor resolver which returns always
 * the same processor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::websites::processors::stubAbstractProcessorResolver');
/**
 * A very simple implementation for the processor resolver which returns always
 * the same processor.
 *
 * The processor to be returned can be set via the <code>setProcessor()</code>
 * method. The interceptor descriptor will always be <em>interceptors</em>.
 *
 * Additionally one can set the the page factory class to be used by the
 * processor, which determines the page factory class that will be injected into
 * the processor's <code>selectPage()</code> method if the processor implements
 * the <code>net::stubbles::websites::processors::stubPageBasesProcessor</code>
 * interface.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
class stubSimpleProcessorResolver extends stubAbstractProcessorResolver
{
    /**
     * full qualified classname of the processor to use
     * 
     * @var  string
     */
    protected $processor        = null;
    /**
     * page factory class to be used
     *
     * @var  string
     */
    protected $pageFactoryClass = null;

    /**
     * set the processor to return on resolve()
     * 
     * @param  string  $fqClassName  full qualified classname of processor to use
     */
    public function setProcessor($fqClassName)
    {
        $this->processor = $fqClassName;
    }

    /**
     * sets the page factory class to be used
     *
     * @param  string  $pageFactoryClass
     */
    public function setPageFactoryClass($pageFactoryClass)
    {
        $this->pageFactoryClass = $pageFactoryClass;
    }

    /**
     * does the real resolving work
     *
     * @param   stubRequest   $request   the current request
     * @param   stubSession   $session   the current session
     * @param   stubResponse  $response  the current response
     * @return  string        full qualified classname of the processor to create
     */
    protected function doResolve(stubRequest $request, stubSession $session, stubResponse $response)
    {
        return $this->processor;
    }

    /**
     * configures the processor
     *
     * @param  stubProcessor  $processor
     */
    protected function configure(stubProcessor $processor)
    {
        $processor->setInterceptorDescriptor('interceptors');
    }

    /**
     * returns the page factory class for the processor
     *
     * @param   stubProcessor  $processor
     * @return  string
     */
    protected function getPageFactoryClass(stubProcessor $processor)
    {
        return $this->pageFactoryClass;
    }
}
?>