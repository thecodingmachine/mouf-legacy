<?php
/**
 * Class for URLs and methods on URLs.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_net
 * @version     $Id: stubURL.php 1935 2008-11-28 14:24:21Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::peer::stubMalformedURLException',
                      'net::stubbles::peer::stubURLContainer'
);
/**
 * Class for URLs and methods on URLs.
 *
 * @package     stubbles
 * @subpackage  peer_net
 */
class stubURL extends stubBaseObject implements stubURLContainer
{
    /**
     * internal representation after parse_url()
     *
     * @var  array
     */
    protected $url    = array();
    /**
     * parameters for url
     *
     * @var  array
     */
    protected $params = array();

    /**
     * constructor
     *
     * @param  string  $url
     */
    protected function __construct($url)
    {
        $this->url = parse_url($url);
        if (isset($this->url['host']) === true) {
            $this->url['host'] = strtolower($this->url['host']);
        }
        
        // bugfix for a PHP issue: ftp://user:@auxiliary.kl-s.com/
        // will lead to an unset $this->url['pass'] which is wrong
        // due to RFC1738 3.1, it has to be an empty string
        if (isset($this->url['user']) === true && isset($this->url['pass']) === false && $this->get(true) !== $url) {
            $this->url['pass'] = '';
        }
        
        if ($this->hasQuery() === true) {
            parse_str($this->url['query'], $this->params);
        }
    }

    /**
     * parses an url out of a string
     *
     * @param   string   $urlString
     * @return  stubURL
     * @throws  stubMalformedURLException
     */
    public static function fromString($urlString)
    {
        if (strlen($urlString) === 0) {
            return null;
        }
        
        $url = new self($urlString);
        if ($url->isValid() === false) {
            throw new stubMalformedURLException('The URL ' . $urlString . ' is not a valid URL.');
        }
        
        return $url;
    }

    /**
     * Checks whether URL is a correct URL.
     *
     * @return  bool
     */
    public function isValid()
    {
        if (strlen($this->get()) === 0) {
            return false;
        }
        
        if (isset($this->url['scheme']) === false) {
            return false;
        }
        
        if (isset($this->url['user']) === true) {
            if (ereg('([@:/])', $this->url['user']) == true) {
                return false;
            }
            
            if (isset($this->url['pass']) === true && ereg('([@:/])', $this->url['pass']) == true) {
                return false;
            }
        }
        
        // if host is set and seems to comply with host character rules or host is localhost syntax is ok
        if (isset($this->url['host']) === true
                && (eregi('([a-z0-9-]*)\.([a-z]{2,4})', $this->url['host']) == true
                        || eregi('([0-9-]{1,3})\.([0-9-]{1,3})\.([0-9-]{1,3})\.([0-9-]{1,3})', $this->url['host']) == true
                        || 'localhost' == $this->url['host'])) {
            return true;
        } elseif (isset($this->url['host']) === false || strlen($this->url['host']) === 0) {
            return true;
        }
        
        return false;
    }

    /**
     * checks whether host of url is listed in dns
     *
     * @return  bool
     */
    public function checkDNS()
    {
        // no valid url, no dns :)
        if ($this->isValid() === false) {
            return false;
        }
        
        // no host, no dns :)
        if (isset($this->url['host']) === false) {
            return false;
        }
        
        if ('localhost' === $this->url['host'] || '127.0.0.1' === $this->url['host']) {
            return true;
        }
        
        // windows does not support dns functions :(
        if (function_exists('checkdnsrr') === false) {
            return true;
        }
        
        if (checkdnsrr($this->url['host'], 'ANY') === true || checkdnsrr($this->url['host'], 'MX') === true) {
            return true;
        }
        
        return false;
    }

    /**
     * returns the url
     *
     * @param   bool    $port  optional  true if port should be within returned url string
     * @return  string
     */
    public function get($port = false)
    {
        $url  = '';
        $user = '';
        if (isset($this->url['user']) === true) {
            $user = $this->url['user'];
            if (isset($this->url['pass']) === true) {
                $user .= ':' . $this->url['pass'];
            }
            
            $user .= '@';
        }
        
        if (true == $port && isset($this->url['port']) === true) {
            $port =  ':' . $this->url['port'];
        } else {
            $port = '';
        }
        
        if (isset($this->url['scheme']) === true) {
            $url = $this->url['scheme'] . '://';
            if (isset($this->url['host']) === true) {
                $url .= $user . $this->url['host'] . $port;
            }
            
            if (isset($this->url['path']) === true) {
                $url .= $this->url['path'];
            }
        }
        
        if ($this->hasQuery() === true) {
            $url .= '?' . $this->buildQuery();
        }
        
        if (isset($this->url['fragment']) && strlen($this->url['fragment']) > 0) {
            $url .= '#' . $this->url['fragment'];
        }
        
        return $url;
    }

