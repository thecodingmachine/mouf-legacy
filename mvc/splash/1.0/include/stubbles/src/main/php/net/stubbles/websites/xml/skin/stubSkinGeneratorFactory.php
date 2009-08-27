<?php
/**
 * Factory to create skin generators depending on the current mode.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::util::cache::stubCache',
                      'net::stubbles::websites::xml::skin::stubCachingSkinGenerator',
                      'net::stubbles::websites::xml::skin::stubDefaultSkinGenerator'
);
/**
 * Factory to create skin generators depending on the current mode.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
class stubSkinGeneratorFactory extends stubBaseObject
{
    /**
     * cache id to be used to get the cache container
     *
     * @var  string
     */
    protected $cacheId;

    /**
     * constructor
     *
     * @param  string  $cacheId  cache id to be used to get the cache container
     */
    public function __construct($cacheId = 'skin')
    {
        $this->cacheId = $cacheId;
    }

    /**
     * factory method to create the skin generator instance
     *
     * @return  stubSkinGenerator
     */
    public function create()
    {
        switch (stubMode::$CURRENT) {
            case stubMode::$PROD:
                // break omitted
            
            case stubMode::$TEST:
                $skinGenerator = new stubCachingSkinGenerator(new stubDefaultSkinGenerator(), stubCache::factory($this->cacheId));
                break;
            
            default:
                $skinGenerator = new stubDefaultSkinGenerator();
        }
        
        return $skinGenerator;
    }
}
?>