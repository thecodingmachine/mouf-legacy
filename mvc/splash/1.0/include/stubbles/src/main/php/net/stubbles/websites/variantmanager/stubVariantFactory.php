<?php
/**
 * Interface for variant factories that create a variant map.
 * 
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantConfigurationException',
                      'net::stubbles::websites::variantmanager::stubVariantsMap'
);
/**
 * Interface for variant factories that create a variant map.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
interface stubVariantFactory extends stubObject
{
    /**
     * Get all defined variants in this configuration
     * 
     * @return  array<string>
     */
    public function getVariantNames();

    /**
     * get a variant by its name
     *
     * @param   string       $variantName
     * @return  stubVariant
     */
    public function getVariantByName($variantName);

    /**
     * return the variant map
     *
     * @return  stubVariantsMap
     */
    public function getVariantsMap();
}
?>