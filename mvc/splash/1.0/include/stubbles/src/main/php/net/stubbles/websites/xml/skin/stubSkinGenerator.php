<?php
/**
 * Interface to generate the skin to be applied onto the XML result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
stubClassLoader::load('net::stubbles::ipo::session::stubSession',
                      'net::stubbles::websites::stubPage'
);
/**
 * Interface to generate the skin to be applied onto the XML result document.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin
 */
interface stubSkinGenerator extends stubObject
{
    /**
     * checks whether a given skin exists
     *
     * @param   string  $skinName
     * @return  bool
     */
    public function hasSkin($skinName);

    /**
     * returns the key for the skin to be generated
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  string
     */
    public function getSkinKey(stubSession $session, stubPage $page, $skinName);

    /**
     * generates the skin document
     *
     * @param   stubSession  $session
     * @param   stubPage     $page
     * @param   string       $skinName
     * @return  DOMDocument
     */
    public function generate(stubSession $session, stubPage $page, $skinName);
}
?>