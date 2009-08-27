<?php
/**
 * Class to read the page configuration and to create the page.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubConfigurationException',
                      'net::stubbles::util::xjconf::xjconf',
                      'net::stubbles::websites::stubAbstractPageFactory'
);
/**
 * Class to read the page configuration and to create the page.
 *
 * @package     stubbles
 * @subpackage  websites
 */
class stubPageXJConfFactory extends stubAbstractPageFactory
{
    /**
     * the xml parser
     *
     * @var  stubXJConfFacade
     */
    private static $xjconf;
    /**
     * path to cache files
     *
     * @var  string
     */
    protected $cachePath;
    /**
     * path to config files
     *
     * @var  string
     */
    protected $configPath;

    /**
     * constructor
     *
     * @param  string  $cachePath   optional  path to cache files
     * @param  string  $configPath  optional  path to config files
     */
    public function __construct($cachePath = null, $configPath = null)
    {
        $this->cachePath  = ((null == $cachePath) ? (stubConfig::getCachePath() . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR) : ($cachePath));
        $this->configPath = ((null == $configPath) ? (stubConfig::getPagePath() . DIRECTORY_SEPARATOR) : ($configPath));
    }

    /**
     * checks whether the page factory knows the page or not
     *
     * @param   string  $configSource  source of the page configuration to use
     * @return  bool
     */
    public function hasPage($configSource)
    {
        return file_exists($this->configPath . $this->pagePrefix . $configSource . '.xml');
    }

    /**
     * returns the configured stubPage instance
     *
     * @param   string    $configSource  name of the page to retrieve
     * @return  stubPage
     */
    protected function doGetPage($configSource)
    {
        $configSource = str_replace('/', DIRECTORY_SEPARATOR, $this->pagePrefix . $configSource);
        $cacheSource  = $this->cachePath . $configSource . '.cache';
        $configSource = $this->configPath . $configSource . '.xml';
        if (file_exists($cacheSource) && filemtime($cacheSource) >= filemtime($configSource)) {
            $cachedPage = unserialize(file_get_contents($cacheSource));
            foreach ($cachedPage['classes'] as $class) {
                stubClassLoader::load($class);
            }
            
            $page = unserialize($cachedPage['data']);
            return $page;
        }
        
        $page = $this->getPageFromXJConf($configSource);
        $cachedPage = array('classes' => array($page->getClassName()),
                            'data'    => serialize($page)
                      );
        foreach ($page->getElements() as $pageElement) {
            $cachedPage['classes'][] = $pageElement->getClassName();
            foreach ($pageElement->getRequiredClassNames() as $requiredClassName) {
                $cachedPage['classes'][] = $requiredClassName;
            }
        }
        
        if (file_exists(dirname($cacheSource)) == false) {
            mkdir(dirname($cacheSource), stubRegistry::getConfig('net.stubbles.filemode', 0700), true);
        }
        
        file_put_contents($cacheSource, serialize($cachedPage));
        return $page;
    }

    /**
     * returns the configured stubPage instance
     *
     * @param   string    $configSource   source of the page configuration to use
     * @return  stubPage
     * @throws  stubConfigurationException
     */
    protected function getPageFromXJConf($configSource)
    {
        if (null == self::$xjconf) {
            stubClassLoader::load('net::stubbles::lang::stubFactory',
                                  'net::stubbles::util::xjconf::xjconfReal'
            );
            self::$xjconf = new stubXJConfFacade(new XJConfFacade(array('__default' => stubXJConfLoader::getInstance())));
            self::$xjconf->addDefinitions(stubFactory::getResourceURIs('xjconf/websites.xml'));
            self::$xjconf->addExtension(new stubConfigXJConfExtension());
        }
        
        try {
            self::$xjconf->parse($configSource);
            return self::$xjconf->getConfigValue('page');
        } catch (stubXJConfException $xjce) {
            throw new stubConfigurationException('Can not read page configuration from ' . $configSource, $xjce);
        }
    }
}
?>