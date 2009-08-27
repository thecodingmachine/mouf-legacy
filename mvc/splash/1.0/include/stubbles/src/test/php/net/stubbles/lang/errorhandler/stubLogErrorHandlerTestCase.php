<?php
/**
 * Tests for net::stubbles::lang::errorhandler::stubLogErrorHandler.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubLogErrorHandler');
@include_once 'vfsStream/vfsStream.php';
/**
 * Tests for net::stubbles::lang::errorhandler::stubLogErrorHandler.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler_test
 * @group       lang
 * @group       lang_errorhandler
 */
class stubLogErrorHandlerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubLogErrorHandler
     */
    protected $logErrorHandler;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->logErrorHandler = new stubLogErrorHandler();
    }

    /**
     * assure that isResponsible() works correct
     *
     * @test
     */
    public function isResponsible()
    {
        $this->assertTrue($this->logErrorHandler->isResponsible(E_NOTICE, 'foo'));
    }

    /**
     * assure that isSupressable() works correct
     *
     * @test
     */
    public function isSupressable()
    {
        $this->assertFalse($this->logErrorHandler->isSupressable(E_NOTICE, 'foo'));
    }

    /**
     * handle() should log the error
     *
     * @test
     */
    public function handleErrorShouldLogTheError()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('stubLogErrorHandlerTestCase::handleErrorShouldLogTheError() requires vfsStream, see http://vfs.bovigo.org/');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('logDir'));
        $this->logErrorHandler->setLogDirectory(vfsStream::url('logDir/{Y}/{M}'));
        
        $this->assertTrue($this->logErrorHandler->handle(E_WARNING, 'message', __FILE__, __LINE__));
        $logFile = vfsStreamWrapper::getRoot()->getChild(date('Y') . '/' . date('m') . '/php-error-' . date('Y-m-d') . '.log');
        $logContents = explode('|', $logFile->getContent());
        $this->assertEquals(E_WARNING, $logContents[1]);
        $this->assertEquals('E_WARNING', $logContents[2]);
        $this->assertEquals('message', $logContents[3]);
        $this->assertEquals(__FILE__, $logContents[4]);
        $this->assertEquals("71\n", $logContents[5]);
        
        $this->logErrorHandler->setLogTarget('errors');
        $this->logErrorHandler->setMode(0777);
        $this->assertTrue($this->logErrorHandler->handle(313, 'message', __FILE__, __LINE__));
        $logFile = vfsStreamWrapper::getRoot()->getChild(date('Y') . '/' . date('m') . '/errors-' . date('Y-m-d') . '.log');
        $logContents = explode('|', $logFile->getContent());
        $this->assertEquals(313, $logContents[1]);
        $this->assertEquals('unknown', $logContents[2]);
        $this->assertEquals('message', $logContents[3]);
        $this->assertEquals(__FILE__, $logContents[4]);
        $this->assertEquals("82\n", $logContents[5]);
    }
}
?>