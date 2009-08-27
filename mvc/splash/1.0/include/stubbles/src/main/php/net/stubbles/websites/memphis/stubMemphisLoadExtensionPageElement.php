<?php
/**
 * Page element for including a template file as content.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIOException',
                      'net::stubbles::ioc::stubBinderRegistry',
                      'net::stubbles::websites::memphis::stubMemphisExtension',
                      'net::stubbles::websites::memphis::stubMemphisPageElement'
);
/**
 * Page element for including a template file as content.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
class stubMemphisLoadExtensionPageElement extends stubMemphisPageElement
{
    /**
     * extension class name
     *
     * @var  string
     */
    protected $fqClassName = '';
    /**
     * extension instance
     *
     * @var  stubMemphisExtension
     */
    protected $extension;

    /**
     * set the extension class of the element
     *
     * @param  string  $fqClassName
     */
    public function setExtension($fqClassName)
    {
        $this->fqClassName = $fqClassName;
    }

    /**
     * returns the extension class of the element
     *
     * @return  string
     */
    public function getExtension()
    {
        return $this->fqClassName;
    }

    /**
     * initializes the page element
     *
     * @param   stubRequest          $request   the request data
     * @param   stubSession          $session   current session
     * @param   stubResponse         $response  contains response data
     * @param   array<string,mixed>  $context   optional  additional context data
     * @throws  stubRuntimeException
     */
    public function init(stubRequest $request, stubSession $session, stubResponse $response, array $context = array())
    {
        parent::init($request, $session, $response, $context);
        if (null === $this->extension) {
            $binder = stubBinderRegistry::get();
            $binder->bindConstant()->named('context')->to($this->context);
            $binder->bind('stubRequest')->named('prefixed')->toInstance($this->request);
            $extension = $binder->getInjector()->getInstance($this->fqClassName);
            if (($extension instanceof stubMemphisExtension) === false) {
                throw new stubRuntimeException('Configured extension class ' . $this->fqClassName . ' does not implement interface net::stubbles::websites::memphis::stubMemphisExtension.');
            }
            
            $this->extension = $extension;
        } else {
            $this->extension->setContext($context);
        }
    }

    /**
     * checks whether page element is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return $this->extension->isCachable();
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return $this->extension->getCacheVars();
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return $this->extension->getUsedFiles();
    }

    /**
     * processes the page element
     *
     * @return  string
     */
    public function process()
    {
        return $this->extension->process();
    }
}
?>