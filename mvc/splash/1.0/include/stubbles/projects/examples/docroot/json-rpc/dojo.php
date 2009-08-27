<?php
/**
 * Example HTML page to demonstrate the JSON-RPC processor.
 * *
 * @author  Stephan Schmidt <schst@stubbles.net>
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
  <title>JSON-RPC DOJO example</title>
  <script type="text/javascript" src="http://o.aolcdn.com/dojo/1.0.0/dojo/dojo.xd.js"></script>
  <script type="text/javascript">
  djConfig.usePlainJson = true;
  dojo.require('dojo.rpc.JsonService');
  var proxy;
  function setup() {
    var smdURL = '<?php echo dirname($_SERVER['PHP_SELF']);?>/jsonrpc.php?processor=jsonrpc&__smd=MathService';
    proxy = new dojo.rpc.JsonService(smdURL);
  }

  function doCalc() {
     proxy.add(document.getElementById('a').value, document.getElementById('b').value).addCallback(doCalcCallback);
  }

  function doCalcCallback(result) {
      alert(result);
  }

  dojo.addOnLoad(setup);
  </script>
</head>
<body>
<h1>JSON-RPC DOJO example</h1>
<p>
  This example shows, how Stubbles JSON-RPC functionality can be used with
  Dojo's JSON-RPC package. The example makes use of the integrated SMD generator.
</p>
<p>
  See the <a href="http://dojotoolkit.org/book/dojo-book-0-9/part-3-programmatic-dijit-and-dojo/ajax-transports/remote-procedure-call-rpc" target="_blank">Dojo documentation</a> for more information
  on JSON-RPC with the Dojo toolkit.
</p>
<fieldset>
  <legend>Simple JSON-RPC example</legend>
  A: <input type="text" id="a" size="3"/> B: <input type="text" id="b" size="3"/><br/>
  <input type="button" onclick="doCalc();" value="A+B"/>
</fieldset>
<body>
</body>
</html>