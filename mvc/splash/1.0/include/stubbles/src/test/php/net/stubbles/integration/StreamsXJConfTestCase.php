<?php
/**
 * Integration test for configuring streams with XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 * @version     $Id: StreamsXJConfTestCase.php 1826 2008-09-15 20:38:26Z mikey $
 */
stubClassLoader::load('net::stubbles::util::xjconf::xjconf',
                      'net::stubbles::util::xjconf::xjconfReal'
);
/**
 * Integration test for configuring streams with XJConf.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class StreamsXJConfTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method
     *
     * @return  stubXJConfFacade
     */
    public function getXJConf()
    {
        $xjconf = new stubXJConfFacade(new XJConfFacade(array('__default' => stubXJConfLoader::getInstance())));
        $xjconf->addDefinitions(stubFactory::getResourceURIs('xjconf/streams.xml'));
        $xjconf->parse(TEST_SRC_PATH . '/resources/xjconf/streams.xml');
        return $xjconf;
    }

    /**
     * assure that a base64 encoder is created correct
     *
     * @test
     */
    public function instances()
    {
        $xjconf = $this->getXJConf();
        $fileInputStream = $xjconf->getConfigValue('fileInputStream');
        $this->assertType('stubFileInputStream', $fileInputStream);
        $fileOutputStream = $xjconf->getConfigValue('fileOutputStream');
        $this->assertType('stubFileOutputStream', $fileOutputStream);
        $memoryInputStream = $xjconf->getConfigValue('memoryInputStream');
        $this->assertType('stubMemoryInputStream', $memoryInputStream);
        $this->assertEquals('foo', $memoryInputStream->read());
        $memoryOutputStream = $xjconf->getConfigValue('memoryOutputStream');
        $this->assertType('stubMemoryOutputStream', $memoryOutputStream);
        $decodingInputStream = $xjconf->getConfigValue('decodingInputStream');
        $this->assertType('stubDecodingInputStream', $decodingInputStream);
        $this->assertType('stubMemoryInputStream', $decodingInputStream->getEnclosedInputStream());
        $this->assertEquals('utf-8', $decodingInputStream->getCharset());
        $encodingOutputStream = $xjconf->getConfigValue('encodingOutputStream');
        $this->assertType('stubEncodingOutputStream', $encodingOutputStream);
        $this->assertType('stubMemoryOutputStream', $encodingOutputStream->getEnclosedOutputStream());
        $this->assertEquals('utf-8', $encodingOutputStream->getCharset());
        
        // cached
        $xjconf = $this->getXJConf();
        $fileInputStream = $xjconf->getConfigValue('fileInputStream');
        $this->assertType('stubFileInputStream', $fileInputStream);
        $fileOutputStream = $xjconf->getConfigValue('fileOutputStream');
        $this->assertType('stubFileOutputStream', $fileOutputStream);
        $memoryInputStream = $xjconf->getConfigValue('memoryInputStream');
        $this->assertType('stubMemoryInputStream', $memoryInputStream);
        $this->assertEquals('foo', $memoryInputStream->read());
        $memoryOutputStream = $xjconf->getConfigValue('memoryOutputStream');
        $this->assertType('stubMemoryOutputStream', $memoryOutputStream);
        $decodingInputStream = $xjconf->getConfigValue('decodingInputStream');
        $this->assertType('stubDecodingInputStream', $decodingInputStream);
        $this->assertType('stubMemoryInputStream', $decodingInputStream->getEnclosedInputStream());
        $this->assertEquals('utf-8', $decodingInputStream->getCharset());
        $encodingOutputStream = $xjconf->getConfigValue('encodingOutputStream');
        $this->assertType('stubEncodingOutputStream', $encodingOutputStream);
        $this->assertType('stubMemoryOutputStream', $encodingOutputStream->getEnclosedOutputStream());
        $this->assertEquals('utf-8', $encodingOutputStream->getCharset());
    }
}
?>