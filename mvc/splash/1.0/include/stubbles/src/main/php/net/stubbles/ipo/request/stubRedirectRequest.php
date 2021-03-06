<?php
/**
 * Request implementation for processing redirected requests.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 * @version     $Id: stubRedirectRequest.php 1918 2008-11-07 13:42:42Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::stubWebRequest');
/**
 * Request implementation for processing redirected requests.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
class stubRedirectRequest extends stubWebRequest
{
    /**
     * post initialization of redirect parameters
     *
     * This method initialize parameters, instead of fetching thoose from HTTP
     * GET and POST headers, it access a special HTTP header and fetching from
     * there parameters.
     */
    protected function doConstuct()
    {
        if (isset($_SERVER['REDIRECT_QUERY_STRING']) === true) {
            parse_str($_SERVER['REDIRECT_QUERY_STRING'], $this->unsecureParams);
        } else {
            $this->unsecureParams = $_GET;
        }
        
        $this->unsecureHeaders = $_SERVER;
        $this->unsecureCookies = $_COOKIE;
    }
}
?>