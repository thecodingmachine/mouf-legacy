<?php
/**
 * Testing script for converting docbook to HTML.
 *
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  stubbles_doc
 * @version  $Id: convert.php 1742 2008-07-27 22:18:01Z mikey $
 */
require '../projects/dist/config/php/config.php';
require_once stubConfig::getLibPath() . '/starWriter.php';
require_once stubConfig::getSourcePath() . '/php/net/stubbles/stubClassLoader.php';
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLProcessor');

echo "Starting xsl conversion process...\n";
stubXSLProcessor::applyStylesheetFromFile(dirname(__FILE__) . '/manual/styles/xhtml_chunked.xsl')
                ->onXMLFile(dirname(__FILE__) . '/manual/content/index.xml')
                ->withParameter('', 'base.dir', dirname(__FILE__) . '/manual/out/')
                ->toXML();
echo "OK\n";
?>