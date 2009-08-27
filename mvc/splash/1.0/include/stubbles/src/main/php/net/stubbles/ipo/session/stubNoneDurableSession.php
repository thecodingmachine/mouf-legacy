<?php
/**
 * Session class that is not durable for more than one request.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_session
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::ipo::session::stubAbstractSession'
);
/**
 * Session class that is not durable for more than one request.
 *
 * @package     stubbles
 * @subpackage  ipo_session
 */
class stubNoneDurableSession extends stubAbstractSession
{
    /**
     * the session id
     *
     * @var  int
     */
    protected $id;
    /**
     * the data
     *
     * @var  array
     */
    protected $data        = array();
    /**
     * the response instance
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * regular expression to validate the session id
     */
    const REGEX_SESSION_ID = '/^([a-zA-Z0-9]{32})$/D';

    /**
     * template method for child classes to do the real construction
     * 
     * @param   stubRequest   $request      request instance
     * @param   stubResponse  $response     response instance
     * @param   string        $sessionName  name of the session
     * @return  bool
     */
    protected function doConstruct(stubRequest $request, stubResponse $response, $sessionName)
    {
        $this->response = $response;
        if ($request->hasValue($sessionName) === true) {
            $this->id = $request->getValidatedValue(new stubRegexValidator(self::REGEX_SESSION_ID), $sessionName);
            $this->data = array(stubSession::START_TIME  => time(),
                                stubSession::FINGERPRINT => '',
                                stubSession::NEXT_TOKEN  => ''
                          );
        } elseif ($request->hasValue($sessionName, stubRequest::SOURCE_COOKIE) === true) {
            $this->id = $request->getValidatedValue(new stubRegexValidator(self::REGEX_SESSION_ID), $sessionName, stubRequest::SOURCE_COOKIE);
            $this->data = array(stubSession::START_TIME  => time(),
                                stubSession::FINGERPRINT => '',
                                stubSession::NEXT_TOKEN  => ''
                          );
        } else {
            $this->id = md5(uniqid(rand(), true));
        }
        
        $this->response->setCookie(stubCookie::create($sessionName, $this->id)->forPath('/'));
        return true;
    }

    /**
     * returns fingerprint for user: has to use same user agent all over the session
     * 
     * @return  string
     */
    protected function getFingerprint()
    {
        return '';
    }

    /**
     * returns session id
     *
     * @return  string  the session id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * regenerates the session id but leaves session data
     */
    public function regenerateId()
    {
        $this->id = md5(uniqid(rand(), true));
        $this->response->setCookie(stubCookie::create($this->sessionName, $this->id)->forPath('/'));
    }

    /**
     * invalidates current session and creates a new one
     */
    public function invalidate()
    {
        $this->data = array();
    }

    /**
     * stores a value associated with the key
     *
     * @param  string  $key    key to store value under
     * @param  mixed   $value  data to store
     */
    protected function doPutValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * returns a value associated with the key or the default value
     *
     * @param   string  $key  key where value is stored under
     * @return  mixed
     */
    protected function doGetValue($key)
    {
        return $this->data[$key];
    }

    /**
     * checks whether a value associated with key exists
     *
     * @param   string  $key  key where value is stored under
     * @return  bool
     */
    public function hasValue($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * removes a value from the session
     *
     * @param   string  $key  key where value is stored under
     * @return  bool    true if value existed and was removed, else false
     */
    protected function doRemoveValue($key)
    {
        unset($this->data[$key]);
        return true;
    }

    /**
     * return an array of all keys registered in this session
     *
     * @return  array<string>
     */
    protected function doGetValueKeys()
    {
        return array_keys($this->data);
    }
}
?>