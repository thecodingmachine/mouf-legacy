<?php
/**
 * Base implementation for a xml page element.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::websites::stubAbstractPageElement',
                      'net::stubbles::websites::xml::page::stubXMLPageElement'
);
/**
 * Base implementation for a xml page element.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
abstract class stubAbstractXMLPageElement extends stubAbstractPageElement implements stubXMLPageElement
{
    /**
     * returns a list of form values
     *
     * @return  array<string,string>
     */
    public function getFormValues()
    {
        $data      = array();
        $validator = new stubPassThruValidator();
        foreach ($this->request->getValueKeys() as $key) {
            $data[$key] = $this->request->getValidatedValue($validator, $key);
        }
        
        return $data;
    }
}
?>