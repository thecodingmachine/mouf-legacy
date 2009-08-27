<?php
/**
 * Helper classes for tests of the net::stubbles::websites::memphis::stubMemphisProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
/**
 * Extends the original class to get rid of initializing. 
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
class TeststubMemphisConfig extends stubMemphisConfig
{
    /**
     * constructor
     */
    public function __construct() { }
}
/**
 * Extend tested class to be able to inject mock instances.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
class TeststubMemphisProcessor extends stubMemphisProcessor
{
    /**
     * switch whether we are in ssl mode or not
     *
     * @var  bool
     */
    protected $sslOverwrite = null;

    /**
     * overwrite parent ssl setting
     *
     * @param  bool  $ssl
     */
    public function setSSL($sslOverwrite)
    {
        $this->sslOverwrite = $sslOverwrite;
    }

    /**
     * checks whether the request is ssl or not
     *
     * @return  bool
     */
    public function isSSL()
    {
        if (null !== $this->sslOverwrite) {
            return $this->sslOverwrite;
        }
        
        return parent::isSSL();
    }

    /**
     * sets the memphis config instance (no type hint to allow mocks from concrete class)
     *
     * @param  stubMemphisConfig  $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * helper method to create the config object
     *
     * @return  stubMemphisConfig
     * @throws  stubException
     */
    protected function createConfig()
    {
        return null;
    }

    /**
     * sets the memphis template instance (no type hint to allow mocks from concrete class)
     *
     * @param  stubMemphisTemplate  $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * helper method to create the template
     *
     * @return  stubMemphisTemplate
     */
    protected function createTemplate()
    {
        return $this->template;
    }

    /**
     * helper method to access protected method getFrameId()
     *
     * @return  string
     */
    public function callGetFrameId()
    {
        return parent::getFrameId();
    }

    /**
     * helper method to get the name of the frame to use
     *
     * @return  string
     */
    protected function getFrameId()
    {
        return 'frame';
    }

    /**
     * helper method to access protected method setTemplateVars()
     */
    public function callSetTemplateVars()
    {
        return parent::setTemplateVars();
    }

    /**
     * helper method to set the template vars
     */
    protected function setTemplateVars()
    {
        // intentionally empty
    }
}
?>