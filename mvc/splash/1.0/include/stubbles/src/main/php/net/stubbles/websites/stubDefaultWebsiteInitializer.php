<?php
/**
 * Default implementation of a website initializer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractWebsiteInitializer');
/**
 * Default implementation of a website initializer.
 *
 * @package     stubbles
 * @subpackage  websites
 */
class stubDefaultWebsiteInitializer extends stubAbstractWebsiteInitializer
{
    /**
     * default mode to be used
     *
     * @var  stubMode
     */
    protected $defaultMode;

    /**
     * constructor
     *
     * @param  stubGeneralInitializer  $generalInitializer  optional  general purpose initializer to be used
     * @param  stubMode                $defaultMode         optional  default mode to be used
     */
    public function __construct(stubGeneralInitializer $generalInitializer = null, stubMode $defaultMode = null)
    {
        $this->generalInitializer = $generalInitializer;
        $this->defaultMode        = $defaultMode;
    }

    /**
     * returns the mode to be used
     *
     * @return  stubMode
     */
    protected function getMode()
    {
        if (null === $this->defaultMode) {
            return stubMode::$PROD;
        }
        
        return $this->defaultMode;
    }
}
?>