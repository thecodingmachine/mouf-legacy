<?php
/**
 * Abstract base class for xml page element decorators
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubXMLPageElement');
/**
 * Abstract base class for xml page element decorators
 *
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
abstract class stubXMLPageElementDecorator extends stubBaseObject implements stubXMLPageElement
{
    /**
     * The decorated element
     *
     * @var  stubXMLPageElement
     */
    protected $element;

    /**
     * Create a new decorator
     *
     * @param  stubXMLPageElement  $element
     */
    public function __construct(stubXMLPageElement $element)
    {
        $this->element = $element;
    }

    /**
     * set the name of the element
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->element->setName($name);
    }

    /**
     * returns the name of the element
     *
     * @return  string
     */
    public function getName()
    {
        return $this->element->getName();
    }

    /**
     * returns a list of required class names
     *
     * @return  array<string>
     */
    public function getRequiredClassNames()
    {
        return array($this->element->getClassName());
    }

    /**
     * initializes the page element
     *
     * @param  stubRequest          $request   the request data
     * @param  stubSession          $session   current session
     * @param  stubResponse         $response  contains response data
     * @param  array<string,mixed>  $context   optional  additional context data
     */
    public function init(stubRequest $request, stubSession $session, stubResponse $response, array $context = array())
    {
        $this->element->init($request, $session, $response, $context);
    }

    /**
     * checks whether the page element is available or not
     *
     * @return  bool
     */
    public function isAvailable()
    {
        return $this->element->isAvailable();
    }

    /**
     * checks whether page element is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return $this->element->isCachable();
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return $this->element->getCacheVars();
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return $this->element->getUsedFiles();
    }

    /**
     * processes the page element
     *
     * Please note that an element should catch all exceptions and wrap them
     * into the response!
     *
     * @return  mixed
     */
    public function process()
    {
        return $this->element->process();
    }

    /**
     * returns a list of form values
     *
     * @return  array<string,string>
     */
    public function getFormValues()
    {
        return $this->element->getFormValues();
    }
}
?>