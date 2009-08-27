<?php
/**
 * Proxy for XJConf.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::lang::stubFactory',
                      'net::stubbles::util::xjconf::stubXJConfInitializer'
);
/**
 * Proxy for XJConf.
 *
 * @package     stubbles
 * @subpackage  util_xjconf
 */
class stubXJConfProxy extends stubBaseObject
{
    /**
     * the initializer to use
     *
     * @var  stubXJConfInitializer
     */
    protected $initializer;
    /**
     * name of the config file to use for initializing
     *
     * @var  string
     */
    protected $configFile;
    /**
     * name of the cache file to use
     *
     * @var  string
     */
    protected $cacheFile;

    /**
     * constructor
     *
     * @param  stubXJConfInitializer  $initializer  the initializer to use
     * @param  string                 $configPath   optional  path to config files
     * @param  string                 $cachePath    optional  path to cache files
     */
    public function __construct(stubXJConfInitializer $initializer, $configPath = null, $cachePath = null)
    {
        $this->initializer = $initializer;
        if (null == $configPath) {
            $configPath = stubConfig::getConfigPath() . '/xml';
        }
        
        if (null == $cachePath) {
            $cachePath = stubConfig::getCachePath();
        }
        
        $this->configFile  = $configPath . '/' . $this->initializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG) . '.xml';
        $this->cacheFile   = $cachePath . '/' . $this->initializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG) . '.cache';
    }

    /**
     * initialize with configuration values from given configuration file
     *
     * @param   array<string>  $sources  optional  a list of sources to parse
     * @return  bool                     true if data was cached, else false
     * @throws  stubXJConfException
     */
    public function process(array $sources = array())
    {
        if (file_exists($this->cacheFile) == true) {
            $useCache = true;
            if (count($sources) == 0 && filemtime($this->cacheFile) < filemtime($this->configFile)) {
                $useCache = false;
            } elseif (count($sources) > 0) {
                foreach ($sources as $source) {
                    if (filemtime($this->cacheFile) < filemtime($source)) {
                        $useCache = false;
                    }
                }
            }
            
            if (true == $useCache) {
                $this->initializer->setCacheData(unserialize(file_get_contents($this->cacheFile)));
                return;
            }
        }
        
        stubClassLoader::load('net::stubbles::util::xjconf::xjconfReal');
        $xjconf = new stubXJConfFacade(new XJConfFacade(array('__default' => stubXJConfLoader::getInstance())));
        $xjconf->addDefinitions(stubFactory::getResourceURIs('xjconf/' . $this->initializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION) . '.xml'));
        foreach ($this->initializer->getAdditionalDefinitions() as $definition) {
            $xjconf->addDefinitions(stubFactory::getResourceURIs($definition));
        }
        
        $xjconf->enableXIncludes();
        foreach ($this->initializer->getExtensions() as $extension) {
            $xjconf->addExtension($extension);
        }
        
        if (count($sources) == 0) {
            $xjconf->parse($this->configFile);
            $this->initializer->loadData($xjconf);
        } else {
            foreach ($sources as $source) {
                $xjconf->parse($source);
                $this->initializer->loadData($xjconf);
                $xjconf->clearConfigValues();
            }
        }
        
        file_put_contents($this->cacheFile, serialize($this->initializer->getCacheData()));
    }
}
?>