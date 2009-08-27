<?php
/**
 * Serializes session data into xml result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::xml::generator::stubXMLGenerator'
);
/**
 * Serializes session data into xml result document.
 *
 * Default values are whether the session is new or not, the current and
 * the next token of the request:
 * <code>
 * <document>
 *   [...]
 *   <session>
 *     <acceptsCookies>true</acceptsCookies>
 *     <id>abc123</id>
 *     <name>PHPSESSID</name>
 *     <isNew>true</isNew>
 *     <variant>
 *       <name>foo</name>
 *       <alias>bar</alias>
 *     </variant>
 *   </session>
 *   [...]
 * </document>
 * </code>
 * Concrete session data will not be written into the result document.
 *
 * @package     stubbles
 * @subpackage  websites_xml_generator
 */
class stubSessionXMLGenerator extends stubBaseObject implements stubXMLGenerator
{
    /**
     * request instance to be used
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * session instance to be used
     *
     * @var  stubSession
     */
    protected $session;

    /**
     * constructor
     *
     * @param  stubRequest  $request
     * @param  stubSession  $session
     * @Inject
     */
    public function __construct(stubRequest $request, stubSession $session)
    {
        $this->request = $request;
        $this->session = $session;
    }

    /**
     * checks whether document part is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return true;
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return array('isNew'          => $this->session->isNew(),
                     'variant'        => (string) $this->session->getValue('net.stubbles.websites.variantmanager.variant.name'),
                     'acceptsCookies' => $this->request->acceptsCookies()
               );
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return array();
    }

    /**
     * serializes session data into result document
     *
     * @param  stubXMLStreamWriter  $xmlStreamWriter  writer to be used
     * @param  stubXMLSerializer    $xmlSerializer    serializer to be used
     */
    public function generate(stubXMLStreamWriter $xmlStreamWriter, stubXMLSerializer $xmlSerializer)
    {
        $xmlStreamWriter->writeStartElement('session');
        if ($this->request->acceptsCookies() === true) {
            $xmlStreamWriter->writeElement('acceptsCookies', array(), 'true');
        } else {
            $xmlStreamWriter->writeElement('acceptsCookies', array(), 'false');
        }
        
        $xmlStreamWriter->writeElement('id', array(), '$SESSION_ID');
        $xmlStreamWriter->writeElement('name', array(), '$SESSION_NAME');
        $xmlStreamWriter->writeElement('isNew', array(), (($this->session->isNew() === true) ? ('true') : ('false')));
        $xmlStreamWriter->writeStartElement('variant');
        $xmlStreamWriter->writeElement('name', array(), (string) $this->session->getValue('net.stubbles.websites.variantmanager.variant.name'));
        $xmlStreamWriter->writeElement('alias', array(), (string) $this->session->getValue('net.stubbles.websites.variantmanager.variant.alias'));
        $xmlStreamWriter->writeEndElement();  // end variant
        $xmlStreamWriter->writeEndElement();  // end session
    }
}
?>