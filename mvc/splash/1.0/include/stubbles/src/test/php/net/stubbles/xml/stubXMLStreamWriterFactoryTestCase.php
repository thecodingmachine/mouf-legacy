<?php
/**
 * Test for net::stubbles::xml::stubXMLStreamWriterFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 */
stubClassLoader::load('net::stubbles::xml::stubXMLStreamWriterFactory',
                      'net::stubbles::xml::stubXMLStreamWriter'
);
/**
 * Test for net::stubbles::xml::stubXMLStreamWriterFactory.
 *
 * @package     stubbles
 * @subpackage  xml_test
 * @group       xml
 */
class stubXMLStreamWriterFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test the creation of a xml stream writer
     *
     * @test
     */
    public function create()
    {
        $this->assertType('stubDomXMLStreamWriter', stubXMLStreamWriterFactory::create('Dom'));
    }

    /**
     * test the creation of a xml stream writer with given order of extensions
     *
     * @test
     */
    public function createAsAvailable()
    {
        $this->assertType('stubDomXMLStreamWriter', stubXMLStreamWriterFactory::createAsAvailable());
        $this->assertType('stubLibXmlXMLStreamWriter', stubXMLStreamWriterFactory::createAsAvailable(array('xmlwriter', 'Dom')));
        $this->assertType('stubDomXMLStreamWriter', stubXMLStreamWriterFactory::createAsAvailable(array('xmlwriter', 'Dom'), array(stubXMLStreamWriter::FEATURE_IMPORT_WRITER)));
    }

    /**
     * test the creation of a xml stream writer with given order of extensions
     *
     * @test
     * @expectedException  stubXMLException
     */
    public function createNonAvailable()
    {
        stubXMLStreamWriterFactory::createAsAvailable(array('ExtensionDoesNotExist'));
    }

    /**
     * test the finding class name of a xml stream writer with given order of extensions
     *
     * @test
     */
    public function getFqClassNameAsAvailable()
    {
        $this->assertEquals('net::stubbles::xml::stubDomXMLStreamWriter', stubXMLStreamWriterFactory::getFqClassNameAsAvailable());
        $this->assertEquals('net::stubbles::xml::stubLibXmlXMLStreamWriter', stubXMLStreamWriterFactory::getFqClassNameAsAvailable(array('xmlwriter', 'Dom')));
        $this->assertEquals('net::stubbles::xml::stubDomXMLStreamWriter', stubXMLStreamWriterFactory::getFqClassNameAsAvailable(array('xmlwriter', 'Dom'), array(stubXMLStreamWriter::FEATURE_IMPORT_WRITER)));
    }

    /**
     * test the creation of a xml stream writer with given order of extensions
     *
     * @test
     * @expectedException  stubXMLException
     */
    public function getFqClassNameNonAvailable()
    {
        stubXMLStreamWriterFactory::getFqClassNameAsAvailable(array('ExtensionDoesNotExist'));
    }

    /**
     * test set a different encoding is set correctly
     *
     * @test
     */
    public function setVersion()
    {
        $writer = stubXMLStreamWriterFactory::create('Dom');
        $this->assertEquals('1.0', $writer->getVersion());
        stubXMLStreamWriterFactory::setVersion('1.1');
        $writer = stubXMLStreamWriterFactory::create('Dom');
        $this->assertEquals('1.1', $writer->getVersion());
    }

    /**
     * test set a different version is set correctly
     *
     * @test
     */
    public function setEncoding()
    {
        $writer = stubXMLStreamWriterFactory::create('Dom');
        $this->assertEquals('UTF-8', $writer->getEncoding());
        stubXMLStreamWriterFactory::setEncoding('ISO-8859-1');
        $writer = stubXMLStreamWriterFactory::create('Dom');
        $this->assertEquals('ISO-8859-1', $writer->getEncoding());
    }
}
?>