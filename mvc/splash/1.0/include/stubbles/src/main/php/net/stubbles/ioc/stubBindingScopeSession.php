<?php
/**
 * Scope for session-bounded singletons.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder',
                      'net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::ioc::stubBindingScope',
                      'net::stubbles::ioc::stubValueInjectionProvider',
                      'net::stubbles::lang::exceptions::stubRuntimeException'
);
/**
 * Scope for session-bounded singletons.
 *
 * @package     stubbles
 * @subpackage  ioc
 */
class stubBindingScopeSession extends stubBaseObject implements stubBindingScope
{
    /**
     * session prefix key
     */
    const SESSION_KEY    = 'net.stubbles.ioc.sessionScope#';
    /**
     * session instance to store instances in
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * instances in this scope
     *
     * @var  array<string,stubValueInjectionProvider>
     */
    protected $instances = array();

    /**
     * sets the session
     *
     * @param  stubSession  $session
     */
    public function setSession(stubSession $session)
    {
        $this->session = $session;
    }

    /**
     * returns the provider that has or creates the required instance
     *
     * @param   stubBaseReflectionClass  $type      type of the object
     * @param   stubBaseReflectionClass  $impl      concrete implementation
     * @param   stubInjectionProvider    $provider
     * @return  stubInjectionProvider
     */
    public function getProvider(stubBaseReflectionClass $type, stubBaseReflectionClass $impl, stubInjectionProvider $provider)
    {
        if (null === $this->session) {
            $this->getSession();
        }
        
        $key = self::SESSION_KEY . $impl->getName();
        if (isset($this->instances[$key]) === true) {
            return $this->instances[$key];
        }
        
        if ($this->session->hasValue($key) === true) {
            $this->instances[$key] = new stubValueInjectionProvider($this->session->getValue($key));
            return $this->instances[$key];
        }
        
        $instance              = $provider->get($type);
        $this->instances[$key] = new stubValueInjectionProvider($instance);
        $this->session->putValue($key, $instance);
        return $this->instances[$key];
    }

    /**
     * clears the instance list
     */
    public function clearInstances()
    {
        $this->instances = array();
    }

    /**
     * retrieves the session from the binder
     */
    protected function getSession()
    {
        $this->session = stubBinderRegistry::get()->getInjector()->getInstance('stubSession');
    }
}
?>