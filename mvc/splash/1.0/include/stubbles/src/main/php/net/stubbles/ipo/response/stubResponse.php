<?php
/**
 * interface for a response to a request
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_response
 */
stubClassLoader::load('net::stubbles::ipo::response::stubCookie');
/**
 * interface for a response to a request
 * 
 * The response collects all data that should be send to the source
 * that initiated the request.
 *
 * @package     stubbles
 * @subpackage  ipo_response
 */
interface stubResponse extends stubObject
{
    /**
     * registry key for response class to be used
     */
    const CLASS_REGISTRY_KEY = 'net.stubbles.ipo.response.class';

    /**
     * clears the response
     */
    public function clear();

    /**
     * sets the http version
     *
     * The version should be a string like '1.0' or '1.1'.
     *
     * @param  string  $version
     */
    public function setVersion($version);

    /**
     * returns the http version
     *
     * @return  string
     */
    public function getVersion();

    /**
     * sets the status code to be send
     *
     * This needs only to be done if another status code then the default one
     * should be send.
     *
     * @param  int     $statusCode
     * @param  string  $reasonPhrase  optional
     */
    public function setStatusCode($statusCode, $reasonPhrase = null);

    /**
     * returns status code to be send
     *
     * If return value is <null> the default one will be send.
     *
     * @return  int
     */
    public function getStatusCode();

    /**
     * add a header to the response
     *
     * @param  string  $name   the name of the header
     * @param  string  $value  the value of the header
     */
    public function addHeader($name, $value);

    /**
     * returns the list of headers
     *
     * @return  array<string,string>
     */
    public function getHeaders();

    /**
     * add a cookie to the response
     *
     * @param  stubCookie  $cookie  the cookie to set
     */
    public function setCookie(stubCookie $cookie);

    /**
     * returns the list of cookies
     *
     * @return  array<string,stubCookie>
     */
    public function getCookies();

    /**
     * write data into the response
     *
     * @param  string  $data
     */
    public function write($data);

    /**
     * returns the data written so far
     * 
     * @return  string
     */
    public function getData();

    /**
     * replaces the data written so far with the new data
     *
     * @param  string  $data
     */
    public function replaceData($data);

    /**
     * removes data completely
     */
    public function clearData();

    /**
     * send the response out
     */
    public function send();
}
?>