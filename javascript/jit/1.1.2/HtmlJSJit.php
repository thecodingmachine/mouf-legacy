<?php

/**
 * Writes the scripts tags that include the Jit library.
 * 
 * The Jit library is a Javascript library used for drawing graphs.
 * The documentation can be found here: <a href="http://thejit.org/">The JIT website</a>
 *
 * @Component
 */
class HtmlJSJit implements HtmlElementInterface {

	public function toHtml() {
		
?>
<!--[if IE]><script language="javascript" type="text/javascript" src="<?php  echo ROOT_URL ?>plugins/javascript/jit/1.1.2/jit/Extras/excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php  echo ROOT_URL ?>plugins/javascript/jit/1.1.2/jit/jit.js"></script>
<?php 

	}
}

?>