    /**
     * returns the scheme of the url
     *
     * @return  string
     */
    public function getScheme()
    {
        if (isset($this->url['scheme']) === true) {
            return $this->url['scheme'];
        }
        
        return null;
    }

    /**
     * returns the user
     *
     * @param   string  $defaultUser  optional  user to return if no user is set
     * @return  string
     */
    public function getUser($defaultUser = null)
    {
        if (isset($this->url['user']) === true) {
            return $this->url['user'];
        }
        
        return $defaultUser;
    }

    /**
     * returns the password
     *
     * @param   string  $defaultPassword  optional  password to return if no password is set
     * @return  string
     */
    public function getPassword($defaultPassword = null)
    {
        if (isset($this->url['pass']) === true) {
            return $this->url['pass'];
        }
        
        return $defaultPassword;
    }

    /**
     * returns hostname of the url
     *
     * @param   string  $defaultHost  optional  default host to return if no host is defined
     * @return  string
     */
    public function getHost($defaultHost = null)
    {
        if (isset($this->url['host']) === true) {
            return $this->url['host'];
        }
        
        return $defaultHost;
    }

    /**
     * sets the port
     *
     * @param  int  $port
     */
    public function setPort($port)
    {
        $this->url['port'] = $port;
    }

    /**
     * returns port of the url
     *
     * @param   int     $defaultPort  optional  port to be used if no port is defined
     * @return  string
     */
    public function getPort($defaultPort = null)
    {
        if (isset($this->url['port']) === true) {
            return $this->url['port'];
        }
        
        return $defaultPort;
    }

    /**
     * returns path of the url
     *
     * @return  string
     */
    public function getPath()
    {
        if (isset($this->url['path']) === false) {
            return null;
        }
        
        if ($this->hasQuery() === true) {
            return $this->url['path'] . '?' . $this->buildQuery();
        }
        
        return $this->url['path'];
    }

    /**
     * checks whether url has a query
     *
     * @return  bool
     */
    public function hasQuery()
    {
        return (count($this->params) > 0 ||  (isset($this->url['query']) === true && strlen($this->url['query']) > 0));
    }

    /**
     * add a parameter to the url
     *
     * @param   string   $key    name of parameter
     * @param   mixed    $value  value of parameter
     * @return  stubURL
     * @throws  stubIllegalArgumentException
     */
    public function addParam($key, $value)
    {
        if (is_string($key) === false) {
            throw new stubIllegalArgumentException('Argument 1 passed to ' . __METHOD__ . '() must be an instance of string.');
        }
        
        if (null !== $value && is_array($value) === false && is_scalar($value) === false) {
            throw new stubIllegalArgumentException('Argument 2 passed to ' . __METHOD__ . '() must be an instance of string, array or any other scalar value or null.');
        }
        
        if (null === $value and isset($this->params[$key]) === true) {
            unset($this->params[$key]);
        } elseif (null !== $value) {
            if (false === $value) {
                $value = 0;
            } elseif (true === $value) {
                $value = 1;
            }
            
            $this->params[$key] = $value;
        }
        
        return $this;
    }

    /**
     * returns the value of a param
     *
     * @param   string  $name          name of the param
     * @param   mixed   $defaultValue  optional  default value to return if param is not set
     * @return  mixed
     */
    public function getParam($name, $defaultValue = null)
    {
        if (isset($this->params[$name]) === false) {
            return $defaultValue;
        }
        
        return $this->params[$name];
    }

    /**
     * build the query from given parameters
     *
     * @return  string
     */
    protected function buildQuery()
    {
        if ($this->hasQuery() === false) {
            return null;
        }

        $query = '';
        foreach ($this->params as $key => $value) {
            if (is_array($value) === false) {
                if (strlen($query) > 0) {
                    $query .= '&';
                }
                
                if($value !== '') {
                    $query .= $key . '=' . urlencode($value);
                } else {
                    $query .= $key;
                }
            } else {
                foreach ($value as $assoc_key => $single) {
                    if (strlen($query) > 0) {
                        $query .= '&';
                    }
                    
                    if (is_string($assoc_key) === true) {
                        $query .= $key . '[' . $assoc_key . ']=' . urlencode($single);
                    } else {
                        $query .= $key . '[]=' . urlencode($single);
                    }
                }
            }
        }
        
        return $query;
    }
}
?>
