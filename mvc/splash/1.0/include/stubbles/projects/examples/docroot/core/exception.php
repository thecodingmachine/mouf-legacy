<?php
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::lang::errorhandler::stubProdModeExceptionHandler',
                      'net::stubbles::lang::errorhandler::stubDisplayExceptionHandler',
                      'net::stubbles::lang::stubRegistry'
);
class Bootstrap
{
    public static function main()
    {
        if (isset($_GET['test']) === true) {
            $exceptionHandler = new stubDisplayExceptionHandler();
            echo '<pre>';
        } else {
            $exceptionHandler = new stubProdModeExceptionHandler();
        }
        
        $exceptionHandler->setLogging(false);
        set_exception_handler(array($exceptionHandler, 'handleException'));
        throw new stubException('This is an exception');
        echo 'This will never be displayed.';
    }
}
Bootstrap::main();
?>