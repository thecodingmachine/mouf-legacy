<?php
/**
 * A class with a __static method to be used in the test of
 * net.stubbles.stubClassLoader.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * A class with a __static method to be used in the test of
 * net.stubbles.stubClassLoader.
 *
 * @package     stubbles
 * @subpackage  test
 */
class WithStatic
{
    private static $called = 0;
    
    public static function __static()
    {
        self::$called++;
    }
    
    public static function getCalled()
    {
        return self::$called;
    }
}
?>