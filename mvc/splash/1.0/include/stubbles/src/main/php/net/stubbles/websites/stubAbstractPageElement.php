<?php
/**
 * Base class for a page element.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 * @version     $Id: stubAbstractPageElement.php 1909 2008-10-28 15:51:19Z mikey $
 */
stubClassLoader::load('net::stubbles::websites::stubPageElement');
/**
 * Base class for a page element.
 *
 * @package     stubbles
 * @subpackage  websites
 */
abstract class stubAbstractPageElement extends stubBaseObject implements stubPageElement
{
    /**
     * name of the page element
     *
     * @var  string
     */
    protected $name;
    /**
     * the request
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * current session
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * the created response
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * additional context data
     *
     * @var  array<string,mixed>
     */
    protected $context;
    /**
     * switch whether internal initializing was done or not
     *
     * @var  bool
     */
    private $initialized = false;

    /**
     * set the name of the element
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * returns the name of the element
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * returns a list of required class names
     *
     * @return  array<string>
     */
    public function getRequiredClassNames()
    {
        return array();
    }

    /**
     * initializes the page element
     *
     * @param  stubRequest          $request   the request data
     * @param  stubSession          $session   current session
     * @param  stubResponse         $response  contains response data
     * @param  array<string,mixed>  $context   optional  additional context data
     */
    public function init(stubRequest $request, stubSession $session, stubResponse $response, array $context = array())
    {
        $this->request  = $request;
        $this->session  = $session;
        $this->response = $response;
        $this->context  = $context;
        if (false === $this->initialized) {
            $this->doInit();
            $this->initialized = true;
        }
    }

    /**
     * method for additional initialisation
     */
    protected function doInit()
    {
        // intentionally empty
    }

    /**
     * checks whether the page element is available or not
     *
     * @return  bool
     */
    public function isAvailable()
    {
        return true;
    }

    /**
     * checks whether page element is cachable or not
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
        return array();
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
}
?>