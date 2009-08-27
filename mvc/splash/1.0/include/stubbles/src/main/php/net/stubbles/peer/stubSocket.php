<?php
/**
 * Class for operations on sockets.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalStateException',
                      'net::stubbles::peer::stubConnectionException'
);
/**
 * Class for operations on sockets.
 *
 * @package     stubbles
 * @subpackage  peer
 */
class stubSocket extends stubBaseObject
{
    /**
     * host to open socket to
     *
     * @var  string
     */
    protected $host    = null;
    /**
     * port to use for opening the socket
     *
     * @var  int
     */
    protected $port    = 80;
    /**
     * timeout
     *
     * @var  int
     */
    protected $timeout = 30;
    /**
     * internal resource pointer
     *
     * @var  resource
     */
    protected $fp      = null;

    /**
     * constructor
     *
     * @param  string  $host  host to open socket to
     * @param  int     $port  port to use for opening the socket
     */
    public function __construct($host, $port = 80)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * opens a http connection to host
     *
     * @return  bool    TRUE if connect was successful
     * @throws  stubConnectionException
     */
    public function connect()
    {
        if ($this->isConnected() == true) {
            return true;
        }

        if (null == $this->host) {
            return false;
        }

        $errno    = 0;
        $errstr   = '';
        $this->fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (false === $this->fp) {
            $this->fp = null;
            throw new stubConnectionException('Connecting to ' . $this->host . ':' . $this->port . ' within ' . $this->timeout . ' seconds failed: ' . $errstr . ' (' . $errno . ').');
        }

        socket_set_timeout($this->fp, $this->timeout);
        return true;
    }

    /**
     * closes a connection
     */
    public function disconnect()
    {
        if ($this->isConnected() == true) {
            fclose($this->fp);
            $this->fp = null;
        }
    }

    /**
     * set timeout for connections
     *
     * @param  int  $timeout  timeout for connection in seconds
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        if ($this->isConnected() == true) {
            socket_set_timeout($this->fp, $this->timeout);
        }
    }

    /**
     * read from socket
     *
     * @param   int     $length  optional  length of data to read
     * @return  string  data read from socket
     * @throws  stubConnectionException
     * @throws  stubIllegalStateException
     */
    public function read($length = 4096)
    {
        if ($this->isConnected() == false) {
            throw new stubIllegalStateException('Can not read on unconnected socket.');
        }

        $data = fgets($this->fp, $length);
        if (false === $data) {
            throw new stubConnectionException('Reading of ' . $length . ' bytes failed.');
        }

        return $data;
    }

    /**
     * read a whole line from socket
     *
     * @param   int     $length  optional  length of data to read
     * @return  string  data read from socket
     */
    public function readLine($length = 4096)
    {
        return rtrim($this->read($length));
    }

    /**
     * read binary data from socket
     *
     * @param   int     $length  optional  length of data to read
     * @return  string  data read from socket
     * @throws  stubConnectionException
     * @throws  stubIllegalStateException
     */
    public function readBinary($length = 1024)
    {
        if ($this->isConnected() == false) {
            throw new stubIllegalStateException('Can not read on unconnected socket.');
        }

        $data = fread($this->fp, $length);
        if (false === $data) {
            throw new stubConnectionException('Reading of ' . $length . ' bytes failed.');
        }

        return $data;
    }

    /**
     * write data to socket
     *
     * @param   string  $data  data to write
     * @return  int     amount of bytes written to socket
     * @throws  stubConnectionException
     * @throws  stubIllegalStateException
     */
    public function write($data)
    {
        if ($this->isConnected() == false) {
            throw new stubIllegalStateException('Can not write on unconnected socket.');
        }

        $length = fputs($this->fp, $data, strlen($data));
        if (false === $length) {
            throw new stubConnectionException('"Writing of ' . strlen($data) . ' bytes failed.');
        }

        return $length;
    }

    /**
     * get host of current connection
     *
     * @return  string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * get port of current connection
     *
     * @return  int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * get timeout for connections
     *
     * @return  int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * checks if we already have a connection
     *
     * @return  bool
     */
    public function isConnected()
    {
        return is_resource($this->fp);
    }

    /**
     * check if we reached end of data
     *
     * @return  bool
     */
    public function eof()
    {
        if ($this->isConnected() == true) {
            return feof($this->fp);
        }

        return true;
    }
}
?>