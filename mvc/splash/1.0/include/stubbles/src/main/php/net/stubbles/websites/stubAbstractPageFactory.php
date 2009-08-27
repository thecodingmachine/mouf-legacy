<?php
/**
 * Abstract base implementation a page factory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::websites::stubPageFactory'
);
/**
 * Abstract base implementation a page factory.
 *
 * @package     stubbles
 * @subpackage  websites
 */
abstract class stubAbstractPageFactory extends stubBaseObject implements stubPageFactory
{
    /**
     * prefix for page names
     *
     * @var  string
     */
    protected $pagePrefix       = null;
    /**
     * name of home page
     *
     * @var  string
     */
    protected $indexPageName    = 'index';
    /**
     * name of request param containing the page name
     *
     * @var  string
     */
    protected $requestParamName = 'page';

    /**
     * sets page name prefix
     *
     * @param  string  $pagePrefix
     */
    public function setPagePrefix($pagePrefix)
    {
        $this->pagePrefix = $pagePrefix;
    }

    /**
     * sets name of home page
     *
     * @param  string  $indexPageName
     */
    public function setIndexPageName($indexPageName)
    {
        $this->indexPageName = $indexPageName;
    }

    /**
     * sets the name of the request parameter containing the page name
     *
     * @param  string  $requestParamName
     */
    public function setRequestParamName($requestParamName)
    {
        $this->requestParamName = $requestParamName;
    }

    /**
     * retrieves the page name
     *
     * @param   stubRequest  $request
     * @return  string
     */
    public function getPageName(stubRequest $request)
    {
        if ($request->hasValue($this->requestParamName) === true) {
            $pageName = $request->getValidatedValue(new stubRegexValidator('/([a-zA-Z0-9_])/'), $this->requestParamName);
            if (null != $pageName && $this->hasPage($pageName) === true) {
                return $pageName;
            }
        }

        return $this->indexPageName;
    }

    /**
     * returns the configured stubPage instance
     *
     * @param   string    $pageName  name of the page to retrieve
     * @return  stubPage
     */
    public function getPage($pageName)
    {
        $page = $this->doGetPage($pageName);
        $page->setProperty('name', $pageName);
        $page->setProperty('fqname', $this->pagePrefix . $pageName);
        return $page;
    }
    
    
    /**
     * returns the configured stubPage instance
     *
     * @param   string    $pageName  name of the page to retrieve
     * @return  stubPage
     */
    protected abstract function doGetPage($pageName);
}
?>