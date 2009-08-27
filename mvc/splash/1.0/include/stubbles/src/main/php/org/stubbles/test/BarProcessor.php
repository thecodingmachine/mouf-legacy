<?php
/**
 * A processor used in tests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
stubClassLoader::load('net::stubbles::websites::processors::stubAbstractProcessor');
/**
 * A processor used in tests.
 *
 * @package     stubbles
 * @subpackage  test
 */
class BarProcessor extends stubAbstractProcessor
{
    /**
     * does the real processing
     */
    public function process()
    {
        // nothing to process here
    }

    public function getRequest()
    {
        return $this->request;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getPageFactory()
    {
        return $this->pageFactory;
    }
}
?>