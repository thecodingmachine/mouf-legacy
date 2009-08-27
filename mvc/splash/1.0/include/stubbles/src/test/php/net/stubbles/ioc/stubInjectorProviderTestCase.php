<?php
/**
 * Test for net::stubbles::ioc::stubInjector with provider binding.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder');
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 */
class stubInjectorProviderTestCase_Answer
{
    /**
     * the answer to all questions
     *
     * @return  int
     */
    public function answer()
    {
        return 42;
    }
}
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 */
class stubInjectorProviderTestCase_Question
{
    private $answer;

    /**
     * @param  stubInjectorProviderTestCase_Answer  $answer
     * @Inject
     * @Named('answer')
     */
    public function setAnswer(stubInjectorProviderTestCase_Answer $answer)
    {
        $this->answer = $answer;
    }

    public function getAnswer()
    {
        return $this->answer;
    }
}

/**
 * Test for net::stubbles::ioc::stubInjector with provider binding.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubInjectorProviderTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * use a provider for the injection
     *
     * @test
     */
    public function injectWithProvider()
    {
        $binder       = new stubBinder();
        $mockProvider = $this->getMock('stubInjectionProvider');
        $answer       = new stubInjectorProviderTestCase_Answer();
        $mockProvider->expects($this->once())
                     ->method('get')
                     ->with($this->equalTo('stubInjectorProviderTestCase_Answer'), $this->equalTo('answer'))
                     ->will($this->returnValue($answer));
        $binder->bind('stubInjectorProviderTestCase_Answer')->toProvider($mockProvider);
        $question = $binder->getInjector()->getInstance('stubInjectorProviderTestCase_Question');
        $this->assertType('stubInjectorProviderTestCase_Question', $question);
        $this->assertSame($answer, $question->getAnswer());
    }
}
?>