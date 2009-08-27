<?php
/**
 * Abstract base implementation of a website initializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::ipo::interceptors::stubInterceptorXJConfInitializer',
                      'net::stubbles::lang::initializer::stubRegistryXJConfInitializer',
                      'net::stubbles::websites::stubWebsiteInitializer',
                      'net::stubbles::websites::processors::stubProcessorResolverXJConfFactory'
);
/**
 * Abstract base implementation of a website initializer.
 *
 * @package     stubbles
 * @subpackage  websites
 */
abstract class stubAbstractWebsiteInitializer extends stubBaseObject implements stubWebsiteInitializer
{
    /**
     * general purpose initializer
     *
     * @var  stubGeneralInitializer
     */
    protected $generalInitializer;

    /**
     * initializing method
     */
    public function init()
    {
        $mode = $this->getMode();
        $mode->registerErrorHandler();
        $mode->registerExceptionHandler();
        stubMode::setCurrent($mode);
    }

    /**
     * returns the mode to be used
     *
     * @return  stubMode
     */
    protected abstract function getMode();

    /**
     * returns the registry initializer to be used
     *
     * @return  stubRegistryInitializer
     */
    public function getRegistryInitializer()
    {
        return new stubRegistryXJConfInitializer();
    }

    /**
     * checks whether a general purpose initializer is set
     *
     * @return  bool
     */
    public function hasGeneralInitializer()
    {
        return (null !== $this->generalInitializer);
    }

    /**
     * returns the general purpose initializer
     *
     * @return  stubGeneralInitializer
     */
    public function getGeneralInitializer()
    {
        return $this->generalInitializer;
    }

    /**
     * returns the interceptor initializer to be used
     *
     * @return  stubInterceptorInitializer
     */
    public function getInterceptorInitializer()
    {
        return new stubInterceptorXJConfInitializer();
    }

    /**
     * returns the factory to be used to resolve the processor
     *
     * @return  stubProcessorResolverFactory
     */
    public function getProcessorResolverFactory()
    {
        return new stubProcessorResolverXJConfFactory();
    }
}
?>