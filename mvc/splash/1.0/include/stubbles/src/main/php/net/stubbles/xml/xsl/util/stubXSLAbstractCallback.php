<?php
/**
 * Class with helper methods for callbacks.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
stubClassLoader::load('net::stubbles::xml::stubXMLStreamWriter');
/**
 * Class with helper methods for callbacks.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
abstract class stubXSLAbstractCallback extends stubBaseObject
{
    /**
     * the stream writer to use
     *
     * @var  stubXMLStreamWriter
     */
    protected $xmlStreamWriter;

    /**
     * constructor
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  xml stream writer to create the document with
     * @Inject
     */
    public function __construct(stubXMLStreamWriter $xmlStreamWriter)
    {
        $this->xmlStreamWriter = $xmlStreamWriter;
    }

    /**
     * parses a value and returns the real value
     *
     * When called from within an xsl stylesheet the given param is often an
     * array with one DOMAttr instance in it. This helper method will return the
     * real value.
     *
     * @param   array<DOMAttr>|string  $value
     * @return  string
     */
    protected function parseValue($value)
    {
        if (is_array($value) == true) {
            return $value[0]->value;
        }

        return $value;
    }

    /**
     * creates DOMDocument and dumps stream writer data from memory
     *
     * @return  DOMDocument
     */
    protected function createDomDocument()
    {
        $doc = $this->xmlStreamWriter->asDom();
        $this->xmlStreamWriter->clear();
        return $doc;
    }
}
?>