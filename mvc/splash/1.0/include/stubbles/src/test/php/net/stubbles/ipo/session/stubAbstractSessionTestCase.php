<?php
/**
 * Tests for net::stubbles::ipo::session::stubAbstractSession.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_session_test
 */
stubClassLoader::load('net::stubbles::ipo::session::stubAbstractSession');
class stubTestSession extends stubAbstractSession
{
    protected $id   = 'test';
    protected $data = array();
    
    protected function doConstruct(stubRequest $request, stubResponse $response, $sessionName)
    {
        if (strlen($sessionName) > 1 && 'foo' != $sessionName) {
            $data = explode('|', $sessionName);
            if (strlen($data[0]) > 0) {
                $this->data[stubSession::START_TIME]  = $data[0];
            }
            if (isset($data[1]) == true && strlen($data[1]) > 0) {
                $this->data[stubSession::FINGERPRINT] = $data[1];
            }
            if (isset($data[2]) == true && strlen($data[2]) > 0) {
                $this->data[stubSession::NEXT_TOKEN]  = $data[2];
            }
        }
        
        return true;
    }
    
    protected function getFingerprint() { return 'foobarbaz'; }
    
    public function getId() { return $this->id; }

    public function regenerateId() { $this->id = 'foo'; }

    public function invalidate() { $this->data = array(); }
    
    public function inject($key, $value) { $this->data[$key] = $value; }

    protected function doPutValue($key, $value) { $this->data[$key] = $value; }
    
    public function hasValue($key) { return isset($this->data[$key]); }
    
    protected function doGetValue($key) { return $this->data[$key]; }
    
    protected function doRemoveValue($key) { unset($this->data[$key]); return true; }
    
    protected function doGetValueKeys() { return array_keys($this->data); }
}
/**
 * Tests for net::stubbles::ipo::session::stubAbstractSession.
 *
 * @package     stubbles
 * @subpackage  ipo_session_test
 * @group       ipo
 * @group       ipo_session
 */
class stubAbstractSessionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubTestSession
     */
    protected $session;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->session = new stubTestSession($this->getMock('stubRequest'), $this->getMock('stubResponse'), 'foo');
    }

    /**
     * test with default values
     *
     * @test
     */
    public function defaultValues()
    {
        $this->assertEquals('foo', $this->session->getName());
        $this->assertTrue($this->session->hasValue(stubSession::START_TIME));
        $startTime = $this->session->getStartTime();
        $this->assertEquals('foobarbaz', $this->session->getValue(stubSession::FINGERPRINT));
        $this->assertTrue($this->session->hasValue(stubSession::NEXT_TOKEN));
        $this->assertTrue($this->session->isNew());
        $this->assertTrue($this->session->isValid());
        $nextToken = $this->session->getNextToken();
        
        $this->session = new stubTestSession($this->getMock('stubRequest'), $this->getMock('stubResponse'), $startTime . '|foobarbaz|' . $nextToken);
        $this->assertEquals($startTime, $this->session->getStartTime());
        $this->assertFalse($this->session->isNew());
        $this->assertEquals($nextToken, $this->session->getCurrentToken());
        $this->assertTrue($this->session->isValid());
    }
    
    /**
     * test that an invalid sesssion throws an exception
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function invalidGetStartTime()
    {
        $this->session->invalidate();
        $this->assertFalse($this->session->isValid());
        $this->session->getStartTime();
    }
    
    /**
     * test that an invalid sesssion throws an exception
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function invalidGetValue()
    {
        $this->session->invalidate();
        $this->assertFalse($this->session->isValid());
        $this->session->getValue('foo');
    }
    
    /**
     * test that an invalid sesssion throws an exception
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function invalidRemoveValue()
    {
        $this->session->invalidate();
        $this->assertFalse($this->session->isValid());
        $this->session->removeValue('foo');
    }
    
    /**
     * test that an invalid sesssion throws an exception
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function invalidGetValueKeys()
    {
        $this->session->invalidate();
        $this->assertFalse($this->session->isValid());
        $this->session->getValueKeys();
    }
    
    /**
     * test getting a value from session
     *
     * @test
     */
    public function getValue()
    {
        $this->assertNull($this->session->getValue('foo'));
        $this->assertEquals('bar', $this->session->getValue('foo', 'bar'));
        $this->session->putValue('foo', 'baz');
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
     * assure that no session fixation is possible
     *
     * @test
     */
    public function sessionFixation()
    {
        // session id is always regenerated for new sessions
        $this->assertEquals('foo', $this->session->getId());
    }
    
    /**
     * assure that no session hijacking is possible
     *
     * @test
     */
    public function sessionHijacking()
    {
        // original session started at 50 with fingerprint blub
        $this->session = new stubTestSession($this->getMock('stubRequest'), $this->getMock('stubResponse'), '50|blub|dummy');
        $this->assertTrue($this->session->isNew());
        $this->assertNotEquals(50, $this->session->getStartTime());
        $this->assertNotEquals('dummy', $this->session->getCurrentToken());
    }
    
    /**
     * test that resetting the session removes all stored values but does not
     * make the session invalid
     *
     * @test
     */
    public function reset()
    {
        $this->session->putValue('foo', 'baz');
        $nextToken = $this->session->getNextToken();
        $this->assertEquals($this->session->reset(), 1);
        $this->assertTrue($this->session->isValid());
        $this->assertEquals($nextToken, $this->session->getNextToken());
        $this->assertFalse($this->session->hasValue('foo'));
    }
    
    /**
     * assure that instances of stubSerializable are handled correct
     *
     * @test
     */
    public function stubObjectSerialization()
    {
        $mockSerializable = $this->getMock('stubSerializable');
        $mockSerializable->expects($this->any())->method('getClassName')->will($this->returnValue('MockstubSerializable'));
        $mockSerializable->expects($this->any())->method('hashCode')->will($this->returnValue('mock'));
        $serialized = new stubSerializedObject($mockSerializable);
        $mockSerializable->expects($this->once())->method('getSerialized')->will($this->returnValue($serialized));
        $this->session->putValue('foo', $mockSerializable);
        $foo = $this->session->getValue('foo');
        $this->assertSame($mockSerializable, $foo);
        $this->session->removeValue('foo');
    }
    
    /**
     * assure that instances of stubSerializable are handled correct
     *
     * @test
     */
    public function stubObjectUnserialization()
    {
        $mockSerializable = $this->getMock('stubSerializable');
        $mockSerializable->expects($this->any())->method('getClassName')->will($this->returnValue('MockstubSerializable'));
        $mockSerializable->expects($this->any())->method('hashCode')->will($this->returnValue('mock'));
        $serialized = new stubSerializedObject($mockSerializable);
        $this->session->inject('foo', $serialized);
        $second = $this->session->getValue('foo');
        $third  = $this->session->getValue('foo');
        $this->assertSame($second, $third);
    }

    /**
     * you should not clone a session
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cloneSessionThrowsRuntimeException()
    {
        $session = clone $this->session;
    }
}
?>