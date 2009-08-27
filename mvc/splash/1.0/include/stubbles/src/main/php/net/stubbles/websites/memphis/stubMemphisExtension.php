<?php
/**
 * Page element for including a template file as content.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIOException',
                      'net::stubbles::websites::memphis::stubMemphisPageElement',
                      'net::stubbles::websites::memphis::stubMemphisTemplate'
);
/**
 * Page element for including a template file as content.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
interface stubMemphisExtension extends stubObject
{
    /**
     * constructor
     *
     * @param   stubRequest      $request   the request data
     * @param   stubSession      $session   current session
     * @param   stubResponse     $response  contains response data
     */
    #public function __construct(stubRequest $request, stubSession $session, stubResponse $response);

    /**
     * sets the context
     *
     * @param  array  $context  additional context data
     */
    public function setContext(array $context);

    /**
     * checks whether extension is cachable or not
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
     * processes the work within the extension
     */
    public function process();
}
?>