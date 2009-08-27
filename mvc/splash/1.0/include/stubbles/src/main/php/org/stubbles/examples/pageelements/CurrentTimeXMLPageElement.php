<?php
/**
 * Example class for a xml page element.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageElement',
                      'net::stubbles::websites::xml::page::stubXMLPageElement'
);
/**
 * Example class for a xml page element.
 *
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
class CurrentTimeXMLPageElement extends stubAbstractPageElement implements stubXMLPageElement
{
    /**
     * processes the page element
     *
     * @return  mixed
     */
    public function process()
    {
        return array('currentTime' => date('Y-m-d H:i:s', time()));
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