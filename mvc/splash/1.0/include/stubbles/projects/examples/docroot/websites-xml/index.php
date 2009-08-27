<?php
/**
 * Example for the XML processor
 *
 * @author  Frank Kleine <mikey@stubbles.net>
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @link    http://www.stubbles.net/wiki/Docs/MVC
 */

/**
 * Load Stubbles
 */
require '../bootstrap-stubbles.php';

stubClassLoader::load('net::stubbles::lang::initializer::stubGeneralInitializer',
                      'net::stubbles::websites::stubDefaultWebsiteInitializer',
                      'net::stubbles::websites::stubFrontController'
);

class Bootstrap
{
    public static function main()
    {
        $controller = new stubFrontController(new stubDefaultWebsiteInitializer(new stubGeneralInitializer(array('cache' => true)), stubMode::$DEV));
        $controller->process();
    }
}
Bootstrap::main();
?>