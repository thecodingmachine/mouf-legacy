<?php
/**
 * Interface for a xml page element.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
stubClassLoader::load('net::stubbles::websites::stubPageElement');
/**
 * Interface for a xml page element.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
interface stubXMLPageElement extends stubPageElement
{
    /**
     * returns a list of form values
     *
     * @return  array<string,string>
     */
    public function getFormValues();
}
?>