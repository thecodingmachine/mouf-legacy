<?php
/**
 * XJConf bootstrap file.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::util::xjconf::stubXJConfException',
                      'net::stubbles::util::xjconf::stubXJConfFacade',
                      'net::stubbles::util::xjconf::stubXJConfInitializer',
                      'net::stubbles::util::xjconf::stubXJConfAbstractInitializer',
                      'net::stubbles::util::xjconf::stubXJConfProxy'
);
?>