<?php
/**
 * Skin generator that uses another skin generator and caches its results.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
stubClassLoader::load('net::stubbles::util::cache::stubCacheContainer',
                      'net::stubbles::websites::xml::skin::stubSkinGenerator'
);
/**
 * Skin generator that uses another skin generator and caches its results.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
class stubCachingSkinGenerator extends stubBaseObject implements stubSkinGenerator
{
    /**
     * real skin generator to be used
     *
     * @var  stubSkinGenerator
     */
    protected $skinGenerator;
    /**
     * cache to be used
     *
     * @var  stubCacheContainer
     */
    protected $cache;

    /**
     * constructor
     *
     * @param  stubSkinGenerator   $skinGenerator  real skin generator to be used
     * @param  stubCacheContainer  $cache          cache to be used
     */
    public function __construct(stubSkinGenerator $skinGenerator, stubCacheContainer $cache)
    {
        $this->skinGenerator = $skinGenerator;
        $this->cache         = $cache;
    }

    /**
     * checks whether a given skin exists
     *
     * @param   string  $skinName
     * @return  bool
     */
    public function hasSkin($skinName)
    {
        return $this->skinGenerator->hasSkin($skinName);
    }

    /**
     * returns the key for the skin to be generated
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  string
     */
    public function getSkinKey(stubSession $session, stubPage $page, $skinName)
    {
        return $this->skinGenerator->getSkinKey($session, $page, $skinName);
    }

    /**
     * generates the skin document
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  DOMDocument
     */
    public function generate(stubSession $session, stubPage $page, $skinName)
    {
        $key = $this->skinGenerator->getSkinKey($session, $page, $skinName);
        if ($this->cache->has($key) === true) {
            $resultXSL = new DOMDocument();
            $resultXSL->loadXML($this->cache->get($key));
            return $resultXSL;
        }
        
        $resultXSL = $this->skinGenerator->generate($session, $page, $skinName);
        $this->cache->put($key, $resultXSL->saveXML());
        return $resultXSL;
    }
}
?>