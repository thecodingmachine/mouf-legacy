<?php
/**
 * All built-in scopes.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubBindingScopeSession',
                      'net::stubbles::ioc::stubBindingScopeSingleton'
);
/**
 * All built-in scopes.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubBindingScopes extends stubBaseObject
{
    /**
     * scope for singleton objects
     *
     * @var  stubBindingScopeSingleton
     */
    public static $SINGLETON;
    /**
     * scope for session resources
     *
     * @var  stubBindingScopeSession
     */
    public static $SESSION;

    /**
     * initialize all built-in scopes
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$SINGLETON = new stubBindingScopeSingleton();
        self::$SESSION   = new stubBindingScopeSession();
    }
    // @codeCoverageIgnoreEnd
}
?>