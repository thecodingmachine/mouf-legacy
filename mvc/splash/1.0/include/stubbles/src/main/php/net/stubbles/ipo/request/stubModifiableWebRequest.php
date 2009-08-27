<?php
/**
 * Permits modifiying param values of the request.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 * @version     $Id: stubModifiableWebRequest.php 1756 2008-08-01 11:58:09Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::stubModifiableRequest',
                      'net::stubbles::ipo::request::stubWebRequest'
);
/**
 * Permits modifiying param values of the request.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
class stubModifiableWebRequest extends stubWebRequest implements stubModifiableRequest
{
    /**
     * modifies a param value
     *
     * @param  string  $key     name of param to modify
     * @param  string  $value   new value for param to modify
     * @param  int     $source  optional  param source type: cookie, header, param
     */
    public function setParam($key, $value, $source = stubRequest::SOURCE_PARAM)
    {
        switch ($source) {
            case stubRequest::SOURCE_PARAM:
                $this->unsecureParams[$key] = $value;
                break;
            
            case stubRequest::SOURCE_COOKIE:
                $this->unsecureCookies[$key] = $value;
                break;
            
            case stubRequest::SOURCE_HEADER:
                $this->unsecureHeaders[$key] = $value;
                break;

            default:
                $this->unsecureParams[$key] = $value;
        }
        
    }
}
?>