<?php
/**
 * Class to wrap xincludes transparently as stream wrapper.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml
 * @version     $Id: stubXMLXIncludeStreamWrapper.php 1904 2008-10-25 14:04:33Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::stubMode',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubFileNotFoundException',
                      'net::stubbles::lang::exceptions::stubIOException',
                      'net::stubbles::xml::stubXMLException',
                      'net::stubbles::xml::xsl::stubXSLProcessor'
);
/**
 * Class to wrap xincludes transparently as stream wrapper.
 *
 * @package     stubbles
 * @subpackage  xml
 */
class stubXMLXIncludeStreamWrapper extends stubBaseObject
{
    /**
     * switch whether class has already been registered as stream wrapper or not
     *
     * @var  bool
     */
    private static $registered      = false;
    /**
     * the xsl processor to use for the transformation
     *
     * @var  stubXSLProcessor
     */
    protected static $xslProcessor;
    /**
     * path to cache
     *
     * @var  string
     */
    protected static $cachePath;
    /**
     * list of include pathes where files may reside
     *
     * @var  array<string>
     */
    protected static $includePathes = array();
    /**
     * current xml file
     *
     * @var  string
     */
    protected $fileName;
    /**
     * file name of the cached file
     *
     * @var  string
     */
    protected $cachedFileName;
    /**
     * the part that will be included
     *
     * @var  string
     */
    protected $part;
    /**
     * current file pointer
     *
     * @var  resource
     */
    protected $fp;

    /**
     * registers the class as stream wrapper for the sml protocol
     *
     * @throws  stubXMLException
     */
    public static function register()
    {
        if (true === self::$registered) {
            return;
        }

        if (stream_wrapper_register('xinc', __CLASS__) === false) {
            throw new stubXMLException('A handler has already been registered for the xinc protocol.');
        }

        self::$registered = true;
    }

    /**
     * set the xsl processor to use
     *
     * @param  stubXSLProcessor  $xslProcessor
     */
    public static function setXSLProcessor(stubXSLProcessor $xslProcessor)
    {
        self::$xslProcessor = $xslProcessor;
    }

    /**
     * sets the cache path to use
     *
     * @param   string  $cachePath
     * @throws  stubIOException
     */
    public static function setCachePath($cachePath)
    {
        self::$cachePath = $cachePath . '/xml/xinc';
        if (file_exists($cachePath) === false) {
            if (!@mkdir($cachePath, stubRegistry::getConfig('net.stubbles.filemode', 0700), true)) {
                throw new stubIOException("Can not create cache directory " . $cachePath . '.');
            }
        }
    }

    /**
     * adds an include path
     *
     * @param  string  $key
     * @param  string  $includePath
     */
    public static function addIncludePath($key, $includePath)
    {
        self::$includePathes[$key] = $includePath;
    }

    /**
     * returns a list of include pathes
     *
     * @return  array<string,string>
     */
    public static function getIncludePathes()
    {
        return self::$includePathes;
    }

    /**
     * open the stream
     *
     * @param   string  $path         the path to open
     * @param   string  $mode         mode for opening
     * @param   string  $options      options for opening
     * @param   string  $opened_path  full path that was actually opened
     * @return  bool
     */
    public function stream_open($path, $mode, $options, $opened_path)
    {
        $this->parsePath($path);
        if (file_exists($this->cachedFileName) === false || $this->needsRefresh() === true) {
            $this->processFile();
        }

        $fp = fopen($this->cachedFileName, 'rb');
        if (false === $fp) {
            return false;
        }

        $this->fp = $fp;
        return true;
    }

    /**
     * check whether the cached file needs to be refreshed
     *
     * @return  bool
     */
    protected function needsRefresh()
    {
        if (stubMode::$CURRENT->isCacheEnabled() === false) {
            return true;
        }
        
        if (filemtime($this->cachedFileName) > filemtime($this->fileName))  {
            return false;
        }

        return true;
    }

    /**
     * processes the file and creates a cached version of it
     *
     * @throws  stubIOException
     */
    protected function processFile()
    {
        $previousErrorHandling = libxml_use_internal_errors(true);
        $xslProcessor          = clone self::$xslProcessor;
        $domDocument           = new DOMDocument();
        if (false === $domDocument->load($this->fileName)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            $this->handleErrors($errors, $previousErrorHandling);
        }
        
        $resultDoc = $xslProcessor->onDocument($domDocument)
                                  ->toDoc();
        // we save first to prevent a infinite loop in  case of recursions
        $resultDoc->save($this->cachedFileName);
        $resultDoc->xinclude();
        $errors = libxml_get_errors();
        if (count($errors) > 0) {
            unlink($this->cachedFileName);
            libxml_clear_errors();
            $this->handleErrors($errors, $previousErrorHandling, $domDocument);
        }

        libxml_use_internal_errors($previousErrorHandling);
        $resultDoc->save($this->cachedFileName);
    }

