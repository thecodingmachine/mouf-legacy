<?php
/**
 * Class for storing values.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang
 */
/**
 * Class for storing values.
 *
 * @static
 * @package     stubbles
 * @subpackage  lang
 */
class stubRegistry
{
    /**
     * config values
     *
     * @var  array<string,mixed>
     */
    private static $config   = array();
    /**
     * the registry data itsself
     *
     * @var  array<string,mixed>
     */
    private static $registry = array();

    /**
     * store the given value under the given key
     *
     * @param  string  $key    key to store value under
     * @param  mixed   $value  value to store
     */
    public static function set($key, $value)
    {
        self::$registry[$key] = $value;
    }

    /**
     * store the given value under the given key for the given module
     *
     * @param  string  $key    key to store value under
     * @param  mixed   $value  value to store
     */
    public static function setConfig($key, $value)
    {
        self::$config[$key] = $value;
    }

    /**
     * removes a value from the registry
     *
     * @param  string  $key  key where the value is stored under
     */
    public static function remove($key)
    {
        if (isset(self::$registry[$key]) == true) {
            unset(self::$registry[$key]);
        }
    }

    /**
     * removes a value from the config
     *
     * @param  string  $key  key where the value is stored under
     */
    public static function removeConfig($key)
    {
        if (isset(self::$config[$key]) == true) {
            unset(self::$config[$key]);
        }
    }

    /**
     * check if a value exists under the given key
     *
     * @param   string  $key    key where the value is stored under
     * @return  bool    true if a value exists under the given key, else false
     */
    public static function has($key)
    {
        return isset(self::$registry[$key]);
    }

    /**
     * return the value stored under the given key, if key does not exist it returns null
     *
     * @param   string  $key      key where the value is stored under
     * @param   mixed   $default  optional  default value to return if $key not set
     * @return  mixed
     */
    public static function get($key, $default = null)
    {
        if (isset(self::$registry[$key]) == true) {
            return self::$registry[$key];
        }
        
        return $default;
    }

    /**
     * check if a value exists under the given key
     *
     * @param   string  $key  key where the value is stored under
     * @return  bool    true if a value exists under the given key for the given module, else false
     */
    public static function hasConfig($key)
    {
        if (isset(self::$config[$key]) == true) {
            return true;
        }
        
        return false;
    }

    /**
     * return the value stored under the given key, if key in module does not exist it returns null
     *
     * @param   string  $key      key where the value is stored under
     * @param   mixed   $default  optional  default value to return if $key not set
     * @return  mixed
     */
    public static function getConfig($key, $default = null)
    {
        if (isset(self::$config[$key]) == true) {
            return self::$config[$key];
        }
        
        return $default;
    }

    /**
     * returns all registry values
     *
     * @return  array<string,mixed>
     */
    public static function getKeys()
    {
        return array_keys(self::$registry);
    }

    /**
     * returns all configuration values
     *
     * @return  array<string,mixed>
     */
    public static function getConfigKeys()
    {
        return array_keys(self::$config);
    }
}
?>