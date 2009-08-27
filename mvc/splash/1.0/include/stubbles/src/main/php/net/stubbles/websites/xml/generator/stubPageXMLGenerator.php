<?php
/**
 * Serializes page data into xml result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
stubClassLoader::load('net::stubbles::ioc::stubInjector',
                      'net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::request::stubRequestPrefixDecorator',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::websites::stubPage',
                      'net::stubbles::websites::xml::generator::stubXMLGenerator',
                      'net::stubbles::websites::xml::page::stubXMLPageElement'
);
/**
 * Serializes page data into xml result document.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
class stubPageXMLGenerator extends stubBaseObject implements stubXMLGenerator
{
    /**
     * request instance to be used
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * session instance to be used
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * response instance to be used
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * page instance to be serialized
     *
     * @var  stubPage
     */
    protected $page;
    /**
     * list of available page elements
     *
     * @var  array<string,stubPageElement>
     */
    protected $pageElements = array();
    /**
     * injector instance to be used
     *
     * @var  stubInjector
     */
    protected $injector;
    /**
     * switch whether document part is cachable or not
     *
     * @var  bool
     */
    protected $isCachable   = true;
    /**
     * list of cache variables for this page
     *
     * @var  array<string,scalar>
     */
    protected $cacheVars    = array();
    /**
     * list of used files for this page
     *
     * @var  array<string>
     */
    protected $usedFiles    = array();

    /**
     * constructor
     *
     * @param  stubRequest   $request
     * @param  stubSession   $session
     * @param  stubResponse  $response
     * @param  stubInjector  $injector
     * @Inject
     */
    public function __construct(stubRequest $request, stubSession $session, stubResponse $response, stubInjector $injector)
    {
        $this->request  = new stubRequestPrefixDecorator($request, '');
        $this->session  = $session;
        $this->response = $response;
        $this->injector = $injector;
    }

    /**
     * sets the page
     *
     * @param  stubPage  $page
     * @Inject
     */
    public function setPage(stubPage $page)
    {
        $this->page = $page;
        if (count($this->page->getResources()) > 0) {
            $this->isCachable = false;
        }
        
        foreach ($this->page->getElements() as $name => $element) {
            $this->request->setPrefix($name);
            $this->injector->handleInjections($element);
            $element->init($this->request, $this->session, $this->response);
            if ($element->isAvailable() === true) {
                $this->pageElements[$name] = $element;
                // we can spare this if the page is not cachable
                if (true === $this->isCachable) {
                    if ($element->isCachable() === false) {
                        $this->isCachable = false;
                    } elseif (true === $this->isCachable) {
                        $this->cacheVars = array_merge($this->cacheVars, $element->getCacheVars());
                        $this->usedFiles = array_merge($this->usedFiles, $element->getUsedFiles());
                    }
                }
            }
        }
    }

    /**
     * checks whether document part is cachable or not
     *
     * Document part is cachable if page has no session resources and all page
     * elements are cachable.
     *
     * @return  bool
     */
    public function isCachable()
    {
        return $this->isCachable;
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return $this->cacheVars;
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return $this->usedFiles;
    }

    /**
     * serializes session data into result document
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  writer to be used
     * @param  stubXMLSerializer    $xmlSerializer    serializer to be used
     */
    public function generate(stubXMLStreamWriter $xmlStreamWriter, stubXMLSerializer $xmlSerializer)
    {
        $formValues = array();
        foreach ($this->pageElements as $name => $element) {
            $this->request->setPrefix($name);
            $element->init($this->request, $this->session, $this->response);
            $data = $element->process();
            if ($this->request->isCancelled() === true) {
                return;
            }
            
            $xmlSerializer->serialize($data, $xmlStreamWriter, array(stubXMLSerializer::OPT_ROOT_TAG => $name));
            if ($element instanceof stubXMLPageElement) {
                $formValues[$name] = $element->getFormValues();
            }
        }

        $xmlSerializer->serialize($formValues, $xmlStreamWriter, array(stubXMLSerializer::OPT_ROOT_TAG => 'forms'));
        
        // write resources
        $xmlStreamWriter->writeStartElement('resources');
        foreach ($this->page->getResources() as $name => $interface) {
            $xmlSerializer->serialize($this->injector->getInstance($interface), $xmlStreamWriter, array(stubXMLSerializer::OPT_ROOT_TAG => $name));
        }
        
        $xmlStreamWriter->writeEndElement();
    }
}
?>