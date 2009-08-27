<?php
/**
 * Interface for generators of the xml result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
stubClassLoader::load('net::stubbles::xml::stubXMLStreamWriter',
                      'net::stubbles::xml::serializer::stubXMLSerializer'
);
/**
 * Interface for generators of the xml result document.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
interface stubXMLGenerator extends stubObject
{
    /**
     * checks whether document part is cachable or not
     *
     * @return  bool
     */
    public function isCachable();

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars();

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles();

    /**
     * serializes something
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  writer to be used
     * @param  stubXMLSerializer    $xmlSerializer    serializer to be used
     */
    public function generate(stubXMLStreamWriter $xmlStreamWriter, stubXMLSerializer $xmlSerializer);
}
?>