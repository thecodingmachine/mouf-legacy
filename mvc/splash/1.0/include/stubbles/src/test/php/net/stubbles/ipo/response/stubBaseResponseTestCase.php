<?php
/**
 * Tests for net::stubbles::ipo::response::stubBaseResponse.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_response_test
 */
stubClassLoader::load('net::stubbles::ipo::response::stubBaseResponse');
/**
 * Tests for net::stubbles::ipo::response::stubBaseResponse.
 *
 * @package     stubbles
 * @subpackage  ipo_response_test
 * @group       ipo
 * @group       ipo_response
 */
class stubBaseResponseTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubBaseResponse
     */
    protected $response;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->response = $this->getMock('stubBaseResponse', array('header', 'sendData'));
    }

    /**
     * version property should be handled correct
     *
     * @test
     */
    public function versionProperty()
    {
        $this->assertEquals('1.1', $this->response->getVersion());
        $this->response->setVersion('1.0');
        $this->assertEquals('1.0', $this->response->getVersion());
        $this->response->clear();
        $this->assertEquals('1.0', $this->response->getVersion());

        $response = new stubBaseResponse('1.0');
        $this->assertEquals('1.0', $response->getVersion());
    }

    /**
     * no status code: send no header
     *
     * @test
     */
    public function noStatusCode()
    {
        $this->assertNull($this->response->getStatusCode());
        $this->response->expects($this->never())->method('header');
        $this->response->send();
    }

    /**
     * status code header in cgi sapi differs from other sapis
     *
     * @test
     */
    public function statusCodeInCgiSapi()
    {
        $this->response = $this->getMock('stubBaseResponse', array('header', 'sendData'), array('1.1', 'cgi'));
        $this->assertNull($this->response->getStatusCode());
        $this->response->setStatusCode(404, 'Not Found');
        $this->response->expects($this->once())->method('header')->with($this->equalTo('Status: 404 Not Found'));
        $this->response->send();
        $this->response->clear();
        $this->assertNull($this->response->getStatusCode());
    }

    /**
     * status code header in every other sapi
     *
     * @test
     */
    public function statusCodeInOtherSapi()
    {
        $this->assertNull($this->response->getStatusCode());
        $this->response->setStatusCode(404, 'Not Found');
        $this->response->expects($this->once())->method('header')->with($this->equalTo('HTTP/1.1 404 Not Found'));
        $this->response->send();
        $this->response->clear();
        $this->assertNull($this->response->getStatusCode());
    }

    /**
     * headers should be send as expected
     *
     * @test
     */
    public function headersAreSend()
    {
        $this->assertEquals(array(), $this->response->getHeaders());
        $this->response->addHeader('name', 'value1');
        $this->response->addHeader('name', 'value2');
        $this->assertEquals(array('name' => 'value2'), $this->response->getHeaders());
        $this->response->expects($this->once())->method('header')->with($this->equalTo('name: value2'));
        $this->response->send();
        $this->response->clear();
        $this->assertEquals(array(), $this->response->getHeaders());
    }

    /**
     * cookies should be send as expected
     *
     * @test
     */
    public function cookiesAreSend()
    {
        $this->assertEquals(array(), $this->response->getCookies());
        $mockCookie = $this->getMock('stubCookie', array(), array('foo', 'bar'));
        $mockCookie->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $this->response->setCookie($mockCookie);
        $this->response->setCookie($mockCookie);
        $this->assertEquals(array('foo' => $mockCookie), $this->response->getCookies());
        $mockCookie->expects($this->once())->method('send');
        $this->response->send();
        $this->response->clear();
        $this->assertEquals(array(), $this->response->getCookies());
    }

    /**
     * data handling should work as well
     *
     * @test
     */
    public function dataHandling()
    {
        $this->assertNull($this->response->getData());
        $this->response->write('foo');
        $this->assertEquals('foo', $this->response->getData());
        $this->response->replaceData('bar');
        $this->assertEquals('bar', $this->response->getData());
        $this->response->expects($this->once())->method('sendData')->with($this->equalTo('bar'));
        $this->response->send();
        $this->response->clear();
        $this->assertNull($this->response->getData());
        $this->response->send();
    }

    /**
     * data handling should work as well
     *
     * @test
     */
    public function dataHandlingWithDataCleared()
    {
        $this->assertNull($this->response->getData());
        $this->response->write('foo');
        $this->assertEquals('foo', $this->response->getData());
        $this->response->clearData(null);
        $this->assertNull($this->response->getData());
        $this->response->expects($this->never())->method('sendData');
        $this->response->send();
    }
}
?>