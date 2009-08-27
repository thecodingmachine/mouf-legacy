<?php
/**
 * Interface for objects that can be serialized.
 * 
 * @author      Frank Kleine  <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_serialize
 */
/**
 * Interface for objects that can be serialized.
 * 
 * @package     stubbles
 * @subpackage  lang_serialize
 */
interface stubSerializable extends stubObject
{
    /**
     * returns a serialized representation of the class
     * 
     * @return  stubSerializedObject
     */
    public function getSerialized();
}
?>