<?php
/**
 * Abstract base implementation for a variant factory.
 * 
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantFactory',
                      'net::stubbles::websites::variantmanager::stubVariantsMap'
);
/**
 * Abstract base implementation for a variant factory.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
abstract class stubAbstractVariantFactory extends stubBaseObject implements stubVariantFactory
{
    /**
     * map of available variants
     *
     * @var  stubVariantsMap
     */
    protected $variantsMap;

    /**
     * Get all defined variants in this configuration
     * 
     * @return  array<string>
     */
    public function getVariantNames()
    {
        return $this->variantsMap->getVariantNames();
    }

    /**
     * get a variant by its name
     *
     * @param   string       $variantName
     * @return  stubVariant
     */
    public function getVariantByName($variantName)
    {
        return $this->variantsMap->getVariantByName($variantName);
    }

    /**
     * return the variant map
     *
     * @return  stubVariantsMap
     */
    public function getVariantsMap()
    {
        return $this->variantsMap;
    }
}
?>