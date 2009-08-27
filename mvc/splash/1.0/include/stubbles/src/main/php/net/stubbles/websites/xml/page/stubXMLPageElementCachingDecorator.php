<?php
/**
 * Decorator to cache XML page elements
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
stubClassLoader::load('net::stubbles::websites::xml::page::stubXMLPageElementDecorator');
/**
 * Decorator to cache XML page elements
 *
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
class stubXMLPageElementCachingDecorator extends stubXMLPageElementDecorator
{
    /**
     * Lifetime of the cache
     *
     * @var  int
     */
    private $lifetime = 3600;

    /**
     * Set the lifetime of the cache
     *
     * @param  int  $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * this page element is not cachable in the context of the whole page
     *
     * @return  bool
     */
    public function isCachable()
    {
        return false;
    }

    /**
     * Tries to load the result from the cache or processes the page element.
     *
     * @return  mixed
     */
    public function process()
    {
        $cacheFile = sprintf('%s/xml/elements/%s.cache', stubConfig::getCachePath(), $this->element->getName());
        if (file_exists($cacheFile) === true && (filemtime($cacheFile) + $this->lifetime) >= time()) {
            return unserialize(file_get_contents($cacheFile));
        }
        
        $data = $this->element->process();
        file_put_contents($cacheFile, serialize($data));
        return $data;
    }
}
?>