<?php
/**
 * Annotation to mark class methods accessable as xsl callback.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation'
);
/**
 * Annotation to mark class methods accessable as xsl callback.
 *
 * @package     stubbles
 * @subpackage  xml_xsl
 */
class stubXSLMethodAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD;
    }
}
?>