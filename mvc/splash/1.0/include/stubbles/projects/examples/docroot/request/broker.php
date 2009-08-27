<?php
/**
 * Example for using the request broker
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  request
 * @see         http://www.stubbles.net/wiki/Docs/Request/Broker
 */
require '../bootstrap-stubbles.php';

stubClassLoader::load('net::stubbles::ipo::request::stubWebRequest',
                      'net::stubbles::ipo::request::broker::stubRequestBroker',
                      'net::stubbles::ipo::request::filter::stubFilterFactory'
);
/**
 * a simple plain old php object class
 *
 * @package     stubbles_examples
 * @subpackage  request
 */
class SimplePopo
{
    /**
     * test property
     *
     * @var  int
     * @Filter[IntegerFilter](fieldName='foo', minValue=2, maxValue=4)
     */
    public $foo         = null;
    /**
     * test property
     *
     * @var  string
     */
    protected $bar      = null;

    /**
     * test method
     *
     * @var  string  $bar
     * @Filter[StringFilter](fieldName='bar', regex='/(.*)/', minLength=5)
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * test method
     *
     * @return  string
     */
    public function getBar()
    {
        return $this->bar;
    }
}
class Bootstrap
{
    public static function main()
    {
        $simplePopo = new SimplePopo();
        $request    = new stubWebRequest();
        if ($request->hasValue('action') == true) {
            $requestBroker = new stubRequestBroker();
            $requestBroker->process($request, $simplePopo);
        }
        
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><title>Using the request broker</title></head><body>';
        echo '<h1>Using the request broker</h1>' . "\n";
        echo "<form action=\"broker.php\" method=\"post\">\n";
        if ($request->hasValueError('foo') == true) {
            foreach ($request->getValueError('foo') as $valueError) {
                echo '<span style="color:#FF0000">' . $valueError->getMessage('en_*') . "</span><br/>\n";
            }
        }
        
        echo '<label for="foo">Enter an integer value between 2 and 4:</label> <input type="text" id="foo" name="foo" value="' . $request->getFilteredValue(stubFilterFactory::forType('string'), 'foo') . '"/><br/>' . "\n";
        if ($request->hasValueError('bar') == true) {
            foreach ($request->getValueError('bar') as $valueError) {
                echo '<span style="color:#FF0000">' . $valueError->getMessage('en_*') . "</span><br/>\n";
            }
        }
        
        echo '<label for="bar">Enter any string with at least 5 characters:</label> <input type="text" id="bar" name="bar" value="' . $request->getFilteredValue(stubFilterFactory::forType('string'), 'bar') . '" /><br/>' . "\n";
        echo '<input type="submit" name="action" value="Send"></form><br/><br/>' . "\n";
        echo 'The SimplePopo class contents:<br/><pre>';
        echo htmlspecialchars(print_r($simplePopo, true));
        echo '</pre><br/><a href="../index.php">Back to examples list.</a></body></html>';
        
    }
}
Bootstrap::main();
?>