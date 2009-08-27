<?php
/**
 * Interface for a page element.s
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::ipo::response::stubBaseResponse'
);
/**
 * Interface for a page element.
 *
 * @package     stubbles
 * @subpackage  websites
 */
interface stubPageElement extends stubObject
{
    /**
     * set the name of the element
     *
     * @param  string  $name
     */
    public function setName($name);

    /**
     * returns the name of the element
     *
     * @return  string
     */
    public function getName();

    /**
     * returns a list of required class names
     *
     * @return  array<string>
     */
    public function getRequiredClassNames();

    /**
     * initializes the page element
     *
     * @param   stubRequest   $request   the request data
     * @param   stubSession   $session   current session
     * @param   stubResponse  $response  contains response data
     * @param   array         $context   optional  additional context data
     * @return  bool
     */
    public function init(stubRequest $request, stubSession $session, stubResponse $response, array $context = array());

    /**
     * checks whether the page element is available or not
     *
     * @return  bool
     */
    public function isAvailable();

    /**
     * checks whether page element is cachable or not
     *
     * @return  bool
     */
    public function isCachable();

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars();

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles();

    /**
     * processes the page element
     *
     * Please note that an element should catch all exceptions and wrap them
     * into the response!
     *
     * @return  mixed  content for page element
     */
    public function process();
}
?>