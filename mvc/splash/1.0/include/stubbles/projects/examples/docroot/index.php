<?php
$propFile = '../examples.properties';
if (!file_exists($propFile)) {
    $examplesSetUp = false;
} else {
    $examplesSetUp = true;
}
?>
<html>
  <head>
    <title>Stubbles examples</title>
    <style type="text/css">
      body {
        font: normal 13px verdana,arial,'Bitstream Vera Sans',helvetica,sans-serif;
      }
      h1, h2, h3, h4 {
       font-family: arial,verdana,'Bitstream Vera Sans',helvetica,sans-serif;
       font-weight: bold;
       letter-spacing: -0.018em;
      }
      h1 { font-size: 19px; margin: 18px 0 0 0 }
      h2 { font-size: 16px }
      h3 { font-size: 14px }
      /* Link styles */
      :link, :visited {
       text-decoration: none;
       color: #b00;
       border-bottom: 1px dotted #bbb;
      }
      :link:hover, :visited:hover {
       background-color: #eee;
       color: #555;
      }
      p {
       margin-left: 25px;
      }
      div.warning {
       border: 2px solid #c00;
       color: #900;
       padding: 12px;
       margin: 25px;
      }
    </style>
  </head>
  <body>
    <img src="data/images/stubbles.png" alt="" style="padding: 2px;">
    <div style="text-align: right; padding: 0 5px  0 5px; font-size: 11px; margin: 13px 0 0 2px; background: url(http://stubbles.net/chrome/common/topbar_gradient.png); height: 16px; border: 1px #000 solid;">
      <a href="http://www.stubbles.net/">Go to Stubbles Homepage</a>
    </div>
    <h1 style="font-size: 21px;">Stubbles examples</h1>
<?php
if (!$examplesSetUp):
?>
    <div class="warning">
      <div style="font-weight: bold; margin-bottom: 12px;">Warning:</div>
      The Stubbles examples have not been set up correctly. Some of the examples might not work as expected.<br />
      Please run <code>phing|stubbles (stubbles src/own project) setup-examples</code> in the Stubbles root
      folder to initialize the examples collection. Keep in mind to run <code>phing|stubbles setup-project</code>
      before that.
      <div style="margin-top: 16px;">
        <a href="index.php">retry...</a>
      </div>
    </div>
<?php
endif;
?>
    <p>Please select the desired example:</p>
    <ul>
      <li>
        Core functions
        <ul>
          <li>
            <a href="core/error-simple.php">Using the error handler</a> (<a href="showsource.php?group=core&example=error-simple">show PHP source</a>)
          </li>
          <li>
            <a href="core/exception.php">Using the exception handler</a> (<a href="core/exception.php?test=1">Result with net.stubbles.mode=test</a>) (<a href="showsource.php?group=core&example=exception">show PHP source</a>)
          </li>
          <li>
            <a href="logging/">Logging data</a>
          </li>
        </ul>
      </li>
      <li>
        Reflection
        <ul>
          <li>
            <a href="reflection/annotations.php">Defining annotations</a> (<a href="showsource.php?group=reflection&example=annotations">show PHP source</a>)
          </li>
        </ul>
      </li>
      <li>
        Request handling
        <ul>
          <li>
            <a href="request/broker.php">Using the request broker</a> (<a href="showsource.php?group=request&example=broker">show PHP source</a>)
          </li>
        </ul>
      </li>
      <li>
        JSON-RPC
        <ul>
          <li>
            <a href="json-rpc/">Out-of-the-box AJAX with the JSON-RPC processor</a> (<a href="showsource.php?group=json-rpc">show HTML/PHP source</a>, <a href="showsource.php?group=json-rpc&example=jsonrpc">show service source</a>)
          </li>
          <li>
            <a href="json-rpc/dojo.php">Connecting Stubbles JSON-RPC with the Dojo toolkit</a> (<a href="showsource.php?group=json-rpc&example=dojo">show HTML/PHP source</a>)
          </li>
        </ul>
      </li>
      <li>
        Processors
        <ul>
          <li>
            <a href="websites-memphis/?processor=page">Memphis processor</a> (<a href="showsource.php?group=websites-memphis&example=index">show PHP source</a>)
          </li>
          <li>
            <a href="websites-xml/?processor=xml">XML processor</a> (<a href="showsource.php?group=websites-xml&example=index">show PHP source</a>)
          </li>
          <li>
            <a href="websites-xml/?processor=xml&page=shop">XML processor with Form</a> (<a href="showsource.php?group=websites-memphis&example=index">show PHP source</a>)
          </li>
        </ul>
      </li>
      <li>
        XML handling
        <ul>
          <li>
            <a href="xml/streamWriter.php">stubXMLStreamWriter</a> (<a href="showsource.php?group=xml&example=streamWriter">show PHP source</a>)
          </li>
          <li>
            <a href="xml/serializer.php">stubXMLSerializer</a> (<a href="showsource.php?group=xml&example=serializer">show PHP source</a>)
          </li>
        </ul>
      </li>
      <li>
        Misc features
        <ul>
          <li>
            <a href="variants/variants.php">Using variants</a> (<a href="showsource.php?group=variants&example=variants">show PHP source</a>)
          </li>
        </ul>
      </li>
    </ul>

    <h1 style="font-size: 21px;">Conference examples</h1>
    <p>
        The following examples have been created for various conferences.
    </p>
    <ul>
      <li>
        <a href="http://www.slideshare.net/stubbles/declarative-development-using-annotations-in-php">Declarative Development with Annotations</a> at the International PHP 2007 Conference in Ludwigsburg
        <ul>
          <li>
            <a href="ipc07/xmlSerializer.php">Serializing to XML</a> (<a href="showsource.php?group=ipc07&example=xmlSerializer">show PHP source</a>)
          </li>
          <li>
            <a href="ipc07/csvWriter.php">Serializing to CSV</a> (<a href="showsource.php?group=ipc07&example=csvWriter">show PHP source</a>)
          </li>
        </ul>
      </li>
    </ul>
  </body>
</html>