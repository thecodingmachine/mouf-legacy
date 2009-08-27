<?php
/**
 * Class to create instances of the XSL processor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl
 * @version     $Id: stubXSLProcessorFactory.php 1911 2008-11-03 20:22:35Z mikey $
 */
stubClassLoader::load('net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::xml::stubXMLStreamWriterFactory',
                      'net::stubbles::xml::xsl::stubXSLProcessor'
);
/**
 * Class to create instances of the XSL processor.
 *
 * @package     stubbles
 * @subpackage  xml_xsl
 */
class stubXSLProcessorFactory extends stubBaseObject
{
    /**
     * creates an XSL processor
     *
     * @return  stubXSLProcessor
     */
    public static function create()
    {
        return new stubXSLProcessor();
    }

    /**
     * creates an XSL processor configured with callbacks
     *
     * If no config file is given the file $PROJECT/config/xsl-callbacks.ini
     * will be used.
     *
     * @param   string            $callbackConfigFile  optional  file with configured callbacks
     * @return  stubXSLProcessor
     * @throws  stubRuntimeException
     */
    public static function createWithCallbacks($callbackConfigFile = null)
    {
        if (null === $callbackConfigFile) {
            $callbackConfigFile = stubConfig::getConfigPath() . '/xsl-callbacks.ini';
        }
        
        if (file_exists($callbackConfigFile) === false) {
            throw new stubRuntimeException('Configuration file ' . $callbackConfigFile . ' for XSL callback configuration is missing.');
        }
        
        $binder = stubBinderRegistry::get();
        $binder->bind('stubXMLStreamWriter')->to(stubXMLStreamWriterFactory::getFqClassNameAsAvailable());
        $binder->bindConstant()->named('imagePath')->to(getcwd());
        $injector     = $binder->getInjector();
        $xslProcessor = new stubXSLProcessor();
        foreach (parse_ini_file($callbackConfigFile) as $callbackName => $callbackClass) {
            $xslProcessor->usingCallback($callbackName, $injector->getInstance($callbackClass));
        }
        
        return $xslProcessor;
    }
}
?>