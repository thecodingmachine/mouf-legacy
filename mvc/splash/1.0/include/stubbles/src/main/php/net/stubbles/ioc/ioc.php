<?php
/**
 * Injection handling bootstrap file.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
// @codeCoverageIgnoreStart
stubClassLoader::load('net::stubbles::ioc::stubBinder');
// @codeCoverageIgnoreEnd
?>