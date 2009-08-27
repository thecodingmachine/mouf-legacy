<?php
/**
 * Test for net::stubbles::peer::stubSocket.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_test
 */
stubClassLoader::load('net::stubbles::peer::stubSocket');
/**
 * Test for net::stubbles::peer::stubSocket.
 *
 * @package     stubbles
 * @subpackage  peer_test
 * @group       peer
 */
class stubSocketTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function values()
    {
        $socket = new stubSocket('example.com');
        $this->assertEquals('example.com', $socket->getHost());
        $this->assertEquals(80, $socket->getPort());
        $this->assertEquals(30, $socket->getTimeout());
        $socket->setTimeout(60);
        $this->assertEquals(60, $socket->getTimeout());
        $this->assertFalse($socket->isConnected());
        $this->assertTrue($socket->eof());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valuesWithSocket()
    {
        $socket = new stubSocket('example.com', 21);
        $this->assertEquals('example.com', $socket->getHost());
        $this->assertEquals(21, $socket->getPort());
        $this->assertEquals(30, $socket->getTimeout());
        $socket->setTimeout(60);
        $this->assertEquals(60, $socket->getTimeout());
        $this->assertFalse($socket->isConnected());
        $this->assertTrue($socket->eof());
    }

    /**
     * assure that trying to read on an unconnected socket throws an IllegalStateException
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function readOnUnconnected()
    {    
        $socket = new stubSocket('example.com');
        $data = $socket->read();
    }

    /**
     * assure that trying to read on an unconnected socket throws an IllegalStateException
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function readLineOnUnconnected()
    {
        $socket = new stubSocket('example.com');
        $data = $socket->readLine();
    }

    /**
     * assure that trying to write on an unconnected socket throws an IllegalStateException
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function writeOnUnconnected()
    {
        $socket = new stubSocket('example.com');
        $data = $socket->write('data');
    }
}
?>