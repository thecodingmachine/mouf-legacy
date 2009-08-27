<?php
/**
 * Base class for a response to a request.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_response
 */
stubClassLoader::load('net::stubbles::ipo::response::stubResponse');
/**
 * Base class for a response to a request.
 *
 * This class can be used for responses in web environments. It
 * collects all data of the response and is able to send it back
 * to the source that initiated the request.
 *
 * @package     stubbles
 * @subpackage  ipo_response
 */
class stubBaseResponse extends stubBaseObject implements stubResponse
{
    /**
     * current php sapi
     *
     * @var  string
     */
    protected $sapi;
    /**
     * http version to be used
     *
     * @var  string
     */
    protected $version;
    /**
     * status code to be send
     *
     * @var  int
     */
    protected $statusCode;
    /**
     * status message to be send
     *
     * @var  string
     */
    protected $reasonPhrase;
    /**
     * list of headers for this response
     *
     * @var  array<string,string>
     */
    protected $headers       = array();
    /**
     * list of cookies for this response
     *
     * @var  array<string,stubCookie>
     */
    protected $cookies       = array();
    /**
     * data to send as body of response
     *
     * @var  string
     */
    protected $data;

    /**
     * constructor
     *
     * @param  string  $version  optional  http version
     * @param  string  $sapi     optional  current php sapi
     */
    public function __construct($version = '1.1', $sapi = PHP_SAPI)
    {
        $this->version = $version;
        $this->sapi    = $sapi;
    }

    /**
     * clears the response
     */
    public function clear()
    {
        $this->statusCode   = null;
        $this->reasonPhrase = null;
        $this->headers      = array();
        $this->cookies      = array();
        $this->data         = null;
    }

    /**
     * sets the http version
     *
     * The version should be a string like '1.0' or '1.1'.
     *
     * @param  string  $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * returns the http version
     *
     * @return  string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * sets the status code to be send
     *
     * This needs only to be done if another status code then the default one
     * should be send.
     *
     * @param  int     $statusCode
     * @param  string  $reasonPhrase  optional
     */
    public function setStatusCode($statusCode, $reasonPhrase = null)
    {
        $this->statusCode    = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * returns status code to be send
     *
     * If return value is <null> the default one will be send.
     *
     * @return  int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * add a header to the response
     *
     * @param  string  $name   the name of the header
     * @param  string  $value  the value of the header
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * returns the list of headers
     *
     * @return  array<string,string>
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * add a cookie to the response
     *
     * @param  stubCookie  $cookie  the cookie to set
     */
    public function setCookie(stubCookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }

    /**
     * returns the list of cookies
     *
     * @return  array<string,stubCookie>
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * write data into the response
     *
     * @param  string  $data
     */
    public function write($data)
    {
        $this->data .= $data;
    }

    /**
     * returns the data written so far
     *
     * @return  string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * replaces the data written so far with the new data
     *
     * @param  string  $data
     */
    public function replaceData($data)
    {
        $this->data = $data;
    }

    /**
     * removes data completely
     */
    public function clearData()
    {
        $this->data = null;
    }

    /**
     * send the response out
     */
    public function send()
    {
        if (null !== $this->statusCode) {
            if ('cgi' === $this->sapi) {
                $this->header('Status: ' . $this->statusCode . ' ' . $this->reasonPhrase);
            } else {
                $this->header('HTTP/' . $this->version . ' ' . $this->statusCode . ' ' . $this->reasonPhrase);
            }
        }

        // send the headers
        foreach ($this->headers as $name => $value) {
            $this->header($name . ': ' . $value);
        }

        // send the cookies
        foreach ($this->cookies as $cookie) {
            $cookie->send();
        }

        // and finally send the data
        if (null != $this->data) {
            $this->sendData($this->data);
        }
    }

    /**
     * helper method to send the header
     *
     * @param  string  $header
     */
    // @codeCoverageIgnoreStart
    protected function header($header)
    {
        header($header);
    }
    // @codeCoverageIgnoreEnd

    /**
     * helper method to send the data
     *
     * @param  string  $data
     */
    // @codeCoverageIgnoreStart
    protected function sendData($data)
    {
        echo $data;
        flush();
    }
    // @codeCoverageIgnoreEnd
}
?>