<?php
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::websites::stubDefaultWebsiteInitializer',
                      'net::stubbles::websites::stubFrontController'
);

class Bootstrap
{
    public static function main()
    {
        $controller = new stubFrontController(new stubDefaultWebsiteInitializer());
        $controller->process();
    }
}
Bootstrap::main();
?>