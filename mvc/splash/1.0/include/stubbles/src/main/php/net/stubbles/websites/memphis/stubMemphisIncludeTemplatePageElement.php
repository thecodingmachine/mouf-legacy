<?php
/**
 * Page element for including a template file as content.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubFileNotFoundException',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::websites::memphis::stubMemphisPageElement',
                      'net::stubbles::websites::memphis::stubMemphisTemplate'
);
/**
 * Page element for including a template file as content.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
class stubMemphisIncludeTemplatePageElement extends stubMemphisPageElement
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
    protected static $tmplPath;

    /**
     * static initializing
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$tmplPath = stubRegistry::getConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, stubConfig::getPagePath() . '/../templates');
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
        if (file_exists(self::$tmplPath . DIRECTORY_SEPARATOR . $source) == false) {
            throw new stubFileNotFoundException(self::$tmplPath . DIRECTORY_SEPARATOR . $source);
        }
        
        $this->source = $source;
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
        
        return array(self::$tmplPath . DIRECTORY_SEPARATOR . $this->source);
    }

    /**
     * processes the page element
     *
     * @return  string
     * @throws  stubRuntimeException
     */
    public function process()
    {
        if (isset($this->context['template']) === false || ($this->context['template'] instanceof stubMemphisTemplate) === false) {
            throw new stubRuntimeException('Context contains no template of instance net::stubbles::websites::memphis::stubMemphisTemplate');
        }
        
        if (null === $this->source) {
            return '';
        }
        
        $this->context['template']->readTemplatesFromInput($this->source);
        return $this->context['template']->getParsedTemplate($this->name);
    }
}
?>