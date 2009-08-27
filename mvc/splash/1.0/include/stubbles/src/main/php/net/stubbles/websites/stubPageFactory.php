<?php
/**
 * Interface for a page factory: returns a configured stubPage instance.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::websites::stubPage'
);
/**
 * Interface for a page factory: returns a configured stubPage instance.
 *
 * @package     stubbles
 * @subpackage  websites
 */
interface stubPageFactory extends stubObject
{

    /**
     * sets page name prefix
     *
     * @param  string  $pagePrefix
     */
    public function setPagePrefix($pagePrefix);

    /**
     * sets name of home page
     *
     * @param  string  $indexPageName
     */
    public function setIndexPageName($indexPageName);

    /**
     * sets the name of the request parameter containing the page name
     *
     * @param  string  $requestParamName
     */
    public function setRequestParamName($requestParamName);

    /**
     * retrieves the page name
     *
     * @param   stubRequest  $request
     * @return  string
     */
    public function getPageName(stubRequest $request);

    /**
     * checks whether the page factory knows the page or not
     *
     * @param   string  $pageName  name of the page to check for
     * @return  bool
     */
    public function hasPage($pageName);

    /**
     * returns the configured stubPage instance
     *
     * @param   string    $pageName  name of the page to retrieve
     * @return  stubPage
     */
    public function getPage($pageName);
}
?>