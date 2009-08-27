<?php
/**
 * Interface for filter annotations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
/**
 * Interface for filter annotations.
 *
 * Use this annotation to define which filter should be used to populate a
 * property or method of a class with a value from the request.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
interface stubFilterAnnotation
{
    /**
     * returns the name of the request variable
     *
     * @return  string
     */
    public function getFieldName();

    /**
     * sets whether the value is required or not
     *
     * @param  bool  $isRequired
     */
    public function setRequired($isRequired);

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    public function getFilter();
}
?>