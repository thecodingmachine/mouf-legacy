<?php
/**
 * Tests for net::stubbles::lang::initializer::stubGeneralInitializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer_test
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubGeneralInitializer');
/**
 * Tests for net::stubbles::lang::initializer::stubGeneralInitializer.
 *
 * @package     stubbles
 * @subpackage  lang_initializer_test
 * @group       lang
 * @group       lang_initializer
 */
class stubGeneralInitializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * logging enabled should return a logger initializer
     *
     * @test
     */
    public function loggingEnabled()
    {
        $generalInitializer = new stubGeneralInitializer();
        $this->assertTrue($generalInitializer->isLoggingEnabled());
        $initializer = $generalInitializer->getLoggerInitializer();
        $this->assertType('stubLoggerInitializer', $initializer);
        $this->assertType('stubLoggerXJConfInitializer', $initializer);
    }

    /**
     * logging enabled with wrong class should throw an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function loggingEnabledWrongClass()
    {
        $generalInitializer = new stubGeneralInitializer(null, array('logger' => 'stdClass'));
        $this->assertTrue($generalInitializer->isLoggingEnabled());
        $generalInitializer->getLoggerInitializer();
    }

    /**
     * logging disabled should not return a logger initializer
     *
     * @test
     */
    public function loggingDisabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('logger' => false));
        $this->assertFalse($generalInitializer->isLoggingEnabled());
        $this->assertNull($generalInitializer->getLoggerInitializer());
    }

    /**
     * rdbms enabled should return a database initializer
     *
     * @test
     */
    public function rdbmsEnabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('rdbms' => true));
        $this->assertTrue($generalInitializer->isDatabaseEnabled());
        $initializer = $generalInitializer->getDatabaseInitializer();
        $this->assertType('stubDatabaseInitializer', $initializer);
        $this->assertType('stubDatabaseXJConfInitializer', $initializer);
    }

    /**
     * rdbms enabled with wrong class should throw an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function rdbmsEnabledWrongClass()
    {
        $generalInitializer = new stubGeneralInitializer(array('rdbms' => true), array('rdbms' => 'stdClass'));
        $this->assertTrue($generalInitializer->isDatabaseEnabled());
        $generalInitializer->getDatabaseInitializer();
    }

    /**
     * rdbms disabled should not return a database initializer
     *
     * @test
     */
    public function rdbmsDisabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('rdbms' => false));
        $this->assertFalse($generalInitializer->isDatabaseEnabled());
        $this->assertNull($generalInitializer->getDatabaseInitializer());
    }

    /**
     * cache enabled should return a cache initializer
     *
     * @test
     */
    public function cacheEnabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('cache' => true));
        $this->assertTrue($generalInitializer->isCachingEnabled());
        $initializer = $generalInitializer->getCacheInitializer();
        $this->assertType('stubCacheInitializer', $initializer);
        $this->assertType('stubCacheXJConfInitializer', $initializer);
    }

    /**
     * cache enabled with wrong class should throw an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cacheEnabledWrongClass()
    {
        $generalInitializer = new stubGeneralInitializer(array('cache' => true), array('cache' => 'stdClass'));
        $this->assertTrue($generalInitializer->isCachingEnabled());
        $generalInitializer->getCacheInitializer();
    }

    /**
     * cache disabled should not return a cache initializer
     *
     * @test
     */
    public function cacheDisabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('cache' => false));
        $this->assertFalse($generalInitializer->isCachingEnabled());
        $this->assertNull($generalInitializer->getCacheInitializer());
    }

    /**
     * events enabled should return an events initializer
     *
     * @test
     */
    public function eventsEnabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('events' => true));
        $this->assertFalse($generalInitializer->isEventHandlingEnabled());
        #$this->assertTrue($generalInitializer->isEventHandlingEnabled());
        $initializer = $generalInitializer->getEventsInitializer();
        $this->assertNull($initializer);
        /*$this->assertType('stubEventsInitializer', $initializer);
        $this->assertType('stubEventsXJConfInitializer', $initializer);*/
    }

    /**
     * events enabled with wrong class should throw an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    /*public function eventsEnabledWrongClass()
    {
        $generalInitializer = new stubGeneralInitializer(array('events' => true), array('events' => 'stdClass'));
        $this->assertTrue($generalInitializer->isEventHandlingEnabled());
        $generalInitializer->getEventsInitializer();
    }*/

    /**
     * events disabled should not return an events initializer
     *
     * @test
     */
    public function eventsDisabled()
    {
        $generalInitializer = new stubGeneralInitializer(array('events' => false));
        $this->assertFalse($generalInitializer->isEventHandlingEnabled());
        $this->assertNull($generalInitializer->getEventsInitializer());
    }

    /**
     * disabled init should not call any initializer
     *
     * @test
     */
    public function initDisabled()
    {
        $generalInitializer = $this->getMock('stubGeneralInitializer',
                                             array('getLoggerInitializer',
                                                   'getCacheInitializer',
                                                   'getDatabaseInitializer',
                                                   'getEventsInitializer'
                                             ),
                                             array(array('logger' => false))
                              );
        $generalInitializer->expects($this->never())->method('getLoggerInitializer');
        $generalInitializer->expects($this->never())->method('getCacheInitializer');
        $generalInitializer->expects($this->never())->method('getDatabaseInitializer');
        $generalInitializer->expects($this->never())->method('getEventsInitializer');
        $generalInitializer->init();
    }

    /**
     * disabled init should not call any initializer
     *
     * @test
     */
    public function initEnabled()
    {
        $generalInitializer = $this->getMock('stubGeneralInitializer',
                                             array('getLoggerInitializer',
                                                   'getCacheInitializer',
                                                   'getDatabaseInitializer',
                                                   'getEventsInitializer'
                                             ),
                                             array(array('rdbms'  => true,
                                                         'cache'  => true,
                                                         'events' => true
                                                   )
                                             )
                              );
        $mockLoggerInitializer = $this->getMock('stubInitializer');
        $mockLoggerInitializer->expects($this->once())->method('init');
        $generalInitializer->expects($this->once())->method('getLoggerInitializer')->will($this->returnValue($mockLoggerInitializer));
        $mockCacheInitializer = $this->getMock('stubInitializer');
        $mockCacheInitializer->expects($this->once())->method('init');
        $generalInitializer->expects($this->once())->method('getCacheInitializer')->will($this->returnValue($mockCacheInitializer));;
        $mockDatabaseInitializer = $this->getMock('stubInitializer');
        $mockDatabaseInitializer->expects($this->once())->method('init');
        $generalInitializer->expects($this->once())->method('getDatabaseInitializer')->will($this->returnValue($mockDatabaseInitializer));;
        #$mockEventsInitializer = $this->getMock('stubInitializer');
        #$mockEventsInitializer->expects($this->once())->method('init');
        #$generalInitializer->expects($this->once())->method('getEventsInitializer')->will($this->returnValue($mockEventsInitializer));;
        $generalInitializer->init();
    }
}
?>