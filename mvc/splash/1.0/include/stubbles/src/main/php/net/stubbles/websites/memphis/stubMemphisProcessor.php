<?php
/**
 * Processor for memphis pages.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestPrefixDecorator',
                      'net::stubbles::ipo::request::filter::stubRegexFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubStringFilter',
                      'net::stubbles::ipo::request::validator::stubPreSelectValidator',
                      'net::stubbles::lang::stubMode',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::cache::stubCachableProcessor',
                      'net::stubbles::websites::processors::stubAbstractProcessor',
                      'net::stubbles::websites::processors::stubPageBasedProcessor',
                      'net::stubbles::websites::memphis::stubMemphisConfig',
                      'net::stubbles::websites::memphis::stubMemphisPatTemplate'
);
/**
 * Processor for memphis pages.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
class stubMemphisProcessor extends stubAbstractProcessor implements stubCachableProcessor, stubPageBasedProcessor
{
    /**
     * instance of patTemplate
     *
     * @var  stubMemphisTemplate
     */
    protected $template;
    /**
     * configuration
     *
     * @var  stubMemphisConfig
     */
    protected $config;
    /**
     * context
     *
     * @var  array<string,mixed>
     */
    protected $context = array('part'       => null,
                               'page'       => null,
                               'pageName'   => '',
                               'frameId'    => ''
                         );
    /**
     * decorated request
     *
     * @var  stubRequestPrefixDecorator
     */
    protected $prefixRequest;

    /**
     * optional template method to do some constructor work in derived classes
     */
    protected function doConstruct()
    {
        $this->prefixRequest = new stubRequestPrefixDecorator($this->request, '');
        $this->config        = $this->createConfig();
    }

    /**
     * helper method to create the config object
     *
     * @return  stubMemphisConfig
     */
    // @codeCoverageIgnoreStart
    protected function createConfig()
    {
        return new stubMemphisConfig();
    }
    // @codeCoverageIgnoreEnd

    /**
     * selects the page to display with help of the page factory
     *
     * @param  stubPageFactory  $pageFactory
     */
    public function selectPage(stubPageFactory $pageFactory)
    {
        $this->context['pageName'] = $pageFactory->getPageName($this->request);
        $this->context['page']     = $pageFactory->getPage($this->context['pageName']);
        $this->session->putValue('net.stubbles.websites.lastPage', $this->context['pageName']);
        $this->context['frameId']  = $this->getFrameId();
    }

    /**
     * helper method to get the name of the frame to use
     *
     * @return  string
     */
    protected function getFrameId()
    {
        if ($this->context['page']->hasProperty('frame_fixed') === true && $this->context['page']->getProperty('frame_fixed') === true) {
            return $this->context['page']->getProperty('frame');
        }

        if ($this->request->hasValue('frame') === true) {
            $frame = $this->request->getValidatedValue(new stubPreSelectValidator(array_keys($this->config->getFrames())), 'frame');
        } elseif ($this->session->hasValue('net.stubbles.websites.memphis.frame') === true) {
            $frame = $this->session->getValue('net.stubbles.websites.memphis.frame');
        } else {
            $frame = $this->context['page']->getProperty('frame');
        }

        if (null == $frame) {
            return 'default';
        }

        return $frame;
    }

    /**
     * adds the cache variables for the current request and returns whether
     * response is cachable or not
     *
     * @param   stubWebsiteCache  $cache
     * @return  bool
     */
    public function addCacheVars(stubWebsiteCache $cache)
    {
        $cache->addCacheVar('page', $this->context['pageName']);
        $cache->addCacheVar('frame', $this->context['frameId']);
        $cache->addCacheVar('variant', $this->session->getValue('net.stubbles.websites.variantmanager.variant.name', ''));
        foreach ($this->config->getParts() as $part) {
            $this->context['part'] = $part;
            foreach ($this->config->getDefaultElements($part) as $defaultElement) {
                $this->prefixRequest->setPrefix($defaultElement->getName());
                $defaultElement->init($this->prefixRequest, $this->session, $this->response, $this->context);
                if ($defaultElement->isAvailable() === true) {
                    if ($defaultElement->isCachable() === false) {
                        return false;
                    }

                    $cache->addCacheVars($defaultElement->getCacheVars());
                    $cache->addUsedFiles($defaultElement->getUsedFiles());
                }
            }

            foreach ($this->context['page']->getElements() as $name => $element) {
                $this->prefixRequest->setPrefix($name);
                $element->init($this->prefixRequest, $this->session, $this->response, $this->context);
                if ($element->isAvailable() === true) {
                    if ($element->isCachable() === false) {
                        return false;
                    }

                    $cache->addCacheVars($element->getCacheVars());
                    $cache->addUsedFiles($element->getUsedFiles());
                }
            }
        }

        return true;
    }

    /**
     * returns the name of the current page
     *
     * @return  string
     */
    public function getPageName()
    {
        return $this->context['pageName'];
    }

    /**
     * processes the request
     */
    public function process()
    {
        $this->template            = $this->createTemplate();
        $this->context['template'] = $this->template;
        $this->template->readTemplatesFromInput($this->config->getFrame($this->context['frameId']));
        $this->setTemplateVars();
        foreach ($this->config->getParts() as $part) {
            $content               = '';
            $this->context['part'] = $part;
            foreach ($this->config->getDefaultElements($part) as $defaultElement) {
                $this->prefixRequest->setPrefix($defaultElement->getName());
                $defaultElement->init($this->prefixRequest, $this->session, $this->response, $this->context);
                if ($defaultElement->isAvailable() === true) {
                    $content .= $defaultElement->process();
                    if ($this->prefixRequest->isCancelled() === true) {
                        return;
                    }
                }
            }

            foreach ($this->context['page']->getElements() as $name => $element) {
                $this->prefixRequest->setPrefix($name);
                $element->init($this->prefixRequest, $this->session, $this->response, $this->context);
                if ($element->isAvailable() === true) {
                    $content .= $element->process();
                    if ($this->prefixRequest->isCancelled() === true) {
                        return;
                    }
                }
            }

            $this->template->addGlobalVar($part, $content);
        }

        $this->response->write($this->template->getParsedTemplate('frame'));
        if ($this->request->acceptsCookies() === false) {
            output_add_rewrite_var($this->session->getName(), $this->session->getId());
        }
    }

    /**
     * helper method to create the template
     *
     * @return  stubMemphisTemplate
     */
    // @codeCoverageIgnoreStart
    protected function createTemplate()
    {
        $template = new stubMemphisPatTemplate(stubRegistry::getConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, stubConfig::getPagePath() . '/../templates'));
        if (stubMode::$CURRENT->isCacheEnabled() === true) {
            $template->enableCache();
        }

        return $template;
    }
    // @codeCoverageIgnoreEnd

    /**
     * helper method to set the template vars
     */
    protected function setTemplateVars()
    {
        $this->template->addGlobalVar('UCUO_FRAME', $this->context['frameId']);
        $title = $this->context['page']->getProperty('title');
        $this->template->addGlobalVar('PAGE_TITLE', htmlspecialchars($title, ENT_COMPAT, mb_detect_encoding($title, 'UTF-8, ISO-8859-1')));
        $this->template->addGlobalVar('PAGE_NAME', $this->context['pageName']);
        $this->template->addGlobalVar('VARIANT', $this->session->getValue('net.stubbles.websites.variantmanager.variant.name', ''));
        $this->template->addGlobalVar('VARIANT_ALIAS', $this->session->getValue('net.stubbles.websites.variantmanager.variant.alias', ''));

        $regexDecorator = new stubRegexFilterDecorator(new stubStringFilter(), '/\/.*(?=\?)|\/.*/');
        $requestUri = $this->request->getFilteredValue($regexDecorator, 'REQUEST_URI', stubRequest::SOURCE_HEADER);
        $serviceUrl = $requestUri . '?processor=jsonrpc';
        $this->template->addGlobalVar('SERVICE_URL', $serviceUrl);

        $this->template->addGlobalVar('SID', '$SID');
        $this->template->addGlobalVar('SESSION_NAME', '$SESSION_NAME');
        $this->template->addGlobalVar('SESSION_ID', '$SESSION_ID');
        // add meta information to the page
        foreach ($this->config->getMetaTags() as $key => $value) {
            $this->template->addVar('frame', 'META_' . $key, htmlspecialchars($value, ENT_COMPAT, mb_detect_encoding($value, 'UTF-8, ISO-8859-1')));
        }

        $sslMode = 'no';
        if ($this->isSSL() === true) {
            $sslMode = 'yes';
        }

        $this->template->addGlobalVar('SSL_MODE', $sslMode);
    }
}
?>