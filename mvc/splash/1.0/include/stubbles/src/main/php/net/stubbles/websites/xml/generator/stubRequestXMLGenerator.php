<?php
/**
 * Serializes request data into xml result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::websites::xml::generator::stubXMLGenerator'
);
/**
 * Serializes request data into xml result document.
 *
 * Currently this are only the request errors created during processing of the
 * page elements:
 * <code>
 * <document>
 *   [...]
 *   <request>
 *     <errors>
 *       <error id="foo">
 *         <messages>
 *           <de_DE>Dies ist eine deutsche Fehlermeldung.</de_DE>
 *           <en_EN>This is an english error message.</en_EN>
 *         </messages>
 *       </error>
 *       [...]
 *     </errors>
 *   </request>
 *   [...]
 * </document>
 * </code>
 * Concrete request values will not be written into the result document.
 *
 * The serializing of the request should take place after page elements were
 * processed - only these generate the request value errors stored in the
 * request. Additionally those page elements should take care of whether a page
 * is cachable or not and the required cache variables.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
class stubRequestXMLGenerator extends stubBaseObject implements stubXMLGenerator
{
    /**
     * request instance to be used
     *
     * @var  stubRequest
     */
    protected $request;

    /**
     * constructor
     *
     * @param  stubRequest  $request
     * @Inject
     */
    public function __construct(stubRequest $request)
    {
        $this->request = $request;
    }

    /**
     * checks whether document part is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return true;
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
     * serializes request data into result document
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  writer to be used
     * @param  stubXMLSerializer    $xmlSerializer    serializer to be used
     */
    public function generate(stubXMLStreamWriter $xmlStreamWriter, stubXMLSerializer $xmlSerializer)
    {
        $xmlStreamWriter->writeStartElement('request');
        $errors = $this->request->getValueErrors();
        foreach ($errors as $requestValueName => $requestErrorValues) {
            $xmlStreamWriter->writeStartElement('value');
            $xmlStreamWriter->writeAttribute('name', $requestValueName);
            $xmlSerializer->serialize(array_values($requestErrorValues), $xmlStreamWriter, array(stubXMLSerializer::OPT_ROOT_TAG => 'errors'));
            $xmlStreamWriter->writeEndElement();
        }

        $xmlStreamWriter->writeEndElement();  // end request
    }
}
?>