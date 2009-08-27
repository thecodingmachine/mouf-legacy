<?php
/**
 * Test for net::stubbles::streams::file::stubFileOutputStream.
 *
 * @author      Frank Kleine mikey@stubbles.net
 * @package     stubbles
 * @subpackage  streams_test
 */
stubClassLoader::load('net::stubbles::streams::file::stubFileOutputStream',
                      'net::stubbles::streams::memory::stubMemoryStreamWrapper'
);
@include_once 'vfsStream/vfsStream.php';
/**
 * Test for net::stubbles::streams::file::stubFileOutputStream.
 *
 * @package     stubbles
 * @subpackage  streams_test
 * @group       streams
 */
class stubFileOutputStreamTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * the file url used in the tests
     *
     * @var  string
     */
    protected $fileURL;

    /**
     * set up test environment
     */
    public function setUp()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('Requires vfsStream, see http://vfs.bovigo.org/');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(vfsStream::newDirectory('home'));
        $this->fileURL = vfsStream::url('home/test.txt');
    }

    /**
     * construct with string as argument
     *
     * @test
     */
    public function constructWithString()
    {
        $this->assertFalse(file_exists($this->fileURL));
        $fileOutputStream = new stubFileOutputStream($this->fileURL);
        $this->assertTrue(file_exists($this->fileURL));
        $fileOutputStream->write('foo');
        $this->assertEquals('foo', file_get_contents($this->fileURL));
    }

    /**
     * construct with string as argument fails throws io exception
     *
     * @test
     * @expectedException  stubIOException
     */
    public function constructWithStringFailsAndThrowsIOException()
    {
        $fileOutputStream = new stubFileOutputStream('memory://doesNotExist', 'r');
    }

    /**
     * construct with resource as argument
     *
     * @test
     */
    public function constructWithResource()
    {
        $this->assertFalse(file_exists($this->fileURL));
        $fileOutputStream = new stubFileOutputStream(fopen($this->fileURL, 'wb'));
        $this->assertTrue(file_exists($this->fileURL));
        $fileOutputStream->write('foo');
        $this->assertEquals('foo', file_get_contents($this->fileURL));
    }

    /**
     * construct with an illegal resource as argument
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function constructWithIllegalResource()
    {
        $fileOutputStream = new stubFileOutputStream(imagecreate(2, 2));
    }

    /**
     * construct with an illegal argument
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function constructWithIllegalArgument()
    {
        $fileOutputStream = new stubFileOutputStream(0);
    }
}
?>