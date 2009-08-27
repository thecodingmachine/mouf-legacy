<?php
/**
 * use this class to configure your site
 */
class stubConfig
{
    /**
     * path to root of project
     *
     * @var  string
     */
    private static $rootPath   = null;
    /**
     * path to libary files
     *
     * @var  string
     */
    private static $libPath    = null;
    /**
     * path to source files
     *
     * @var  string
     */
    private static $sourcePath = null;
    /**
     * path to log files
     *
     * @var  string
     */
    private static $logPath    = null;
    /**
     * path to cache files
     *
     * @var  string
     */
    private static $cachePath  = null;
    /**
     * path to config files
     *
     * @var  string
     */
    private static $configPath = null;
    /**
     * path to page files
     *
     * @var  string
     */
    private static $pagePath   = null;
    /**
     * path to common files
     *
     * @var  string
     */
    private static $commonPath = null;

    /**
     * Returns the root path of the project.
     *
     * By default its /path/to/stubbles/.
     *
     * @return  string
     */
    public static function getRootPath()
    {
        if (null == self::$rootPath) {
            self::$rootPath = realpath(dirname(__FILE__) . '/../../../../');
        }

        return self::$rootPath;
    }

    /**
     * this method should return the path to the lib directory
     *
     * By default its /path/to/stubbles/lib.
     *
     * @return  string
     */
    public static function getLibPath()
    {
        if (null == self::$libPath) {
            self::$libPath = realpath(dirname(__FILE__) . '/../../../../lib');
        }
        
        return self::$libPath;
    }

    /**
     * this method should return the path to the source directory
     *
     * By default its /path/to/stubbles/src/main.
     *
     * @return  string
     */
    public static function getSourcePath()
    {
        if (null == self::$sourcePath) {
            self::$sourcePath = realpath(dirname(__FILE__) . '/../../../../src/main');
        }
        
        return self::$sourcePath;
    }

    /**
     * this method should return the path to the log directory
     *
     * By default its /path/to/stubbles/log.
     *
     * @return  string
     */
    public static function getLogPath()
    {
        if (null == self::$logPath) {
            self::$logPath = realpath(dirname(__FILE__) . '/../../log');
        }
        
        return self::$logPath;
    }

    /**
     * this method should return the path to the cache directory
     *
     * By default its /path/to/stubbles/cache.
     *
     * @return  string
     */
    public static function getCachePath()
    {
        if (null == self::$cachePath) {
            self::$cachePath = realpath(dirname(__FILE__) . '/../../cache');
        }
        
        return self::$cachePath;
    }

    /**
     * this method should return the path to the config directory
     *
     * By default its /path/to/stubbles/config.
     *
     * @return  string
     */
    public static function getConfigPath()
    {
        if (null == self::$configPath) {
            self::$configPath = realpath(dirname(__FILE__) . '/../');
        }
        
        return self::$configPath;
    }

    /**
     * this method should return the path to the pages directory
     *
     * By default its /path/to/stubbles/pages.
     *
     * @return  string
     */
    public static function getPagePath()
    {
        if (null == self::$pagePath) {
            self::$pagePath = realpath(dirname(__FILE__) . '/../../pages');
        }
        
        return self::$pagePath;
    }

    /**
     * this method should return the path to the common directory
     *
     * @return  string
     */
    public static function getCommonPath()
    {
        if (null == self::$commonPath) {
            self::$commonPath = realpath(dirname(__FILE__) . '/../../../common');
        }
        
        return self::$commonPath;
    }
}
?>