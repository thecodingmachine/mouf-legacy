<?php
/**
 * Example for the XMLSerializer package
 *
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @link    http://www.stubbles.net/wiki/Docs/XMLStreamWriter
 * @uses    stubXMLStreamWriterFactory
 * @uses    stubXMLStreamWriter
 */
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::xml::stubXMLStreamWriterFactory');

// get a stubXMLStreamWriter instance regardless of the implementation
$writer = stubXMLStreamWriterFactory::createAsAvailable();

$writer->writeStartElement('Team');
$writer->writeAttribute('name', 'JLA');
$writer->writeStartElement('hero');
$writer->writeStartElement('name');
$writer->writeText('Superman');
$writer->writeEndElement();
$writer->writeEndElement();

$writer->writeStartElement('hero');
$writer->writeStartElement('name');
$writer->writeText('Wonder Woman');
$writer->writeEndElement();
$writer->writeEndElement();

$writer->writeXmlFragment('<hero><name>Batman</name></hero>');

$writer->writeEndElement();

header('Content-Type: text/xml');
echo $writer->asXML();
?>