<?php
/**
 * Logging bootstrap file.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogAppender',
                      'net::stubbles::util::log::stubLogData',
                      'net::stubbles::util::log::stubLogDataFactory',
                      'net::stubbles::util::log::stubLogger'
);
?>