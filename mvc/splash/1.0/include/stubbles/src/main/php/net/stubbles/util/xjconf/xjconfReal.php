<?php
/**
 * XJConf bootstrap file.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::util::xjconf::stubXJConfClassLoader',
                      'net::stubbles::util::xjconf::stubXJConfLoader',
                      'net::stubbles::util::xjconf::stubConfigXJConfExtension',
                      'net::xjconf::XJConfFacade'
);
?>