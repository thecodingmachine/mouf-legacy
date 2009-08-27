<?php
/**
 * Test for net::stubbles::ioc::stubInjector with constant binding.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder');

class stubInjectorConstantTestCase_Question {
    private $answer;

    /**
     * @Inject
     * @Named('answer')
     */
    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    public function getAnswer() {
        return $this->answer;
    }
}

/**
 * Test for net::stubbles::ioc::stubInjector with constant binding.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubInjectorConstantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Test a constant injection
     *
     * @test
     */
    public function injectConstant()
    {
        $binder = new stubBinder();
        $binder->bindConstant()->named('answer')->to(42);
        $injector = $binder->getInjector();
        $question = $injector->getInstance('stubInjectorConstantTestCase_Question');
        $this->assertType('stubInjectorConstantTestCase_Question', $question);
        $this->assertEquals(42, $question->getAnswer());
    }
}
?>