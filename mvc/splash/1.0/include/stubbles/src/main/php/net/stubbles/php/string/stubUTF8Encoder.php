<?php
/**
 * Encoder/decoder for UTF-8.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  php_string
 */
stubClassLoader::load('net::stubbles::php::string::stubAbstractStringEncoder');
/**
 * Encoder/decoder for UTF-8.
 *
 * @package     stubbles
 * @subpackage  php_string
 */
class stubUTF8Encoder extends stubAbstractStringEncoder
{
    /**
     * encodes a string
     *
     * @param   string  $string
     * @return  string
     */
    public function encode($string)
    {
        // prevent that numbers, booleans or anything else will be converted
        // into a string
        if (is_string($string) === false) {
            return $string;
        }
        
        return utf8_encode($string);
    }

    /**
     * decodes a string
     *
     * @param   string  $string
     * @return  string
     */
    public function decode($string)
    {
        // prevent that numbers, booleans or anything else will be converted
        // into a string
        if (is_string($string) === false) {
            return $string;
        }
        
        return utf8_decode($string);
    }
}
?>