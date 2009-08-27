<?php
/**
 * Interface for URLs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_net
 */
/**
 * Interface for URLs.
 *
 * @package     stubbles
 * @subpackage  peer_net
 */
interface stubURLContainer extends stubObject
{
    /**
     * Checks whether URL is a correct URL.
     *
     * @return  bool
     */
    public function isValid();

    /**
     * checks whether host of url is listed in dns
     *
     * @return  bool
     */
    public function checkDNS();

    /**
     * returns the url
     *
     * @param   bool    $port  optional  true if port should be within returned url string
     * @return  string
     */
    public function get($port = false);

    /**
     * returns the scheme of the url
     *
     * @return  string
     */
    public function getScheme();

    /**
     * returns the user
     *
     * @param   string  $defaultUser  optional  user to return if no user is set
     * @return  string
     */
    public function getUser($defaultUser = null);
    /**
     * returns the password
     *
     * @param   string  $defaultPassword  optional  password to return if no password is set
     * @return  string
     */
    public function getPassword($defaultPassword = null);

    /**
     * returns hostname of the url
     *
     * @param   string  $defaultHost  optional  default host to return if no host is defined
     * @return  string
     */
    public function getHost($defaultHost = null);

    /**
     * sets the port
     *
     * @param  int  $port
     */
    public function setPort($port);

    /**
     * returns port of the url
     *
     * @param   int     $defaultPort  optional  port to be used if no port is defined
     * @return  string
     */
    public function getPort($defaultPort = null);

    /**
     * returns path of the url
     *
     * @return  string
     */
    public function getPath();

    /**
     * checks whether url has a query
     *
     * @return  bool
     */
    public function hasQuery();

    /** add a parameter to the url
     *
     * @param   string            $key    name of parameter
     * @param   mixed             $value  value of parameter
     * @return  stubURLContainer
     * @throws  stubIllegalArgumentException
     */
    public function addParam($key, $value);

    /**
     * returns the value of a param
     *
     * @param   string  $name          name of the param
     * @param   mixed   $defaultValue  optional  default value to return if param is not set
     * @return  mixed
     */
    public function getParam($name, $defaultValue = null);
}
?>
