<?php
/**
 * Pre interceptor for initializing the variantmanager.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPreInterceptor',
                      'net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::request::validator::stubPreSelectValidator',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::lang::stubRegistry'
);
/**
 * Pre interceptor for initializing the variantmanager.
 * 
 * This pre interceptor detects the variant for the user and saves it to the
 * session. It uses the following keys for stubRegistry:
 * <code>
 * net.stubbles.websites.variantmanager.variantfactory.class
 *     name of the variant factory class to use
 *     default: net::stubbles::websites::variantmanager::stubXJConfVariantFactory
 * net.stubbles.websites.variantmanager.cookie.name
 *     name of the cookie where the variant name should be stored
 *     default: variant
 * net.stubbles.websites.variantmanager.cookie.expiring
 *     duration of how long the cookie should be stored
 *     default: 90 days
 * net.stubbles.websites.variantmanager.cookie.url
 *     url of the cookie
 *     default: null (same domain as application is running on)
 * net.stubbles.websites.variantmanager.cookie.path
 *     path of the cookie
 *     default: /
 * </code>
 * Default values are choosen if no explicit values are set.
 * 
 * The choosen variant can be accessed within the session by the key
 * net::stubbles::websites.variantmanager.variant.
 * 
 * @package     stubbles
 * @subpackage  websites_variantmanager
 */
class stubVariantsPreInterceptor extends stubBaseObject implements stubPreInterceptor
{
    /**
     * does the preprocessing stuff
     *
     * @param   stubRequest   $request   access to request data
     * @param   stubSession   $session   access to session data
     * @param   stubResponse  $response  access to response data
     * @throws  stubVariantConfigurationException
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response)
    {
        if ($session->hasValue('net.stubbles.websites.variantmanager.variant.name') == true) {
            return;
        }
        
        $fqClassname = stubRegistry::getConfig('net.stubbles.websites.variantmanager.variantfactory.class', 'net::stubbles::websites::variantmanager::stubVariantXJConfFactory');
        $nqClassname = stubClassLoader::getNonQualifiedClassName($fqClassname);
        if (class_exists($nqClassname, false) == false) {
            stubClassLoader::load($fqClassname);
        }
        
        $variantFactory = $this->createVariantFactory($nqClassname);
        if (($variantFactory instanceof stubVariantFactory) == false) {
            throw new stubVariantConfigurationException('Configured variant factory is not an instance of net::stubbles::websites::variantmanager::stubVariantFactory.');
        }
        
        $variant    = null;
        $cookieName = stubRegistry::getConfig('net.stubbles.websites.variantmanager.cookie.name', 'variant');
        if ($variantFactory->getVariantsMap()->shouldUsePersistence() == true) {
            $variant = $this->getVariantFromCookie($request, $session, $variantFactory, $cookieName);
        }
        
        if (null === $variant) {
            $variant = $variantFactory->getVariantsMap()->getVariant($session, $request);
        }
        
        $session->putValue('net.stubbles.websites.variantmanager.variant.name', $variant->getFullQualifiedName());
        $session->putValue('net.stubbles.websites.variantmanager.variant.alias', $variant->getAlias());
        $expiring   = stubRegistry::getConfig('net.stubbles.websites.variantmanager.cookie.expiring', (86400 * 90)); // 90 days default
        $cookieURL  = stubRegistry::getConfig('net.stubbles.websites.variantmanager.cookie.url', null);
        $cookiePath = stubRegistry::getConfig('net.stubbles.websites.variantmanager.cookie.path', '/');
        $response->setCookie($this->createCookie($cookieName, $variant->getName(), $expiring, $cookieURL, $cookiePath));
    }
    
    /**
     * creates the variant factory
     *
     * @param   string              $classname  classname of the variant factory to create
     * @return  stubVariantFactory
     */
    // @codeCoverageIgnoreStart
    protected function createVariantFactory($classname)
    {
        return new $classname();
    }
    // @codeCoverageIgnoreEnd
    
    /**
     * tries to get the variant from the cookie
     *
     * @param   stubRequest         $request         access to request data
     * @param   stubSession         $session         access to session data
     * @param   stubVariantFactory  $variantFactory  the variant factory to use
     * @param   string              $cookieName      name of the cookie
     * @return  stubVariant
     */
    protected function getVariantFromCookie(stubRequest $request, stubSession $session, stubVariantFactory $variantFactory, $cookieName)
    {
        if ($request->hasValue($cookieName, stubRequest::SOURCE_COOKIE) == false) {
            return null;
        }
        
        $variantName = $request->getValidatedValue(new stubPreSelectValidator($variantFactory->getVariantNames()), $cookieName, stubRequest::SOURCE_COOKIE);
        if (null == $variantName) {
            return null;
        }
        
        $cookieVariant    = $variantFactory->getVariantByName($variantName);
        $enforcingVariant = $variantFactory->getVariantsMap()->getEnforcingVariant($session, $request);
        if (null === $enforcingVariant) {
            return $cookieVariant;
        }
        
        if (substr($cookieVariant->getName(), 0, strlen($enforcingVariant->getName())) == $enforcingVariant->getName()) {
            return $enforcingVariant;
        }
        
        return $cookieVariant;
    }
    
    /**
     * creates the cookie
     *
     * @param   string      $cookieName
     * @param   string      $variantName
     * @param   int         $expiring
     * @param   string      $cookieURL
     * @param   string      $cookiePath
     * @return  stubCookie
     */
    protected function createCookie($cookieName, $variantName, $expiring, $cookieURL, $cookiePath)
    {
        return stubCookie::create($cookieName, $variantName)->expiringAt(time() + $expiring)->forPath($cookiePath)->forDomain($cookieURL);
    }
}
?>