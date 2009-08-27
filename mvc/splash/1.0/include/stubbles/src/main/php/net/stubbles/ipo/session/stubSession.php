<?php
/**
 * interface for sessions
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_session
 */
/**
 * interface for sessions
 *
 * @package     stubbles
 * @subpackage  ipo_session
 */
interface stubSession extends stubObject
{
    /**
     * key to be associated with the start time of the session
     */
    const START_TIME           = '__stubbles_SessionStartTime';
    /**
     * key to be associated with the token for the next request
     */
    const NEXT_TOKEN           = '__stubbles_SessionNextToken';
    /**
     * key to be associated with the fingerprint of the user
     */
    const FINGERPRINT          = '__stubbles_SessionFingerprint';
    /**
     * registry key for session class to be used
     */
    const CLASS_REGISTRY_KEY   = 'net.stubbles.ipo.session.class';
    /**
     * registry key for session fingerprint salt to be used
     */
    const SALT_REGISTRY_KEY    = 'net.stubbles.ipo.session.fingerprintSalt';
    /**
     * registry key for session name to be used
     */
    const NAME_REGISTRY_KEY    = 'net.stubbles.ipo.session.name';
    /**
     * default session name
     */
    const DEFAULT_SESSION_NAME = 'PHPSESSID';

    /**
     * checks whether session has been started
     * 
     * Typically, a session is new on the first request of a user,
     * afterwards it should never be new.
     *
     * @return  bool  true if session has been started, else false
     */
    public function isNew();

    /**
     * returns unix timestamp when session was started
     *
     * @return  int
     */
    public function getStartTime();
    
    /**
     * returns session id
     *
     * @return  string  the session id
     */
    public function getId();
    
    /**
     * returns the name of the session
     *
     * @return  string
     */
    public function getName();

    /**
     * regenerates the session id but leaves session data
     */
    public function regenerateId();

    /**
     * returns token of current request
     *
     * @return  string  the token
     */
    public function getCurrentToken();
    
    /**
     * returns token for next request
     *
     * @return  string  the token
     */
    public function getNextToken();
    
    /**
     * checks if this session is valid
     *
     * @return  bool
     */
    public function isValid();
    
    /**
     * invalidates current session and creates a new one
     */
    public function invalidate();
    
    /**
     * resets the session and deletes all session data
     *
     * @return  int
     */
    public function reset();
    
    /**
     * stores a value associated with the key
     *
     * @param  string  $key    key to store value under
     * @param  mixed   $value  data to store
     */
    public function putValue($key, $value);
    
    /**
     * returns a value associated with the key or the default value
     *
     * @param   string  $key      key where value is stored under
     * @param   mixed   $default  optional  return this if no data is associated with $key
     * @return  mixed
     */
    public function getValue($key, $default = null);
    
    /**
     * checks whether a value associated with key exists
     *
     * @param   string  $key  key where value is stored under
     * @return  bool
     */
    public function hasValue($key);
    
    /**
     * removes a value from the session
     *
     * @param   string  $name  key where value is stored under
     * @return  bool    true if value existed and was removed, else false
     */
    public function removeValue($name);
    
    /**
     * return an array of all keys registered in this session
     *
     * @return  array<string>
     */
    public function getValueKeys();
}
?>