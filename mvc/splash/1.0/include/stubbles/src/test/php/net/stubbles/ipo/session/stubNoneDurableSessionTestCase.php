<?php
/**
 * Tests for net::stubbles::ipo::session::stubNoneDurableSession.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_session_test
 */
stubClassLoader::load('net::stubbles::ipo::session::stubNoneDurableSession');
/**
 * Tests for net::stubbles::ipo::session::stubNoneDurableSession.
 *
 * @package     stubbles
 * @subpackage  ipo_session_test
 * @group       ipo
 * @group       ipo_session
 */
class stubNoneDurableSessionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubNoneDurableSession
     */
    protected $session;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * response instance
     *
     * @var  stubResponse
     */
    protected $response;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest = $this->getMock('stubRequest');
        $this->response    = new stubBaseResponse();
        $this->session     = new stubNoneDurableSession($this->mockRequest, $this->response, 'test');
    }

    /**
     * session id from request should be used
     *
     * @test
     */
    public function useSessionIdFromRequest()
    {
        $this->mockRequest->expects($this->once())
                          ->method('hasValue')
                          ->with($this->equalTo('test'))
                          ->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('313'));
        $session = new stubNoneDurableSession($this->mockRequest, $this->response, 'test');
        $this->assertEquals('313', $session->getId());
        $cookies = $this->response->getCookies();
        $this->assertType('stubCookie', $cookies['test']);
        $this->assertEquals('test', $cookies['test']->getName());
        $this->assertEquals('313', $cookies['test']->getValue());
        $this->assertFalse($session->isNew());
    }

    /**
     * session id from cookie should be used
     *
     * @test
     */
    public function useSessionIdFromCookie()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->will($this->onConsecutiveCalls(false, true));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->will($this->returnValue('313'));
        $session = new stubNoneDurableSession($this->mockRequest, $this->response, 'test');
        $this->assertEquals('313', $session->getId());
        $cookies = $this->response->getCookies();
        $this->assertType('stubCookie', $cookies['test']);
        $this->assertEquals('test', $cookies['test']->getName());
        $this->assertEquals('313', $cookies['test']->getValue());
        $this->assertFalse($session->isNew());
    }

    /**
     * new session id should be generated
     *
     * @test
     */
    public function generateNewSessionId()
    {
        $this->mockRequest->expects($this->exactly(2))
                          ->method('hasValue')
                          ->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())
                          ->method('getValidatedValue');
        $session = new stubNoneDurableSession($this->mockRequest, $this->response, 'test');
        $cookies = $this->response->getCookies();
        $this->assertType('stubCookie', $cookies['test']);
        $this->assertEquals('test', $cookies['test']->getName());
        $this->assertEquals($session->getId(), $cookies['test']->getValue());
        $this->assertTrue($session->isNew());
    }

    /**
     * test that regenerating the id gives a new id for the session
     *
     * @test
     */
    public function regenerateId()
    {
        $id = $this->session->getId();
        $this->session->regenerateId();
        $this->assertNotEquals($id, $this->session->getId());
        $cookies = $this->response->getCookies();
        $this->assertType('stubCookie', $cookies['test']);
        $this->assertEquals('test', $cookies['test']->getName());
        $this->assertNotEquals($id, $cookies['test']->getValue());
        $this->assertEquals($this->session->getId(), $cookies['test']->getValue());
    }

    /**
     * test that invalidating the session makes it invalid
     *
     * @test
     */
    public function invalidate()
    {
        $this->session->invalidate();
        $this->assertFalse($this->session->isValid());
    }

    /**
     * test getting a value from session
     *
     * @test
     */
    public function putGetHasValue()
    {
        $this->assertNull($this->session->getValue('foo'));
        $this->assertEquals('bar', $this->session->getValue('foo', 'bar'));
        $this->assertFalse($this->session->hasValue('foo'));
        $this->session->putValue('foo', 'baz');
        $this->assertTrue($this->session->hasValue('foo'));
        $this->assertEquals('baz', $this->session->getValue('foo'));
        $this->assertEquals('baz', $this->session->getValue('foo', 'bar'));
    }

    /**
     * test removing a value from session
     *
     * @test
     */
    public function removeValue()
    {
        $this->assertFalse($this->session->removeValue('foo'));
        $this->session->putValue('foo', 'baz');
        $this->assertTrue($this->session->hasValue('foo'));
        $this->assertTrue($this->session->removeValue('foo'));
        $this->assertFalse($this->session->hasValue('foo'));
        $this->assertFalse($this->session->removeValue('foo'));
    }

    /**
     * assure the the value keys are returned as expected
     *
     * @test
     */
    public function getValueKeys()
    {
        $this->assertEquals(array(stubSession::START_TIME,
                                  stubSession::FINGERPRINT,
                                  stubSession::NEXT_TOKEN
                            ),
                            $this->session->getValueKeys()
        );
        $this->session->putValue('foo', 'baz');
        $this->assertEquals(array(stubSession::START_TIME,
                                  stubSession::FINGERPRINT,
                                  stubSession::NEXT_TOKEN,
                                 'foo'
                            ),
                            $this->session->getValueKeys()
        );
    }
}
?>