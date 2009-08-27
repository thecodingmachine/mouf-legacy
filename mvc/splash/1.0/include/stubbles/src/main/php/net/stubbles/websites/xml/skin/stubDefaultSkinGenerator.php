<?php
/**
 * Default implementation to generate the skin to be applied onto the XML result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin
 * @version     $Id: stubDefaultSkinGenerator.php 1904 2008-10-25 14:04:33Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::stubFactory',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::xml::skin::stubSkinGenerator',
                      'net::stubbles::xml::stubXMLException',
                      'net::stubbles::xml::stubXMLXIncludeStreamWrapper',
                      'net::stubbles::xml::xsl::stubXSLProcessorFactory'
);
/**
 * Default implementation to generate the skin to be applied onto the XML result document.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
class stubDefaultSkinGenerator extends stubBaseObject implements stubSkinGenerator
{
    /**
     * checks whether a given skin exists
     *
     * @param   string  $skinName
     * @return  bool
     */
    public function hasSkin($skinName)
    {
        return file_exists(stubConfig::getPagePath() . '/skin/' . $skinName . '.xml');
    }

    /**
     * returns the key for the skin to be generated
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  string
     */
    public function getSkinKey(stubSession $session, stubPage $page, $skinName)
    {
        return md5($skinName . $page->getProperty('name') . $this->getLanguage($session, $page));
    }

    /**
     * returns the currently selected language
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @return  string
     */
    protected function getLanguage(stubSession $session, stubPage $page)
    {
        if ($session->hasValue('net.stubbles.language') == true) {
            return $session->getValue('net.stubbles.language');
        }
        
        if ($page->hasProperty('language') == true) {
            return $page->getProperty('language');
        }
        
        if (stubRegistry::hasConfig('net.stubbles.language') == true) {
            return stubRegistry::getConfig('net.stubbles.language');
        }

        return 'en_EN';
    }

    /**
     * generates the skin document
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  DOMDocument
     */
    public function generate(stubSession $session, stubPage $page, $skinName)
    {
        $lang         = $this->getLanguage($session, $page);
        $xslProcessor = $this->createXSLProcessor();
        $xslProcessor->andApplyStylesheet($this->createXSLStylesheet())
                     ->withParameter('', 'page', $page->getProperty('name'))
                     ->withParameter('', 'lang', $lang)
                     ->withParameter('', 'lang_base', substr($lang, 0, strpos($lang, '_')) . '_*');
        stubXMLXIncludeStreamWrapper::register();
        stubXMLXIncludeStreamWrapper::addIncludePath('default', stubConfig::getPagePath() . '/txt');
        if (stubRegistry::hasConfig('net.stubbles.websites.xml.skin.common') === true) {
            stubXMLXIncludeStreamWrapper::addIncludePath('common', stubRegistry::getConfig('net.stubbles.websites.xml.skin.common'));
        }
        
        stubXMLXIncludeStreamWrapper::setCachePath(stubConfig::getCachePath());
        stubXMLXIncludeStreamWrapper::setXSLProcessor($xslProcessor);

        // first we create the xsl with applying the master.xsl on the skin and the page
        $resultXSL = $xslProcessor->onDocument($this->createXMLSkinDocument($skinName))
                                  ->toDoc();
        @$resultXSL->xinclude();
        return $resultXSL;
    }

    /**
     * creates a stubXSLProcessor instance
     *
     * @return  stubXSLProcessor
     */
    // @codeCoverageIgnoreStart
    protected function createXSLProcessor()
    {
        return stubXSLProcessorFactory::createWithCallbacks();
    }
    // @codeCoverageIgnoreEnd

    /**
     * creates the xsl stylesheet
     *
     * @return  DOMDocument
     * @todo    fix selection of uri
     */
    // @codeCoverageIgnoreStart
    protected function createXSLStylesheet()
    {
        $uris = stubFactory::getResourceURIs('xsl/master.xsl');
        $domDocument = new DOMDocument();
        $domDocument->load($uris[0]);
        return $domDocument;
    }
    // @codeCoverageIgnoreEnd

    /**
     * creates the skin document
     *
     * @param   string       $skinName
     * @return  DOMDocument
     * @throws  stubXMLException
     */
    // @codeCoverageIgnoreStart
    protected function createXMLSkinDocument($skinName)
    {
        $domDocument = new DOMDocument();
        if (false === $domDocument->load(stubConfig::getPagePath() . '/skin/' . $skinName . '.xml')) {
            throw new stubXMLException('Invalid xml file ' . stubConfig::getPagePath() . '/skin/' . $skinName . '.xml');
        }
        
        return $domDocument;
    }
    // @codeCoverageIgnoreEnd
}
?>