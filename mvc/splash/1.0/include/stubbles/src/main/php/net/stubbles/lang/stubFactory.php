<?php
/**
 * Class that holds informations used throughout a bunch of other classes.
 * 
 * @author      Frank Kleine  <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang
 */
/**
 * Class that holds informations used throughout a bunch of other classes.
 * 
 * @static
 * @package     stubbles
 * @subpackage  lang
 */
class stubFactory
{
    /**
     * uri to resource files
     *
     * @var  string
     */
    private static $resourcePath = null;
    /**
     * switch whether to use star files or not
     *
     * @var  bool
     */
    private static $useStar      = null;
    
    /**
     * static initializing
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$resourcePath = stubConfig::getSourcePath() . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        self::$useStar      = class_exists('StarClassRegistry', false);
    }
    // @codeCoverageIgnoreEnd
    
    /**
     * return the uris for a resource
     *
     * @param   string  $fileName  the resource to retrieve the uris for
     * @return  array<string>
     */
    public static function getResourceURIs($fileName)
    {
        if (true == self::$useStar) {
            $uris = StarClassRegistry::getUrisForResource($fileName);
        } else {
            $uris = array();
        }
        
        if (file_exists(self::$resourcePath . $fileName) == true) {
            $uris[] = self::$resourcePath . $fileName;
        }
        
        return $uris;
    }
    
    /**
     * returns the uri for a resource in the real resource path
     *
     * @param   string  $fileName  the resource to retrieve the path for
     * @return  string
     */
    public static function getFileResourceURI($fileName)
    {
        return self::$resourcePath . $fileName;
    }
}
?>