    /**
     * handles libxml errors
     *
     * @param   array             $errors
     * @param   boolean           $previousErrorHandling
     * @param   DOMDocument       $resultDoc
     * @throws  stubXMLException
     */
    protected function handleErrors(array $errors, $previousErrorHandling, DOMDocument $resultDoc = null)
    {
        foreach ($errors as $error) {
            $message = trim($error->message) . (($error->file) ? (' in file ' . $error->file) : ('')) . ' on line ' . $error->line . ' in column ' . $error->column;
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    if (null !== $resultDoc) {
                        $this->appendError($resultDoc, 'warning', $message);
                        break;
                    }
                    
                    // break omitted if no result doc given
                
                case LIBXML_ERR_ERROR:
                    if (null !== $resultDoc) {
                        $this->appendError($resultDoc, 'error', $message);
                        break;
                    }
                    
                    // break omitted if no result doc given
                
                case LIBXML_ERR_FATAL:
                    libxml_use_internal_errors($previousErrorHandling);
                    throw new stubXMLException('Fatal error: ' . $message);
                
                default:
                    if (null !== $resultDoc) {
                        $this->appendError($resultDoc, 'warning', $message);
                    }
            }
        }
    }

    /**
     * appends the error message into the result document
     *
     * If a part for the inclusion is known it tries to append the error
     * message into this part, if no part is known the error message is
     * appended directly before the end tag of the root element.
     *
     * @param  DOMDocument  $resultDoc  the document to append the error message into
     * @param  string       $level      level of the error
     * @param  string       $message    the error message
     */
    protected function appendError(DOMDocument $resultDoc, $level, $message)
    {
        $element = $resultDoc->createElement('error', ucfirst($level) . ': ' . $message);
        $element->setAttribute('errorType', $level);
        if (null != $this->part && strlen($this->part) > 0) {
            $xpath = new DOMXPath($resultDoc);
            $entry = $xpath->query("/parts/part[@name='" . $this->part ."']")->item(0);
            if (null !== $entry) {
                $entry->appendChild($element);
            } else {
                $resultDoc->documentElement->appendChild($element);
            }
        } else {
            $resultDoc->documentElement->appendChild($element);
        }
    }

    /**
     * closes the stream
     */
    public function stream_close()
    {
        fclose($this->fp);
    }

    /**
     * read the stream up to $count bytes
     *
     * @param   int     $count  amount of bytes to read
     * @return  string
     */
    public function stream_read($count)
    {
        return fread($this->fp, $count);
    }

    /**
     * checks whether stream is at end of file
     *
     * @return  bool
     */
    public function stream_eof()
    {
        return feof($this->fp);
    }

    /**
     * returns status of stream
     *
     * @return  array
     */
    public function stream_stat()
    {
        return array('size' => filesize($this->cachedFileName));
    }

    /**
     * returns status of url
     *
     * @param   string      $path  path of url to return status for
     * @return  array|bool  false if $path does not exist, else
     */
    public function url_stat($path)
    {
        return array('size' => filesize($this->cachedFileName));
    }

    /**
     * parses the path into class members
     *
     * @param   string  $path
     * @throws  stubFileNotFoundException
     */
    protected function parsePath($path)
    {
        list($key, $fileName, $part) = sscanf($path, 'xinc://%[^/?#]/%[^?]?part=%[^$]');
        if (null !== $fileName) {
            if (isset(self::$includePathes[$key]) === false || file_exists(self::$includePathes[$key] . DIRECTORY_SEPARATOR . $fileName) === false) {
                throw new stubFileNotFoundException(self::$includePathes[$key] . DIRECTORY_SEPARATOR . $fileName);
            }
            
            $this->fileName = self::$includePathes[$key] . DIRECTORY_SEPARATOR . $fileName;
            $cacheKey       = $key . DIRECTORY_SEPARATOR;
        } elseif (file_exists($key) === true) {
            $this->fileName = $key;
            $cacheKey       = '';
        } else {
            throw new stubFileNotFoundException($fileName);
        }
        
        $this->part = $part;
        $language   = '';
        if (self::$xslProcessor->hasParameter('', 'lang') === true) {
            $language = self::$xslProcessor->getParameter('', 'lang');
        }
        
        $this->cachedFileName = self::$cachePath . DIRECTORY_SEPARATOR . $cacheKey . $language  . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists(dirname($this->cachedFileName)) === false) {
            mkdir(dirname($this->cachedFileName), stubRegistry::getConfig('net.stubbles.filemode', 0700), true);
        }
    }
}
?>