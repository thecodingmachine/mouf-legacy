<?php
/**
 * Tests for net::stubbles::ipo::interceptors::stubInterceptorXJConfInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubInterceptorXJConfInitializer',
                      'net::stubbles::ipo::interceptors::stubPreInterceptor',
                      'net::stubbles::ipo::interceptors::stubPostInterceptor'
);
/**
 * Helper class for test: serializable pre- and postinterceptor.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 */
class MockSerializableInterceptor extends stubSerializableObject implements stubPreInterceptor, stubPostInterceptor
{
    /**
     * does the preprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response) {}

    /**
     * does the postprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function postProcess(stubRequest $request, stubSession $session, stubResponse $response) {}
}
/**
 * Helper class for test: non-serializable preinterceptor.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 */
class MockPreInterceptor extends stubBaseObject implements stubPreInterceptor
{
    /**
     * does the preprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response) {}

    /**
     * returns the full qualified class name
     *
     * @return  string
     */
    public function getClassName()
    {
        return 'MockPreInterceptor';
    }
}
/**
 * Helper class for test: non-serializable postinterceptor.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 */
class MockPostInterceptor extends stubBaseObject implements stubPostInterceptor
{
    /**
     * does the postprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function postProcess(stubRequest $request, stubSession $session, stubResponse $response) {}

    /**
     * returns the full qualified class name
     *
     * @return  string
     */
    public function getClassName()
    {
        return 'MockPostInterceptor';
    }
}
/**
 * Tests for net::stubbles::ipo::interceptors::stubInterceptorXJConfInitializer.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors_test
 * @group       ipo
 * @group       ipo_interceptors
 */
class stubInterceptorXJConfInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubInterceptorXJConfInitializer
     */
    protected $interceptorXJConfInitializer;
    /**
     * list of mocked pre interceptors
     *
     * @var  array<stubPreInterceptor>
     */
    protected $preInterceptors              = array();
    /**
     * list of mocked post interceptors
     *
     * @var  array<stubPostInterceptor>
     */
    protected $postInterceptors             = array();

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->interceptorXJConfInitializer = new stubInterceptorXJConfInitializer();
        $this->preInterceptors[]            = new MockPreInterceptor();
        $this->preInterceptors[]            = new MockSerializableInterceptor();
        $this->postInterceptors[]           = new MockSerializableInterceptor();
        $this->postInterceptors[]           = new MockPostInterceptor();
    }

    /**
     * test descriptor
     *
     * @test
     */
    public function descriptor()
    {
        $this->assertEquals('interceptors', $this->interceptorXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('interceptors', $this->interceptorXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
        $this->interceptorXJConfInitializer->setDescriptor('foo');
        $this->assertEquals('foo', $this->interceptorXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_CONFIG));
        $this->assertEquals('interceptors', $this->interceptorXJConfInitializer->getDescriptor(stubXJConfInitializer::DESCRIPTOR_DEFINITION));
    }

    /**
     * test descriptor
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function descriptorWrongArgument()
    {
        $this->interceptorXJConfInitializer->getDescriptor('bar');
    }

    /**
     * check that the cache data is correct
     *
     * @test
     */
    public function getCacheData()
    {
        $this->interceptorXJConfInitializer->setPreInterceptors($this->preInterceptors);
        $this->interceptorXJConfInitializer->setPostInterceptors($this->postInterceptors);
        $cacheData = $this->interceptorXJConfInitializer->getCacheData();
        $this->assertEquals('MockPreInterceptor', $cacheData['preInterceptors'][0]);
        $this->assertType('stubSerializedObject', $cacheData['preInterceptors'][1]);
        $this->assertType('stubSerializedObject', $cacheData['postInterceptors'][0]);
        $this->assertEquals('MockPostInterceptor', $cacheData['postInterceptors'][1]);
        
    }

    /**
     * check that cache data is used correct
     *
     * @test
     */
    public function setCacheData()
    {
        $cacheData['preInterceptors'][0]  = 'MockPreInterceptor';
        $cacheData['preInterceptors'][1]  = $this->preInterceptors[1]->getSerialized();
        $cacheData['postInterceptors'][0] = $this->postInterceptors[0]->getSerialized();
        $cacheData['postInterceptors'][1] = 'MockPostInterceptor';
        $this->interceptorXJConfInitializer->setCacheData($cacheData);
        $preInterceptors = $this->interceptorXJConfInitializer->getPreInterceptors();
        $this->assertType('MockPreInterceptor', $preInterceptors[0]);
        $this->assertType('MockSerializableInterceptor', $preInterceptors[1]);
        $postInterceptors = $this->interceptorXJConfInitializer->getPostInterceptors();
        $this->assertType('MockSerializableInterceptor', $postInterceptors[0]);
        $this->assertType('MockPostInterceptor', $postInterceptors[1]);
    }
}
?>