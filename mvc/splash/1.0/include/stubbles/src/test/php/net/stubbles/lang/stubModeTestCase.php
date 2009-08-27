<?php
/**
 * Tests for net::stubbles::lang::stubMode.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_test
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::lang::errorhandler::stubErrorHandler'
);
/**
 * Mock class to be used as error handler.
 *
 * @package     stubbles
 * @subpackage  lang_test
 */
class stubModestubErrorHandler extends stubBaseObject implements stubErrorHandler
{
    /**
     * checks whether this error handler is responsible for the given error
     *
     * @param   int     $level    level of the raised error
     * @param   string  $message  error message
     * @param   string  $file     optional  filename that the error was raised in
     * @param   int     $line     optional  line number the error was raised at
     * @param   array   $context  optional  array of every variable that existed in the scope the error was triggered in
     * @return  bool    true if error handler is responsible, else false
     */
    public function isResponsible($level, $message, $file = null, $line = null, array $context = array()) {}

    /**
     * checks whether this error is supressable
     * 
     * This method is called in case the level is 0. It decides whether the 
     * error has to be handled or if it can be omitted.
     *
     * @param   int     $level    level of the raised error
     * @param   string  $message  error message
     * @param   string  $file     optional  filename that the error was raised in
     * @param   int     $line     optional  line number the error was raised at
     * @param   array   $context  optional  array of every variable that existed in the scope the error was triggered in
     * @return  bool    true if error is supressable, else false
     */
    public function isSupressable($level, $message, $file = null, $line = null, array $context = array()) {}

    /**
     * handles the given error
     *
     * @param   int     $level    level of the raised error
     * @param   string  $message  error message
     * @param   string  $file     optional  filename that the error was raised in
     * @param   int     $line     optional  line number the error was raised at
     * @param   array   $context  optional  array of every variable that existed in the scope the error was triggered in
     * @return  bool    true if error message should populate $php_errormsg, else false
     * @throws  stubException  error handlers are allowed to throw every exception they want to
     */
    public function handle($level, $message, $file = null, $line = null, array $context = array()) {}
}
/**
 * Concrete instance of net::stubbles::lang::stubMode.
 *
 * @package     stubbles
 * @subpackage  lang_test
 */
class TeststubMode extends stubMode
{
    /**
     * we just need a test instance
     *
     * @var  TeststubMode
     */
    public static $FOO;

    /**
     * initialize the test instance
     */
    public static function init()
    {
        self::$FOO = new self('FOO');
    }

    /**
     * returns the class name
     *
     * @return  string
     */
    public function getClassName()
    {
        return 'net::stubbles::lang::test::TeststubMode';
    }

    /**
     * returns the exception handler
     *
     * @return  array
     */
    public function getExceptionHandler()
    {
        return $this->exceptionHandler;
    }

    /**
     * returns the error handler
     *
     * @return  array
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * helper method to access the protected getCallback() method
     *
     * @param   array     $handler
     * @return  callback
     */
    public function retrieveCallback(array &$handler)
    {
        return $this->getCallback($handler);
    }
}
TeststubMode::init();
/**
 * Tests for net::stubbles::lang::stubMode.
 *
 * @package     stubbles
 * @subpackage  lang_test
 * @group       lang
 */
class stubModeTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        stubMode::setCurrent(stubMode::$PROD);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubMode::setCurrent(stubMode::$PROD);
    }

    /**
     * assure that creating the callback work as expected
     *
     * @test
     */
    public function getCallbackWithStatic()
    {
        $handler = array('class'  => 'stubModestubErrorHandler',
                         'method' => 'handle',
                         'type'   => stubMode::HANDLER_STATIC
                   );
        $this->assertEquals(array('stubModestubErrorHandler', 'handle'),
                            TeststubMode::$FOO->retrieveCallback($handler)
        );
    }

    /**
     * assure that creating the callback work as expected
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function getCallbackWithStaticButInstanceGiven()
    {
        $instance = new stubModestubErrorHandler();
        $handler  = array('class'  => $instance,
                          'method' => 'handle',
                          'type'   => stubMode::HANDLER_STATIC
                    );
        TeststubMode::$FOO->retrieveCallback($handler);
    }

    /**
     * assure that creating the callback work as expected
     *
     * @test
     */
    public function getCallbackWithInstanceFromClassname()
    {
        $handler  = array('class'  => 'stubModestubErrorHandler',
                          'method' => 'handle',
                          'type'   => stubMode::HANDLER_INSTANCE
                    );
        $callback = TeststubMode::$FOO->retrieveCallback($handler);
        $this->assertType('stubModestubErrorHandler', $callback[0]);
        $this->assertEquals('handle', $callback[1]);
    }

    /**
     * assure that creating the callback work as expected
     *
     * @test
     */
    public function getCallbackWithInstanceFromInstance()
    {
        $instance = new stubModestubErrorHandler();
        $handler  = array('class'  => $instance,
                          'method' => 'handle',
                          'type'   => stubMode::HANDLER_INSTANCE
                    );
        $callback = TeststubMode::$FOO->retrieveCallback($handler);
        $this->assertSame($instance, $callback[0]);
        $this->assertEquals('handle', $callback[1]);
    }

    /**
     * test that cache switch is set correct
     *
     * @test
     */
    public function cacheEnabled()
    {
        $this->assertTrue(stubMode::$PROD->isCacheEnabled());
        $this->assertTrue(stubMode::$TEST->isCacheEnabled());
        $this->assertFalse(stubMode::$STAGE->isCacheEnabled());
        $this->assertFalse(stubMode::$DEV->isCacheEnabled());
    }

    /**
     * test that the stage and dev mode do not register any error handler by default
     *
     * @test
     */
    public function noErrorHandlerForStageAndDevMode()
    {
        $this->assertFalse(stubMode::$STAGE->registerErrorHandler());
        $this->assertFalse(stubMode::$DEV->registerErrorHandler());
    }

    /**
     * test that the exception handler is set correct
     *
     * @test
     */
    public function setExceptionHandler()
    {
        TeststubMode::$FOO->setExceptionHandler('class', 'method', stubMode::HANDLER_INSTANCE);
        $this->assertEquals(array('class'  => 'class',
                                  'method' => 'method',
                                  'type'   => stubMode::HANDLER_INSTANCE
                            ),
                            TeststubMode::$FOO->getExceptionHandler()
        );
    }

    /**
     * test that the error handler is set correct
     *
     * @test
     */
    public function setErrorHandler()
    {
        TeststubMode::$FOO->setErrorHandler('class', 'method', stubMode::HANDLER_STATIC);
        $this->assertEquals(array('class'  => 'class',
                                  'method' => 'method',
                                  'type'   => stubMode::HANDLER_STATIC
                            ),
                            TeststubMode::$FOO->getErrorHandler()
        );
    }

    /**
     * test that current mode is a reference to the selected mode
     *
     * @test
     */
    public function current()
    {
        // PROD is the default current mode
        $this->assertSame(stubMode::$PROD, stubMode::$CURRENT);
        stubMode::setCurrent(stubMode::$DEV);
        $this->assertSame(stubMode::$DEV, stubMode::$CURRENT);
    }
}
?>