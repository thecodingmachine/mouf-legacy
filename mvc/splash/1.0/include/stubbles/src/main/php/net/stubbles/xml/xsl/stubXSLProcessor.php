<?php
/**
 * Class to transform xml via xsl.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl
 */
stubClassLoader::load('net::stubbles::lang::stubClonable',
                      'net::stubbles::lang::exceptions::stubIOException',
                      'net::stubbles::xml::xsl::stubXSLCallback',
                      'net::stubbles::xml::xsl::stubXSLProcessorException'
);
/**
 * Class to transform xml via xsl.
 *
 * @package     stubbles
 * @subpackage  xml_xsl
 */
class stubXSLProcessor extends stubBaseObject implements stubClonable
{
    /**
     * the document to transform
     *
     * @var  DOMDocument
     */
    protected $document;
    /**
     * the real processor used for the transformation
     *
     * @var  XSLTProcessor
     */
    protected $xsltProcessor;
    /**
     * list of parameters that were set
     *
     * @var  array<string,array<string,string>>
     */
    protected $parameters    = array();
    /**
     * the response to work on as post interceptor
     *
     * @var  array<string,stubObject>
     */
    protected $callbacks     = array();
    /**
     * list of used stylesheets
     *
     * @var  array<DOMDocument>
     */
    protected $stylesheets   = array();

    /**
     * constructor
     *
     * @throws  stubRuntimeException
     */
    public function __construct()
    {
        if (extension_loaded('xsl') === false) {
            throw new stubRuntimeException('Can not create ' . __CLASS__ . ', requires PHP-extension "xsl".');
        }
        
        $this->createXSLTProcessor();
    }

    /**
     * creates the XSLTProcessor instance
     */
    protected function createXSLTProcessor()
    {
        $this->xsltProcessor = new XSLTProcessor();
    }

    /**
     * static creation with fluent interface
     *
     * @param   DOMDocument       $stylesheet
     * @return  stubXSLProcessor
     */
    public static function applyStylesheet(DOMDocument $stylesheet)
    {
        $self = new self();
        $self->andApplyStylesheet($stylesheet);
        return $self;
    }

    /**
     * static creation with fluent interface
     *
     * @param   DOMDocument       $stylesheetFile
     * @return  stubXSLProcessor
     */
    public static function applyStylesheetFromFile($stylesheetFile)
    {
        $self = new self();
        $self->andApplyStylesheetFromFile($stylesheetFile);
        return $self;
    }

    /**
     * sets the document to transform
     *
     * @param   DOMDocument       $doc
     * @return  stubXSLProcessor
     */
    public function onDocument(DOMDocument $doc)
    {
        $this->document = $doc;
        return $this;
    }

    /**
     * sets the document to transform
     *
     * @param   string            $xmlFile   name of the xml file containing the document to transform
     * @param   bool              $xinclude  optional  whether to resolve xincludes or not, defaults to true
     * @return  stubXSLProcessor
     * @throws  stubIOException
     */
    public function onXMLFile($xmlFile, $xinclude = true)
    {
        $doc = new DOMDocument();
        if (false === $doc->load($xmlFile)) {
            throw new stubIOException('Can not read xml document file ' . $xmlFile);
        }
        
        if (true === $xinclude) {
            $doc->xinclude();
        }
        
        return $this->onDocument($doc);
    }

    /**
     * add a stylesheet to use
     *
     * @param   DOMDocument       $stylesheet
     * @return  stubXSLProcessor
     */
    public function andApplyStylesheet(DOMDocument $stylesheet)
    {
        $this->stylesheets[] = $stylesheet;
        $this->xsltProcessor->importStylesheet($stylesheet);
        return $this;
    }

    /**
     * add a stylesheet to use from a file
     *
     * @param   string            $stylesheetFile
     * @return  stubXSLProcessor
     * @throws  stubIOException
     */
    public function andApplyStylesheetFromFile($stylesheetFile)
    {
        $stylesheet = new DOMDocument();
        if (false === $stylesheet->load($stylesheetFile)) {
            throw new stubIOException('Can not read stylesheet file ' . $stylesheetFile);
        }
        
        return $this->andApplyStylesheet($stylesheet);
    }

    /**
     * returns the list of used stylesheets
     *
     * @return  array<DOMDocument>
     */
    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    /**
     * register an instance as callback
     *
     * @param   string            $name      name to register the callback under
     * @param   stubObject        $instance  the instance to register as callback
     * @return  stubXSLProcessor
     */
    public function usingCallback($name, stubObject $instance)
    {
        $this->callbacks[$name] = $instance;
        return $this;
    }

    /**
     * returns list of callbacks
     *
     * @return  array<string,stubObject>
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * sets a parameter for a namespace
     *
     * @param   string            $nameSpace   the namespace where the parameter is in
     * @param   string            $paramName   the name of the parameter to set
     * @param   string            $paramValue  the value to set the parameter to
     * @return  stubXSLProcessor
     * @throws  stubXSLProcessorException
     */
    public function withParameter($nameSpace, $paramName, $paramValue)
    {
        if (false === $this->xsltProcessor->setParameter($nameSpace, $paramName, $paramValue)) {
            throw new stubXSLProcessorException('Could not set parameter ' . $nameSpace . ':' . $paramName . ' with value ' . $paramValue);
        }
        
        if (isset($this->parameters[$nameSpace]) === false) {
            $this->parameters[$nameSpace] = array();
        }
        
        $this->parameters[$nameSpace][$paramName] = $paramValue;
        return $this;
    }

