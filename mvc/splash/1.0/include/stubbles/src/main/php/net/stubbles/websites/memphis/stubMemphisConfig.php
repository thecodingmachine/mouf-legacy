<?php
/**
 * Reader for memphis configuration files.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::util::xjconf::xjconf');
/**
 * Reader for memphis configuration files.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
class stubMemphisConfig extends stubXJConfAbstractInitializer
{
    /**
     * holds the configuration data
     *
     * @var  array<string,mixed>
     */
    protected $config;

    /**
     * constructor
     *
     * @throws  stubRuntimeException
     */
    public function __construct()
    {
        $this->init();
        if (isset($this->config['parts']) === false || count($this->config['parts']) === 0) {
            throw new stubRuntimeException('No parts configured.');
        }
        
        if (isset($this->config['frames']) === false || count($this->config['frames']) === 0) {
            throw new stubRuntimeException('No frames configured.');
        }
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        return 'memphis';
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array('classes' => array(), 'data' => serialize($this->config));
        foreach ($this->config['parts'] as $part) {
            if (isset($part['defaultElements']) === false) {
                continue;
            }
            
            foreach ($part['defaultElements'] as $defaultElement) {
                $cacheData['classes'][] = $defaultElement->getClassName();
                foreach ($defaultElement->getRequiredClassNames() as $requiredClassName) {
                    $cacheData['classes'][] = $requiredClassName;
                }
            }
            
        }
        
        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        foreach ($cacheData['classes'] as $class) {
            stubClassLoader::load($class);
        }
            
        $this->config = unserialize($cacheData['data']);
    }

    /**
     * returns definitions that are additionally required beyond the default definition
     *
     * @return  array<string>
     */
    public function getAdditionalDefinitions()
    {
        return array('xjconf/websites.xml');
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        $this->config = $xjconf->getConfigValue('config');
    }

    /**
     * returns the list of parts
     *
     * @return  array<string,array<stubPageElement>>
     */
    public function getParts()
    {
        return array_keys($this->config['parts']);
    }

    /**
     * returns a list of default elements for a part
     *
     * @param   string  $part
     * @return  array<stubPageElement>
     */
    public function getDefaultElements($part)
    {
        if (isset($this->config['parts'][$part]) === true && isset($this->config['parts'][$part]['defaultElements']) === true) {
            return $this->config['parts'][$part]['defaultElements'];
        }
        
        return array();
    }

    /**
     * returns the frame template file with the given id
     *
     * @param   string  $frameId
     * @return  string
     */
    public function getFrame($frameId)
    {
        if (isset($this->config['frames'][$frameId]) === true) {
            return $this->config['frames'][$frameId];
        }
        
        if (isset($this->config['frames']['default']) === true) {
            return $this->config['frames']['default'];
        }
        
        return $this->config['frames'][key($this->config['frames'])];
    }

    /**
     * returns a list of configured frames
     *
     * @return  string
     */
    public function getFrames()
    {
        return array_keys($this->config['frames']);
    }

    /**
     * returns a list of meta tags
     *
     * @return  array<string,string>
     */
    public function getMetaTags()
    {
        if (isset($this->config['metaTags']) === true) {
            return $this->config['metaTags'];
        }
        
        return array();
    }
}
?>