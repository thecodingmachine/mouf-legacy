<?php
/**
 * Integration test for configuring validators with XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::util::xjconf::xjconf',
                      'net::stubbles::util::xjconf::xjconfReal'
);
/**
 * Integration test for configuring validators with XJConf.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class ValidatorsXJConfTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * access to xjconf
     *
     * @var  stubXJConfFacade
     */
    protected $xjconf;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->xjconf = new stubXJConfFacade(new XJConfFacade(array('__default' => stubXJConfLoader::getInstance())));
        $this->xjconf->addDefinitions(stubFactory::getResourceURIs('xjconf/validators.xml'));
        $this->xjconf->parse(TEST_SRC_PATH . '/resources/xjconf/validators.xml');
    }

    /**
     * assure that a contains validator is created correct
     *
     * @test
     */
    public function containsValidator()
    {
        $equalValidator = $this->xjconf->getConfigValue('contains');
        $this->assertType('stubContainsValidator', $equalValidator);
        $this->assertEquals(array('contained' => 'foo'), $equalValidator->getCriteria());
    }

    /**
     * assure that an equal validator is created correct
     *
     * @test
     */
    public function equalValidator()
    {
        $equalValidator = $this->xjconf->getConfigValue('equal');
        $this->assertType('stubEqualValidator', $equalValidator);
        $this->assertEquals(array('expected' => 3), $equalValidator->getCriteria());
    }

    /**
     * assure that an ip validator is created correct
     *
     * @test
     */
    public function ipValidator()
    {
        $ipValidator = $this->xjconf->getConfigValue('ip');
        $this->assertType('stubIPValidator', $ipValidator);
    }

    /**
     * assure that a mail validator is created correct
     *
     * @test
     */
    public function mailValidator()
    {
        $mailValidator = $this->xjconf->getConfigValue('mail');
        $this->assertType('stubMailValidator', $mailValidator);
    }

    /**
     * assure that a max length validator is created correct
     *
     * @test
     */
    public function maxLengthValidator()
    {
        $maxLengthValidator = $this->xjconf->getConfigValue('maxLength');
        $this->assertType('stubMaxLengthValidator', $maxLengthValidator);
        $this->assertEquals(array('maxLength' => 4), $maxLengthValidator->getCriteria());
    }

    /**
     * assure that a max number validator is created correct
     *
     * @test
     */
    public function maxNumberValidator()
    {
        $maxNumberValidator = $this->xjconf->getConfigValue('maxNumber');
        $this->assertType('stubMaxNumberValidator', $maxNumberValidator);
        $this->assertEquals(array('maxNumber' => 5), $maxNumberValidator->getCriteria());
    }

    /**
     * assure that a min length validator is created correct
     *
     * @test
     */
    public function minLengthValidator()
    {
        $minLengthValidator = $this->xjconf->getConfigValue('minLength');
        $this->assertType('stubMinLengthValidator', $minLengthValidator);
        $this->assertEquals(array('minLength' => 4), $minLengthValidator->getCriteria());
    }

    /**
     * assure that a min number validator is created correct
     *
     * @test
     */
    public function minNumberValidator()
    {
        $minNumberValidator = $this->xjconf->getConfigValue('minNumber');
        $this->assertType('stubMinNumberValidator', $minNumberValidator);
        $this->assertEquals(array('minNumber' => 5), $minNumberValidator->getCriteria());
    }

    /**
     * assure that a pass thru validator is created correct
     *
     * @test
     */
    public function passThruValidator()
    {
        $passThruValidator = $this->xjconf->getConfigValue('passThru');
        $this->assertType('stubPassThruValidator', $passThruValidator);
    }

    /**
     * assure that a min number validator is created correct
     *
     * @test
     */
    public function preSelectValidator()
    {
        $preSelectValidator = $this->xjconf->getConfigValue('preSelect');
        $this->assertType('stubPreSelectValidator', $preSelectValidator);
        $this->assertEquals(array('allowedValues' => array(313, 'Donald Duck', true)), $preSelectValidator->getCriteria());
    }

    /**
     * assure that a regex validator is created correct
     *
     * @test
     */
    public function regexValidator()
    {
        $regexValidator = $this->xjconf->getConfigValue('regex');
        $this->assertType('stubRegexValidator', $regexValidator);
        $this->assertEquals(array('regex' => '/([a-Z]){1,3}/'), $regexValidator->getCriteria());
    }

    /**
     * assure that an and validator is created correct
     *
     * @test
     */
    public function andValidator()
    {
        $andValidator = $this->xjconf->getConfigValue('and');
        $this->assertType('stubAndValidator', $andValidator);
        $this->assertEquals(array('expected'  => 'This must be equal.',
                                  'maxNumber' => 5.5
                            ),
                            $andValidator->getCriteria()
        );
    }

    /**
     * assure that an or validator is created correct
     *
     * @test
     */
    public function orValidator()
    {
        $orValidator = $this->xjconf->getConfigValue('or');
        $this->assertType('stubOrValidator', $orValidator);
        $this->assertEquals(array('expected'  => true,
                                  'minNumber' => 5.5
                            ),
                            $orValidator->getCriteria()
        );
    }

    /**
     * assure that an xor validator is created correct
     *
     * @test
     */
    public function xorValidator()
    {
        $xorValidator = $this->xjconf->getConfigValue('xor');
        $this->assertType('stubXorValidator', $xorValidator);
        $this->assertEquals(array('expected' => 'null',
                                  'regex'    => '([a-Z]){1,3}'
                             ),
                             $xorValidator->getCriteria()
        );
    }
}
?>