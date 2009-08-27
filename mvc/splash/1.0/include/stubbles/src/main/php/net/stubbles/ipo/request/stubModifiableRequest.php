<?php
/**
 * Permits modifiying param values of the request.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 * @version     $Id: stubModifiableRequest.php 1756 2008-08-01 11:58:09Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest');
/**
 * Permits modifiying param values of the request.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
interface stubModifiableRequest extends stubRequest
{
    /**
     * modifies a param value
     *
     * @param  string  $key     name of param to modify
     * @param  string  $value   new value for param to modify
     * @param  int     $source  optional  param source type: cookie, header, param
     */
    public function setParam($key, $value, $source = stubRequest::SOURCE_PARAM);
}
?>