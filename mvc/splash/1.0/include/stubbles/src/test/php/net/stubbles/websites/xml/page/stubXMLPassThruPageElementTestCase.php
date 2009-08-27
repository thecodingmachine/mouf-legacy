<?php
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPassThruPageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubXMLPassThruPageElement',
                      'net::stubbles::xml::serializer::annotations::stubXMLFragmentAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLMethodsAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLTagAnnotation'
);
@include_once 'vfsStream/vfsStream.php';
/**
 * Tests for net::stubbles::websites::xml::page::stubXMLPassThruPageElement.
 *
 * @package     stubbles
 * @subpackage  websites_xml_page_test
 * @group       websites
 * @group       websites_xml
 */
class stubXMLPassThruPageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXMLPassThruPageElement
     */
    protected $xmlPassThruPageElement;
    /**
     * mocked decorated page element
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockXMLPageElement;

    /**
     * set up test environment
     */
    public function setUp()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('stubFileCacheContainerTestCase requires vfsStream, see http://vfs.bovigo.org/.');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('directory'));
        vfsStream::newFile('file.name')->withContent('<foo>bar</foo>')->at(vfsStreamWrapper::getRoot());
        $this->xmlPassThruPageElement = new stubXMLPassThruPageElement();
        $this->xmlPassThruPageElement->setDirectory(vfsStream::url('directory'));
        $this->xmlPassThruPageElement->setFileName('file.name');
    }

    /**
     * ensure that annotations can be read
     *
     * @test
     */
    public function annotationsAreCorrect()
    {
        $this->assertTrue($this->xmlPassThruPageElement->getClass()->hasAnnotation('XMLTag'));
        $this->assertTrue($this->xmlPassThruPageElement->getClass()->hasAnnotation('XMLMethods'));
        $this->assertTrue($this->xmlPassThruPageElement->getClass()->getMethod('getContents')->hasAnnotation('XMLFragment'));
    }

    /**
     * content of the file should be returned
     *
     * @test
     */
    public function contents()
    {
        $this->assertEquals('<foo>bar</foo>', $this->xmlPassThruPageElement->getContents());
        $this->xmlPassThruPageElement->setFileName('file.doesNotExist');
        $this->assertEquals('<error>The file ' . vfsStream::url('directory/file.doesNotExist') . ' does not exist.</error>', $this->xmlPassThruPageElement->getContents());
    }

    /**
     * cache variable is the file name
     *
     * @test
     */
    public function cacheVars()
    {
        $this->assertEquals(array('filename' => vfsStream::url('directory/file.name')), $this->xmlPassThruPageElement->getCacheVars());
    }

    /**
     * used files return the file name
     *
     * @test
     */
    public function usedFiles()
    {
        $this->assertEquals(array(vfsStream::url('directory/file.name')), $this->xmlPassThruPageElement->getUsedFiles());
    }

    /**
     * page element returns itself on process
     *
     * @test
     */
    public function processReturnsItself()
    {
        $this->assertSame($this->xmlPassThruPageElement, $this->xmlPassThruPageElement->process());
    }
    /**
     * page element has no form values
     *
     * @test
     */
    public function decoratedPageElementsFormValuesShouldBeReturned()
    {
        $this->assertEquals(array(), $this->xmlPassThruPageElement->getFormValues());
    }
}
?>