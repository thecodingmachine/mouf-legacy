<?php
/**
 * Default processor delivered by stubbles.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml
 * @version     $Id: stubXMLProcessor.php 1904 2008-10-25 14:04:33Z mikey $
 */
stubClassLoader::load('net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::cache::stubCachableProcessor',
                      'net::stubbles::websites::processors::stubAbstractProcessor',
                      'net::stubbles::websites::processors::stubPageBasedProcessor',
                      'net::stubbles::websites::xml::skin::stubSkinGeneratorFactory',
                      'net::stubbles::xml::stubXMLStreamWriterFactory',
                      'net::stubbles::xml::serializer::stubXMLSerializer',
                      'net::stubbles::xml::xsl::stubXSLProcessorFactory'
);
/**
 * Default processor delivered by stubbles.
 *
 * For a customized version it is recommended to extend this processor. Possible
 * useful methods to be overwritten in a customized version:
 * - protected function getXMLGenerators()
 *   This method should return a list of full qualified class names denoting
 *   xml generator implementations. All these will be used to generate the
 *   result xml document.
 * - protected function create*()
 *   These methods return instances that are used to create the result xml
 *   document.
 *
 * @package     stubbles
 * @subpackage  websites_xml
 */
class stubXMLProcessor extends stubAbstractProcessor implements stubPageBasedProcessor, stubCachableProcessor
{
    /**
     * registry key for switch whether to serialize the current mode or not
     */
    const SERIALIZE_MODE_REGISTRY_KEY = 'net.stubbles.websites.xml.serializeMode';
    /**
     * list of xml generators to be used to create the dom tree
     *
     * @var  array<stubXMLGenerator>
     */
    protected $xmlGenerators          = array();
    /**
     * page to display
     *
     * @var  stubPage
     */
    protected $page;
    /**
     * name of page to display
     *
     * @var  string
     */
    protected $pageName;

    /**
     * selects the page to display with help of the page factory
     *
     * @param  stubPageFactory  $pageFactory
     */
    public function selectPage(stubPageFactory $pageFactory)
    {
        $pageFactory->setPagePrefix('conf/');
        $this->pageName = $pageFactory->getPageName($this->request);
        $this->page     = $pageFactory->getPage($this->pageName);
        $this->session->putValue('net.stubbles.websites.lastPage', $this->pageName);
    }

    /**
     * helper method to initialize the xml generator instances
     */
    protected function initializeGenerators()
    {
        if (count($this->xmlGenerators) > 0) {
            return;
        }
        
        $binder = stubBinderRegistry::get();
        $binder->bind('stubPage')->toInstance($this->page);
        $injector = $binder->getInjector();
        foreach ($this->getXMLGenerators() as $xmlGeneratorClassName) {
            $this->xmlGenerators[] = $injector->getInstance($xmlGeneratorClassName);
        }
    }

    /**
     * configure the xml generators
     *
     * @return  array<string>
     */
    protected function getXMLGenerators()
    {
        $generators = array('net::stubbles::websites::xml::generator::stubSessionXMLGenerator',
                            'net::stubbles::websites::xml::generator::stubPageXMLGenerator',
                            'net::stubbles::websites::xml::generator::stubRequestXMLGenerator'
                      );
        if (stubRegistry::getConfig(self::SERIALIZE_MODE_REGISTRY_KEY, false) !== false) {
            $generators[] = 'net::stubbles::websites::xml::generator::stubModeXMLGenerator';
        }
        
        return $generators;
    }

    /**
     * adds the cache variables for the current request and returns whether
     * response is cachable or not
     *
     * @param   stubWebsiteCache  $cache
     * @return  bool
     */
    public function addCacheVars(stubWebsiteCache $cache)
    {
        $this->initializeGenerators();
        foreach ($this->xmlGenerators as $xmlGenerator) {
            if ($xmlGenerator->isCachable() === false) {
                return false;
            }
            
            $cache->addCacheVars($xmlGenerator->getCacheVars());
            $cache->addUsedFiles($xmlGenerator->getUsedFiles());
        }
        
        $cache->addCacheVar('page', $this->pageName);
        return true;
    }

    /**
     * returns the name of the current page
     *
     * Non-page-based processors should return another unique identifier for
     * the current request if they want to implement this interface.
     *
     * @return  string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * processes the request
     */
    public function process()
    {
        $this->initializeGenerators();
        $xmlStreamWriter = $this->createXMLStreamWriter();
        $xmlStreamWriter->writeStartElement('document');
        $xmlStreamWriter->writeAttribute('page', $this->pageName);
        $xmlSerializer = $this->createXMLSerializer();
        foreach ($this->xmlGenerators as $xmlGenerator) {
            $xmlGenerator->generate($xmlStreamWriter, $xmlSerializer);
            if ($this->request->isCancelled() === true) {
                return;
            }
        }

        $xmlStreamWriter->writeEndElement(); // end document
        $this->session->putValue('net.stubbles.websites.lastRequestResponseData', $xmlStreamWriter->asXML());
        $skinGenerator = $this->createSkinGenerator();
        $this->response->replaceData(str_replace(' xmlns=""',
                                                 '',
                                                 preg_replace('/ xml:base="(.*)"/U',
                                                              '',
                                                              $this->createXSLProcessor()
                                                                   ->andApplyStylesheet($skinGenerator->generate($this->session, $this->page, $this->getSkinName($skinGenerator)))
                                                                   ->onDocument($xmlStreamWriter->asDOM())
                                                                   ->toXML()
                                                 )
                                     )
        );
    }

    /**
     * detects the skin to be used
     *
     * @param   stubSkinGenerator  $skinGenerator
     * @return  string
     */
    protected function getSkinName(stubSkinGenerator $skinGenerator)
    {
        $skin = null;
        if ($this->request->hasValue('frame') === true) {
            $skin = $this->request->getValidatedValue(new stubPassthruValidator(), 'frame');
        } elseif ($this->page->hasProperty('skin') === true) {
            $skin = $this->page->getProperty('skin');
        }
        
        if (null == $skin || (null != $skin && $skinGenerator->hasSkin($skin) === false)) {
            $skin = 'default';
        }
        
        return $skin;
    }

    /**
     * returns a xml stream writer
     *
     * @return  stubXMLStreamWriter
     */
    // @codeCoverageIgnoreStart
    protected function createXMLStreamWriter()
    {
        return stubXMLStreamWriterFactory::createAsAvailable();
    }
    // @codeCoverageIgnoreEnd

    /**
     * returns the xml serializer
     *
     * @return  stubXMLSerializer
     */
    // @codeCoverageIgnoreStart
    protected function createXMLSerializer()
    {
        return new stubXMLSerializer();
    }
    // @codeCoverageIgnoreEnd

    /**
     * creates a new skin generator instance
     *
     * @return  stubSkinGenerator
     */
    // @codeCoverageIgnoreStart
    protected function createSkinGenerator()
    {
        $factory = new stubSkinGeneratorFactory();
        return $factory->create();
    }
    // @codeCoverageIgnoreEnd

    /**
     * creates a stubXSLProcessor instance
     *
     * @return  stubXSLProcessor
     */
    // @codeCoverageIgnoreStart
    protected function createXSLProcessor()
    {
        return stubXSLProcessorFactory::createWithCallbacks();
    }
    // @codeCoverageIgnoreEnd
}
?>