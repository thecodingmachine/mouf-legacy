<?php
/**
 * Page element for including a complete file as content.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubFileNotFoundException',
                      'net::stubbles::websites::memphis::stubMemphisPageElement',
                      'net::stubbles::websites::memphis::stubMemphisTemplate'
);
/**
 * Page element for including a complete file as content.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
class stubMemphisIncludeFilePageElement extends stubMemphisPageElement
{
    /**
     * the source to use
     *
     * @var  string
     */
    protected $source = null;
    /**
     * base directory where content is located
     *
     * @var  string
     */
    protected static $baseDir;

    /**
     * static initializing
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$baseDir = stubRegistry::getConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, stubConfig::getPagePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates');
    }
    // @codeCoverageIgnoreEnd

    /**
     * set the source of the element
     *
     * @param   string  $source
     * @throws  stubFileNotFoundException
     */
    public function setSource($source)
    {
        $existsInBasePath = file_exists(self::$baseDir . DIRECTORY_SEPARATOR . $source);
        if (false === $existsInBasePath && file_exists($source) === false) {
            throw new stubFileNotFoundException(self::$baseDir . DIRECTORY_SEPARATOR . $source);
        }
        
        $this->source = ((false === $existsInBasePath) ? ($source) : (self::$baseDir . DIRECTORY_SEPARATOR . $source));
    }

    /**
     * returns the source of the element
     *
     * @return  string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        if (null === $this->source) {
            return array();
        }
        
        return array('source' => $this->source);
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        if (null === $this->source) {
            return array();
        }
        
        return array($this->source);
    }

    /**
     * processes the page element
     *
     * @return  string
     */
    public function process()
    {
        if (null !== $this->source) {
            return file_get_contents($this->source);
        }
        
        return '';
    }
}
?>