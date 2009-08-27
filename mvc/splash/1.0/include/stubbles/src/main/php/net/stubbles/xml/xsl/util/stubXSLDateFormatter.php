<?php
/**
 * Class to transfer the query string into an xml document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
stubClassLoader::load('net::stubbles::lang::types::stubDate',
                      'net::stubbles::xml::stubXMLStreamWriter',
                      'net::stubbles::xml::xsl::util::stubXSLAbstractCallback'
);
/**
 * Class to transfer the query string into an xml document.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
class stubXSLDateFormatter extends stubXSLAbstractCallback
{
    /**
     * returns a formatted date
     *
     * If no timestamp is given the current time will be used.
     * 
     * @param   array<DOMAttr>|string  $format     format for the date string to be returned
     * @param   array<DOMAttr>|string  $timestamp  optional  timestamp to format
     * @return  DOMDocument
     * @XSLMethod
     */
    public function formatDate($format, $timestamp = null)
    {
        $format    = $this->parseValue($format);
        $timestamp = $this->parseValue($timestamp);
        if (null == $timestamp) {
            $timestamp = time();
        }
        
        $date = new stubDate($timestamp);
        $this->xmlStreamWriter->writeElement('date',
                                             array('timestamp' => $timestamp),
                                             $date->format($format)
        );
        return $this->createDomDocument();
    }

    /**
     * returns a formatted date
     *
     * If no timestamp is given the current time will be used.
     * 
     * @param   array<DOMAttr>|string  $format     format for the date string to be returned
     * @param   array<DOMAttr>|string  $timestamp  optional  timestamp to format
     * @return  DOMDocument
     * @XSLMethod
     */
    public function formatLocaleDate($format, $timestamp = null)
    {
        $format    = $this->parseValue($format);
        $timestamp = $this->parseValue($timestamp);
        if (null == $timestamp) {
            $timestamp = time();
        }
        
        $this->xmlStreamWriter->writeElement('date',
                                             array('timestamp' => $timestamp),
                                             strftime($format, $timestamp)
        );
        return $this->createDomDocument();
    }
}
?>