<?php
/**
 * Example class for a xml page element.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageElement',
                      'net::stubbles::websites::xml::page::stubXMLPageElement',
                      'org::stubbles::examples::resources::MyResource'
);
/**
 * Example class for a xml page element.
 *
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
class TestElementWithInjectedResource extends stubAbstractPageElement implements stubXMLPageElement
{
    /**
     * A resource that will be injected
     *
     * @var Myresource
     */
    private $resource;

    /**
     * Set the resource.
     *
     * This will be done automatically via dependency injection.
     *
     * @Inject
     * @param MyResource $resource
     */
    public function setMyResource(MyResource $resource) {
        $this->resource = $resource;
    }

    /**
     * processes the page element
     *
     * @return  mixed
     */
    public function process()
    {
        $this->resource->incrementCount();
        return array();
    }

    /**
     * returns a list of form values
     *
     * @return  array<string,string>
     */
    public function getFormValues()
    {
        return array();
    }
}
?>