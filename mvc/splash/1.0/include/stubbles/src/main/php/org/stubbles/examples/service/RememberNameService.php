<?php
/**
 * Simple service to demonstrate stateful services
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  service
 */
/**
 * Simple service to demonstrate stateful services.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  service
 */
class RememberNameService
{
    /**
     * session key
     */
    const SESSION_KEY_NAME = '__name__';
    /**
     * The session
     *
     * @var  stubSession
     */
    private $session;

    /**
     * Inject the session
     *
     * @param  stubSession  $session
     * @Inject
     */
    public function setSession(stubSession $session)
    {
        $this->session = $session;
    }

    /**
     * Get the name from session
     *
     * @WebMethod
     * @return  string
     */
    public function getName()
    {
        return $this->session->getValue(self::SESSION_KEY_NAME, 'No name set.');
    }

    /**
     * Store the name in the session
     *
     * @WebMethod
     * @param  string
     */
    public function setName($name)
    {
        return $this->session->putValue(self::SESSION_KEY_NAME, $name);
    }
}
?>