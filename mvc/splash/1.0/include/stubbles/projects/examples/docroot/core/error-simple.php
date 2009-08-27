<?php
/**
 * Example that demonstrates how to use the error
 * handling provided by Stubbles.
 *
 * In this example, E_RECOVERABLE errors that result
 * by an invalid parameter type are converted to
 * an InvalidArgumentException
 *
 * @author Frank Kleine <mikey@stubbles.net>
 * @author Stephan Schmidt <schst@stubbles.net>
 */

require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::lang::errorhandler::stubIllegalArgumentErrorHandler',
                      'net::stubbles::lang::errorhandler::stubCompositeErrorHandler',
                      'net::stubbles::ipo::response::stubBaseResponse'
);
class Bootstrap
{
    public static function main()
    {
        echo '<pre>';
        $composite = new stubCompositeErrorHandler();
        $composite->addErrorHandler(new stubIllegalArgumentErrorHandler());
        set_error_handler(array($composite, 'handle'));

        $response = new stubBaseResponse();

        // set cookie expects an instance of stubCookie
        // not a string
        $response->setCookie('foo');
    }
}
Bootstrap::main();
?>