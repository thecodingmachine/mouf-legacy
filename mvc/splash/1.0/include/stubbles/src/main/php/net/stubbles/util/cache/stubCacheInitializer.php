<?php
/**
 * Marker interface for initializing the cache.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_cache
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer');
/**
 * Marker interface for initializing the cache.
 *
 * @package     stubbles
 * @subpackage  util_cache
 */
interface stubCacheInitializer extends stubInitializer
{
    // intentionally empty
}
?>