    /**
     * checks if a parameter for a namespace exists
     *
     * @param   string  $nameSpace   the namespace where the parameter is in
     * @param   string  $paramName   the name of the parameter to check
     * @return  bool
     */
    public function hasParameter($nameSpace, $paramName)
    {
        return isset($this->parameters[$nameSpace][$paramName]);
    }

    /**
     * returns a parameter of the given namespace
     *
     * @param   string  $nameSpace   the namespace where the parameter is in
     * @param   string  $paramName   the name of the parameter to check
     * @return  string
     */
    public function getParameter($nameSpace, $paramName)
    {
        if (isset($this->parameters[$nameSpace][$paramName]) === true) {
            return $this->parameters[$nameSpace][$paramName];
        }
        
        return null;
    }

    /**
     * removes a parameter
     *
     * @param   string  $nameSpace  the namespace where the parameter is in
     * @param   string  $paramName  the name of the parameter to remove
     * @return  bool    true if successful, else false
     */
    public function removeParameter($nameSpace, $paramName)
    {
        if ($this->hasParameter($nameSpace, $paramName) === false) {
            return true;
        }
        
        $result = $this->xsltProcessor->removeParameter($nameSpace, $paramName);
        if (false === $result) {
            return false;
        }
        
        unset($this->parameters[$nameSpace][$paramName]);
        if (count($this->parameters[$nameSpace]) === 0) {
            unset($this->parameters[$nameSpace]);
        }
        
        return true;
    }

    /**
     * set a list of parameters for the given namespace
     *
     * @param   string            $nameSpace  the namespace where the parameters are in
     * @param   array             $params     the list of parameters to set: name => value
     * @return  stubXSLProcessor
     * @throws  stubXSLProcessorException
     */
    public function withParameters($nameSpace, array $params)
    {
        if (false === $this->xsltProcessor->setParameter($nameSpace, $params)) {
            throw new stubXSLProcessorException('Could not set parameters in ' . $nameSpace);
        }
        
        if (isset($this->parameters[$nameSpace]) === false) {
            $this->parameters[$nameSpace] = array();
        }
        
        $this->parameters[$nameSpace] = array_merge($this->parameters[$nameSpace], $params);
        return $this;
    }

    /**
     * returns all parameters for the given namespace3
     *
     * @param   string  $nameSpace
     * @return  array
     */
    public function getParameters($nameSpace)
    {
        if (isset($this->parameters[$nameSpace]) === true) {
            return $this->parameters[$nameSpace];
        }
        
        return array();
    }

    /**
     * returns a list of all used namespaces
     *
     * @return  array
     */
    public function getParameterNamespaces()
    {
        return array_keys($this->parameters);
    }

    /**
     * removes a list of parameters for the given namespace
     *
     * @param   string              $nameSpace  the namespace where the parameters are in
     * @param   array               $params     the list of parameters to remove
     * @return  array<string,bool>  return values of removing the parameters
     */
    public function removeParameters($nameSpace, array $params)
    {
        $result = array();
        foreach ($params as $paramName) {
            $result[$paramName] = $this->removeParameter($nameSpace, $paramName);
        }
        
        return $result;
    }

    /**
     * does some corrections after cloning
     */
    public function __clone()
    {
        $this->createXSLTProcessor();
        foreach ($this->parameters as $nameSpace => $params) {
            $this->xsltProcessor->setParameter($nameSpace, $params);
        }
        
        foreach ($this->stylesheets as $stylesheet) {
            $this->xsltProcessor->importStylesheet($stylesheet);
        }
        
        $this->document = null;
    }

    /**
     * transoforms the document into another DOMDocument
     * 
     * @return  DOMDocument
     * @throws  stubXSLProcessorException
     */
    public function toDoc()
    {
        $this->registerCallbacks();
        $result = $this->xsltProcessor->transformToDoc($this->document);
        if (false === $result) {
            throw new stubXSLProcessorException($this->createMessage());
        }
        
        return $result;
    }

    /**
     * transforms the document and saves it to the given uri, returns the
     * amount of bytes written
     *
     * @param   string  $uri
     * @return  int
     * @throws  stubXSLProcessorException
     */
    public function toURI($uri)
    {
        $this->registerCallbacks();
        $bytes = $this->xsltProcessor->transformToURI($this->document, $uri);
        if (false === $bytes) {
            throw new stubXSLProcessorException($this->createMessage());
        }
        
        return $bytes;
    }

    /**
     * transforms the document and returns the result as string
     *
     * @return  string
     * @throws  stubXSLProcessorException
     */
    public function toXML()
    {
        $this->registerCallbacks();
        $result = $this->xsltProcessor->transformToXML($this->document);
        if (false === $result) {
            throw new stubXSLProcessorException($this->createMessage());
        }
        
        return $result;
    }

    /**
     * register all callback instances
     */
    protected function registerCallbacks()
    {
        if (count($this->callbacks) > 0) {
            $xslCallback = stubXSLCallback::getInstance();
            $xslCallback->clearCallbacks();
            foreach ($this->callbacks as $name => $callback) {
                $xslCallback->setCallback($name, $callback);
            }
        }
        
        $this->xsltProcessor->registerPHPFunctions(array('stubXSLCallback::invoke'));
    }

    /**
     * creates a message frim the last libxml error
     *
     * @return  string
     */
    protected function createMessage()
    {
        $message = '';
        foreach (libxml_get_errors() as $error) {
            $message .= trim($error->message) . (($error->file) ? (' in file ' . $error->file) : ('')) . ' on line ' . $error->line . ' in column ' . $error->column . "\n";
        }

        libxml_clear_errors();
        if (strlen($message) === 0) {
            return 'Transformation failed: unknown error.';
        }

        return $message;
    }
}
?>