<?php

/**
 * Every object extending this interface can be rendered in an HTML page, using the toHtml function.
 *
 */
interface HtmlElementInterface {
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml();
}
?>