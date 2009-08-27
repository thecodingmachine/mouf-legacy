<?php
/**
 * Marker interface for logger factories.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::lang::initializer::stubInitializer');
/**
 * Marker interface for logger factories.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
interface stubLoggerInitializer extends stubInitializer
{
    // intentionally empty
}
?>