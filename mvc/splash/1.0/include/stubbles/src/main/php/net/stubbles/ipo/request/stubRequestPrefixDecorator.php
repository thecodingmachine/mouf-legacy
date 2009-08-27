<?php
/**
 * Class for handling request variables with a special prefix.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest');
/**
 * Class for handling request variables with a special prefix.
 * 
 * This acts as a decorator around a stubRequest instance and allows to restrict
 * access to request values starting with a prefix. Via param $sources from the
 * constructor it is controlled for which source the prefix should be applied. As
 * it is a bit switch you may not only use the stubRequest::SOURCE_* constansts
 * but any combination of them as well: e.g. stubRequest::SOURCE_COOKIE +
 * stubRequest::SOURCE_PARAM applies prefixes on cookies and parameters, but not
 * on headers.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
class stubRequestPrefixDecorator extends stubBaseObject implements stubRequest
{
    /**
     * the decorated request
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * the prefix to use
     *
     * @var  string
     */
    protected $prefix;
    /**
     * sources to apply prefix on
     * 
     * Can be any of stubRequest::SOURCE_* or a combination of them (bit value)
     *
     * @var  int
     */
    protected $sources;

    /**
     * constructor
     *
     * @param  stubRequest  $request  the request to decorate
     * @param  string       $prefix   the prefix to use
     * @param  int          $sources  optional  can be any of stubRequest::SOURCE_* or a combination of them (bit value)
     */
    public function __construct(stubRequest $request, $prefix, $sources = stubRequest::SOURCE_PARAM)
    {
        $this->request = $request;
        $this->prefix  = $prefix;
        $this->sources = $sources;
    }

    /**
     * sets the prefix to another value
     *
     * @param  string  $prefix  the prefix to use
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * checks if requestor accepts cookies
     *
     * @return  bool
     */
    public function acceptsCookies()
    {
        return $this->request->acceptsCookies();
    }

    /**
     * checks whether a request value is set or not
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValue($valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->hasValue($valueName, $source);
    }

    /**
     * add a value error for a request value
     *
     * @param  stubRequestValueError  $valueError
     * @param  string                 $valueName
     * @param  int                    $source
     */
    public function addValueError(stubRequestValueError $valueError, $valueName, $source = self::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        $this->request->addValueError($valueError, $valueName, $source);
    }

    /**
     * checks whether a request value has an error after a filter was applied
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueError($valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->hasValueError($valueName, $source);
    }

    /**
     * checks whether a request value has a specific error after a filter was applied
     *
     * @param   string  $valueName  name of request value
     * @param   string  $errorId    id of error to check for
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueErrorWithId($valueName, $errorId, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->hasValueErrorWithId($valueName, $errorId, $source);
    }
    
    /**
     * returns a request value error with a specific id
     *
     * @param   string  $valueName  name of request value
     * @param   string  $errorId    id of error to check for
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  stubRequestValueError|null
     */
    public function getValueErrorWithId($valueName, $errorId, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->getValueErrorWithId($valueName, $errorId, $source);
    }

    /**
     * returns a list of errors for given request value
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  array<stubRequestValueError>
     */
    public function getValueError($valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->getValueError($valueName, $source);
    }

    /**
     * checks whether there are any value errors
     *
     * @param   int   $source  optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueErrors($source = self::SOURCE_PARAM)
    {
        return (count($this->getValueErrors($source)) > 0);
    }

    /**
     * returns a list of all request value names with their errors
     *
     * @param   int  $source  optional  source type: cookie, header, param
     * @return  array<string,array<stubRequestValueError>>
     */
    public function getValueErrors($source = stubRequest::SOURCE_PARAM)
    {
        $valueErrors   = $this->request->getValueErrors($source);
        if ($this->applyPrefix($source) == false || count($valueErrors) == 0) {
            return $valueErrors;
        }
        
        $returnedErrors = array();
        $checkLength    = strlen($this->prefix) + 1;
        foreach ($valueErrors as $valueName => $valueErrorList) {
            if (substr($valueName, 0, $checkLength) == $this->prefix . '_') {
                $returnedErrors[str_replace($this->prefix . '_', '', $valueName)] = $valueErrorList;
            }
        }
        
        return $returnedErrors;
    }

    /**
     * cancels the request, e.g. if it was detected that it is invalid
     * 
     * @param  stubEventDispatcher  $dispatcher  optional  dispatcher to use for signalling
     *                                                     the event, if none given the
     *                                                     default one will be used
     */
    public function cancel(stubEventDispatcher $dispatcher = null)
    {
        $this->request->cancel($dispatcher);
    }

    /**
     * checks whether the request has been cancelled or not
     *
     * @return  bool
     */
    public function isCancelled()
    {
        return $this->request->isCancelled();
    }

    /**
     * returns the request method
     *
     * @return  string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * returns the uri of the request
     * 
     * @return  string
     */
    public function getURI()
    {
        return $this->request->getURI();
    }

    /**
     * checks whether raw data is valid or not
     *
     * @param   stubValidator  $validator  validator to use
     * @return  bool
     */
    public function validateRawData(stubValidator $validator)
    {
        return $this->request->validateRawData($validator);
    }

    /**
     * returns the validated raw data
     * 
     * If the validator says the value is not valid the return value is null.
     *
     * @param   stubValidator  $validator  validator to use
     * @return  string
     */
    public function getValidatedRawData(stubValidator $validator)
    {
        return $this->request->getValidatedRawData($validator);
    }

    /**
     * returns the raw data filtered
     *
     * @param   stubFilter  $filter
     * @return  mixed
     * @throws  stubFilterException
     */
    public function getFilteredRawData(stubFilter $filter)
    {
        return $this->request->getFilteredRawData($filter);
    }

    /**
     * checks whether a request value is valid or nor
     *
     * @param   stubValidator  $validator  validator to use
     * @param   string         $valueName  name of request value
     * @param   int            $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function validateValue(stubValidator $validator, $valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->validateValue($validator, $valueName, $source);
    }

    /**
     * returns the validated request value
     * 
     * If the validator says the value is not valid the return value is null.
     *
     * @param   stubValidator  $validator  validator to use
     * @param   string         $valueName  name of request value
     * @param   int            $source     optional  source type: cookie, header, param
     * @return  string
     */
    public function getValidatedValue(stubValidator $validator, $valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->getValidatedValue($validator, $valueName, $source);
    }

    /**
     * returns a filtered request value
     *
     * @param   stubFilter  $filter     filter to use
     * @param   string      $valueName  name of request value
     * @param   int         $source     optional  source type: cookie, header, param
     * @return  mixed
     * @throws  stubFilterException
     */
    public function getFilteredValue(stubFilter $filter, $valueName, $source = stubRequest::SOURCE_PARAM)
    {
        if ($this->applyPrefix($source) == true) {
            $valueName = $this->prefix . '_' . $valueName;
        }
        
        return $this->request->getFilteredValue($filter, $valueName, $source);
    }

    /**
     * return an array of all keys registered in this request
     *
     * @param   int            $source  optional  source type: cookie, header, param
     * @return  array<string>
     */
    public function getValueKeys($source = stubRequest::SOURCE_PARAM)
    {
        $valueKeys = $this->request->getValueKeys($source);
        if ($this->applyPrefix($source) == false) {
            return $valueKeys;
        }
        
        $returnedValueKeys = array();
        $checkLength       = strlen($this->prefix) + 1;
        foreach ($valueKeys as $valueName) {
            if (substr($valueName, 0, $checkLength) == $this->prefix . '_') {
                $returnedValueKeys[] = substr($valueName, $checkLength);
            }
        }
        
        return $returnedValueKeys;
    }

    /**
     * check whether the prefix has to be applied for requested source
     *
     * @param   int   $source  can be any of stubRequest::SOURCE_* or a combination of them (bit value)
     * @return  bool
     */
    protected function applyPrefix($source)
    {
        return (($this->sources & $source) != 0);
    }
}
?>