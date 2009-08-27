<?php
/**
 * Extension for XJConf to load values from stubConfig.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::xjconf::ext::Extension'
);
/**
 * Extension for XJConf to load values from stubConfig.
 * 
 * This XJConf extension allows to use values from stubConfig within xml
 * configurations.
 * <code>
 * <?xml version="1.0" encoding="iso-8859-1"?>
 * <xj:configuration
 *  xmlns:xj="http://xjconf.net/XJConf"
 *  xmlns:cfg="http://stubbles.net/util/XJConf"
 *  xmlns="http://stubbles.net/util/log">
 *   <logger id="default" level="15">
 *     <logAppender type="net::stubbles::util::log::stubFileLogAppender">
 *       <cfg:stubConfig name="logDir" method="getLogPath" append="/files" />
 *     </logAppender>
 *   </logger>
 * </xj:configuration>
 * </code>
 * The above example will replace the <cfg:stubConfig /> with a tag named
 * logDir which has the value returned by stubConfig::getLogPath().
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf
 */
class stubConfigXJConfExtension extends stubBaseObject implements Extension
{
    /**
     * the namespace
     *
     * @var  string
     */
    private $namespace = 'http://stubbles.net/util/XJConf';
    
    /**
     * Get the namespace URI used by the extension
     * 
     * @return  string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * Process a start element
     * 
     * @param   XmlParser  $parser
     * @param   Tag        $tag
     * @throws  XJConfException
     */
    public function startElement(XmlParser $parser, Tag $tag)
    {
        // nothing to do here
    }
    
    /**
     * Process the end element
     * 
     * @param   XmlParser   $parser
     * @param   Tag         $tag
     * @return  GenericTag
     * @throws  XJConfException
     */
    public function endElement(XmlParser $parser, Tag $tag)
    {
        // add several values
        if ($tag->getName() === 'stubConfig' && $tag->hasAttribute('method') === true &&  $tag->hasAttribute('name') === true) {
            $methodName = $tag->getAttribute('method');
            $refClass = new ReflectionClass('stubConfig');
            if ($refClass->hasMethod($methodName) === false) {
                throw new XJConfException('The requested method ' . $methodName . ' is not available in class stubConfig.');
            }
            
            $resultTag = new GenericTag($tag->getAttribute('name'));
            $resultTag->setKey($tag->getAttribute('name'));
            $value = $refClass->getMethod($methodName)->invoke(null);
            if ($tag->hasAttribute('append') === true) {
                $value .= $tag->getAttribute('append');
            }
            
            $resultTag->setValue($value);
            return $resultTag;
        } elseif ($tag->getName() === 'regConfig' && $tag->hasAttribute('method') === true &&  $tag->hasAttribute('name') === true) {
            // add registry values
            $methodName = $tag->getAttribute('method');
            $refClass = new ReflectionClass('stubConfig');
            if ($refClass->hasMethod($methodName) === false) {
                throw new XJConfException('The requested method ' . $methodName . ' is not available in class stubConfig.');
            }
            
            $value = $refClass->getMethod($methodName)->invoke(null);
            if ($tag->hasAttribute('append') === true) {
                $value .= $tag->getAttribute('append');
            }
            
            stubRegistry::setConfig($tag->getAttribute('name'), $value);
        }
        
        return null;
    }
}
?>