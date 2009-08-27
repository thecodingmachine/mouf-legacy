<?php
/**
 * Integration test for configuring encoders with XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::util::xjconf::xjconf',
                      'net::stubbles::util::xjconf::xjconfReal'
);
/**
 * Integration test for configuring encoders with XJConf.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class EncoderXJConfTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * helper method
     *
     * @return  stubXJConfFacade
     */
    public function getXJConf()
    {
        $xjconf = new stubXJConfFacade(new XJConfFacade(array('__default' => stubXJConfLoader::getInstance())));
        $xjconf->addDefinitions(stubFactory::getResourceURIs('xjconf/encoder.xml'));
        $xjconf->parse(TEST_SRC_PATH . '/resources/xjconf/encoder.xml');
        return $xjconf;
    }

    /**
     * assure that a base64 encoder is created correct
     *
     * @test
     */
    public function instances()
    {
        $xjconf = $this->getXJConf();
        $base64Encoder = $xjconf->getConfigValue('base64');
        $this->assertType('stubBase64Encoder', $base64Encoder);
        $md5Encoder = $xjconf->getConfigValue('md5');
        $this->assertType('stubMd5Encoder', $md5Encoder);
        $urlEncoder = $xjconf->getConfigValue('url');
        $this->assertType('stubURLEncoder', $urlEncoder);
        $utf8Encoder = $xjconf->getConfigValue('utf8');
        $this->assertType('stubUTF8Encoder', $utf8Encoder);
        
        // cached
        $xjconf = $this->getXJConf();
        $base64Encoder = $xjconf->getConfigValue('base64');
        $this->assertType('stubBase64Encoder', $base64Encoder);
        $md5Encoder = $xjconf->getConfigValue('md5');
        $this->assertType('stubMd5Encoder', $md5Encoder);
        $urlEncoder = $xjconf->getConfigValue('url');
        $this->assertType('stubURLEncoder', $urlEncoder);
        $utf8Encoder = $xjconf->getConfigValue('utf8');
        $this->assertType('stubUTF8Encoder', $utf8Encoder);
    }
}
?>