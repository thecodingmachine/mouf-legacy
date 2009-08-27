<?php
/**
 * Processor for rasmus pages.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_rasmus
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::processors::stubAbstractProcessor'
);
/**
 * Processor for rasmus pages.
 *
 * @package     stubbles
 * @subpackage  websites_rasmus
 */
class stubRasmusProcessor extends stubAbstractProcessor
{
    /**
     * registry key under which the page path is stored
     */
    const PAGEPATH_REGISTRY_KEY = 'net.stubbles.websites.rasmus.pagePath';
    /**
     * the default path where pages are located
     *
     * @var  string
     */
    protected static $defaultPath;

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$defaultPath = realpath(stubConfig::getConfigPath() . '/../pages');
    }
    // @codeCoverageIgnoreEnd

    /**
     * processes the request
     */
    public function process()
    {
        $this->response->write($this->render($this->getPageName()));
    }

    /**
     * retrieves the page name
     *
     * @return  string
     */
    protected function getPageName()
    {
        if ($this->request->hasValue('page') === true) {
            $pageName = $this->request->getValidatedValue(new stubRegexValidator('/([a-zA-Z0-9_])/'), 'page');
            if (null != $pageName && file_exists(stubRegistry::getConfig(self::PAGEPATH_REGISTRY_KEY, self::$defaultPath) . DIRECTORY_SEPARATOR . $pageName . '.php') === true) {
                return $pageName;
            }
        }

        return 'index';
    }

    /**
     * does the real rendering by including the page file
     *
     * @param   string  $pageName
     * @return  string
     */
    protected function render($pageName)
    {
        ob_start();
        include stubRegistry::getConfig(self::PAGEPATH_REGISTRY_KEY, self::$defaultPath) . DIRECTORY_SEPARATOR . $pageName . '.php';
        return ob_get_clean();
    }
}
?>