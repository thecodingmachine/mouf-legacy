<?php
/**
 * Variant factory that reads variant configurations from xml files with XJConf.
 * 
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
stubClassLoader::load('net::stubbles::util::xjconf::xjconf',
                      'net::stubbles::websites::variantmanager::stubAbstractVariantFactory'
);
/**
 * Variant factory that reads variant configurations from xml files with XJConf.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
class stubVariantXJConfFactory extends stubAbstractVariantFactory implements stubXJConfInitializer
{
    /**
     * constructor
     *
     * @throws  stubVariantConfigurationException
     */
    public function __construct()
    {
        try {
            $this->init();
        } catch (stubXJConfException $xjce) {
            throw new stubVariantConfigurationException('Can not read variant configuration: ' . $xjce->getMessage(), $xjce);
        }
    }

    /**
     * initialize the interceptors
     *
     * @throws  stubXJConfException
     */
    public function init()
    {
        $xjconfProxy = new stubXJConfProxy($this);
        $xjconfProxy->process();
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        return 'variantmanager';
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array('name'           => $this->variantsMap->getName(),
                           'usePersistence' => $this->variantsMap->shouldUsePersistence(),
                           'rootVariant'    => $this->variantsMap->getRootVariant()->getSerialized()
                     );
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        $this->variantsMap = new stubVariantsMap($cacheData['rootVariant']->getUnserialized());
        $this->variantsMap->setName($cacheData['name']);
        $this->variantsMap->setUsePersistence($cacheData['usePersistence']);
        
    }

    /**
     * returns definitions that are additionally required beyond the default definition
     *
     * @return  array<string>
     */
    public function getAdditionalDefinitions()
    {
        return array();
    }

    /**
     * returns a list of extensions for the parser
     *
     * @return  array<Extension>
     */
    public function getExtensions()
    {
        return array();
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        $this->variantsMap = $xjconf->getConfigValue('variants');
    }
}
?>