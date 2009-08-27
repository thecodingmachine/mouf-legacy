<?php
/**
 * Integration test for interceptor creation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubInterceptorXJConfInitializer');
/**
 * Integration test for interceptor creation.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class InterceptorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method
     *
     * @return  stubInterceptorXJConfInitializer
     */
    protected function initInitializer()
    {
        $interceptorXJConfInitializer = new stubInterceptorXJConfInitializer();
        $interceptorXJConfInitializer->init();
        return $interceptorXJConfInitializer;
    }

    /**
     * assure that creating the logger instances works correct
     *
     * @test
     */
    public function interceptorXJConfInitializer()
    {
        $interceptorXJConfInitializer = $this->initInitializer();
        $preInterceptors = $interceptorXJConfInitializer->getPreInterceptors();
        $this->assertType('stubShowLastXMLInterceptor', $preInterceptors[0]);
        $postInterceptors = $interceptorXJConfInitializer->getPostInterceptors();
        $this->assertEquals(array(), $postInterceptors);
        
        // cached
        $interceptorXJConfInitializer = $this->initInitializer();
        $preInterceptors = $interceptorXJConfInitializer->getPreInterceptors();
        $this->assertType('stubShowLastXMLInterceptor', $preInterceptors[0]);
        $postInterceptors = $interceptorXJConfInitializer->getPostInterceptors();
        $this->assertEquals(array(), $postInterceptors);
    }
}
?>