<?php
/**
 * Class for creating a resolver with XJConf.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::websites::processors::stubProcessorResolverFactory',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class for creating a resolver with XJConf.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
class stubProcessorResolverXJConfFactory extends stubXJConfAbstractInitializer implements stubProcessorResolverFactory
{
    /**
     * the resolver
     *
     * @var  stubProcessorResolver
     */
    protected $resolver;

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        return 'processors';
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array('resolver' => $this->resolver->getSerialized());
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        $this->resolver = $cacheData['resolver']->getUnserialized();
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        $this->resolver = $xjconf->getConfigValue('resolver');
    }

    /**
     * sets the resolver
     *
     * @param  stubProcessorResolver  $resolver
     */
    public function setResolver(stubProcessorResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * returns the resolver
     *
     * @return  stubProcessorResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }
}
?>