<?php
/**
 * Tests for net::stubbles::ipo::session::stubPHPSession.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_session_test
 */
stubClassLoader::load('net::stubbles::ipo::session::stubPHPSession');
/**
 * Tests for net::stubbles::ipo::session::stubPHPSession.
 *
 * @package     stubbles
 * @subpackage  ipo_session_test
 * @group       ipo
 * @group       ipo_session
 */
class stubPHPSessionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubTestPHPSession
     */
    protected $session;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest = $this->getMock('stubRequest');
        $this->mockRequest->expects($this->atLeastOnce())->method('getValidatedValue')->will($this->returnValue('foobarbaz'));
        $_SESSION      = array();
        $this->session = new stubPHPSession($this->mockRequest, $this->getMock('stubResponse'), 'test');
    }

    /**
     * test that regenerating the id gives a new id for the session
     *
     * @test
     */
    public function regenerateId()
    {
        $id = $this->session->getId();
        $this->assertEquals($id, $this->session->getId());
        $file = null;
        $line = null;
        if (headers_sent($file, $line) === true) {
            $this->markTestSkipped('Headers already send in ' . $file . ' on line ' . $line . ', skipped stubPHPSessionTestCase::testRegenerateId()');
        }
        
        $this->session->regenerateId();
        $this->assertNotEquals($id, $this->session->getId());
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