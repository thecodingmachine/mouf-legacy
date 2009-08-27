<?php
/**
 * Serializes current mode into xml result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::websites::xml::generator::stubXMLGenerator'
);
/**
 * Serializes current mode into xml result document.
 *
 * Default values are whether the session is new or not, the current and
 * the next token of the request:
 * <code>
 * <document>
 *   [...]
 *   <mode>
 *     <name>DEV</name>
 *     <isCacheEnabled>true</isCacheEnabled>
 *   </mode>
 *   [...]
 * </document>
 * </code>
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
class stubModeXMLGenerator extends stubBaseObject implements stubXMLGenerator
{
    /**
     * checks whether document part is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return stubMode::$CURRENT->isCacheEnabled();
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return array();
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return array();
    }

    /**
     * serializes session data into result document
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  writer to be used
     * @param  stubXMLSerializer    $xmlSerializer    serializer to be used
     */
    public function generate(stubXMLStreamWriter $xmlStreamWriter, stubXMLSerializer $xmlSerializer)
    {
        $xmlStreamWriter->writeStartElement('mode');
        $xmlStreamWriter->writeElement('name', array(), stubMode::$CURRENT->name());
        if (stubMode::$CURRENT->isCacheEnabled() === true) {
            $xmlStreamWriter->writeElement('isCacheEnabled', array(), 'true');
        } else {
            $xmlStreamWriter->writeElement('isCacheEnabled', array(), 'false');
        }
        
        $xmlStreamWriter->writeEndElement();  // end mode
    }
}
?>