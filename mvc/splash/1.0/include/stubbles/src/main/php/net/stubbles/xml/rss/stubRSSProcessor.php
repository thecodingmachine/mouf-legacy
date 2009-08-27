<?php
/**
 * Processor for rss feeds.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss
 */
stubClassLoader::load('net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::ipo::request::validator::stubPreSelectValidator',
                      'net::stubbles::lang::exceptions::stubFileNotFoundException',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::websites::processors::stubAbstractProcessor',
                      'net::stubbles::xml::stubXMLStreamWriterFactory',
                      'net::stubbles::xml::rss::stubRSSFeed'
);
/**
 * Processor for rss feeds.
 *
 * @package     stubbles
 * @subpackage  xml_rss
 */
class stubRSSProcessor extends stubAbstractProcessor
{
    /**
     * list of configured feeds
     *
     * @var  array<string,string>
     */
    protected $feeds;

    /**
     * constructor
     */
    protected function doConstruct()
    {
        $this->feeds = $this->loadFeeds(stubConfig::getConfigPath() . DIRECTORY_SEPARATOR . 'rss-feeds.ini');
    }

    /**
     * loads the rss feed list from given file
     *
     * @param   string                $configFile
     * @return  array<string,string>
     * @throws  stubFileNotFoundException
     */
    protected function loadFeeds($configFile)
    {
        if (file_exists($configFile) === false) {
            throw new stubFileNotFoundException($configFile);
        }
        
        return parse_ini_file($configFile);
    }

    /**
     * processes the request
     *
     * @throws  stubRuntimeException
     */
    public function process()
    {
        $feed = $this->request->getValidatedValue(new stubPreSelectValidator(array_keys($this->feeds)), 'feed');
        if (null === $feed) {
            // no special feed requested, use first configured one
            reset($this->feeds);
            $feed = key($this->feeds);
        }
        
        $binder = stubBinderRegistry::get();
        $this->response->addHeader('Content-Type', 'text/xml; charset=utf-8');
        $this->response->write($binder->getInjector()
                                      ->getInstance($this->feeds[$feed])
                                      ->create()
                                      ->serialize(stubXMLStreamWriterFactory::createAsAvailable())
                                      ->asXML()
        );
    }
}
?>