<?php
/**
 * Class for access to request data.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest');
/**
 * Class for access to request data.
 * 
 * This class offers a basic implementation for the stubRequest interface
 * from which any specialized request classes can be inherited.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
abstract class stubAbstractRequest extends stubBaseObject implements stubRequest
{
    /**
     * list of unfiltered request variables
     *
     * @var  array<string,string>
     */
    protected $unsecureParams  = array();
    /**
     * list of unfiltered header data
     *
     * @var  array<string,string>
     */
    protected $unsecureHeaders = array();
    /**
     * list of unfiltered cookie data
     *
     * @var  array<string,string>
     */
    protected $unsecureCookies = array();
    /**
     * list of errors that occurred while applying a filter on a param value
     * 
     * @var  array<string,array<stubRequestValueError>>
     */
    protected $paramErrors     = array();
    /**
     * list of errors that occurred while applying a filter on a header value
     * 
     * @var  array<string,array<stubRequestValueError>>
     */
    protected $headerErrors    = array();
    /**
     * list of errors that occurred while applying a filter on a cookie value
     * 
     * @var  array<string,array<stubRequestValueError>>
     */
    protected $cookieErrors    = array();
    /**
     * switch whether request has been cancelled or not
     *
     * @var  bool
     */
    protected $isCancelled     = false;

    /**
     * constructor
     */
    public final function __construct()
    {
        $this->doConstuct();
    }

    /**
     * template method for child classes to do the real construction
     */
    protected abstract function doConstuct();

    /**
     * cloning is forbidden
     *
     * @throws  stubRuntimeException
     */
    public final function __clone()
    {
        throw new stubRuntimeException('Cloning of request is not allowed!');
    }

    /**
     * checks if requestor accepts cookies
     *
     * Warning! Detection is based on the amount of cookie values returned by
     * the user agent. If the user agent did not send any cookies this does not
     * necessarily mean that the user agent will not accept cookies.
     *
     * @return  bool
     */
    public function acceptsCookies()
    {
        return (count($this->unsecureCookies) > 0);
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
        $data = $this->getValues($source);
        return isset($data[$valueName]);
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
        $error =& $this->getErrors($source);
        if (isset($error[$valueName]) === false) {
            $error[$valueName] = array($valueError->getId() => $valueError);
        } else {
            $error[$valueName][$valueError->getId()] = $valueError;
        }
    }

    /**
     * checks whether a request value has any error after a filter was applied
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueError($valueName, $source = stubRequest::SOURCE_PARAM)
    {
        $error = $this->getErrors($source);
        return isset($error[$valueName]);
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
        $error = $this->getErrors($source);
        return (isset($error[$valueName]) && isset($error[$valueName][$errorId]));
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
        $error = $this->getErrors($source);
        if (isset($error[$valueName]) && isset($error[$valueName][$errorId])) {
            return $error[$valueName][$errorId];
        } else {
            return null;
        }
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
        $error = $this->getErrors($source);
        if (isset($error[$valueName]) === true) {
            return $error[$valueName];
        }
        
        return array();
    }

    /**
     * checks whether there are any value errors
     *
     * @param   int   $source  optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueErrors($source = self::SOURCE_PARAM)
    {
        return (count($this->getErrors($source)) > 0);
    }

    /**
     * returns a list of all request value names with their errors
     *
     * @param   int  $source  optional  source type: cookie, header, param
     * @return  array<string,array<stubRequestValueError>>
     */
    public function getValueErrors($source = stubRequest::SOURCE_PARAM)
    {
        return $this->getErrors($source);
    }

    /**
     * returns the array with errors from requested source
     *
     * @param   int  $source  source type: cookie, header, param
     * @return  array<string,string>
     */
    protected function &getErrors($source)
    {
        switch ($source) {
            case stubRequest::SOURCE_PARAM:
                return $this->paramErrors;
                
            case stubRequest::SOURCE_COOKIE:
                return $this->cookieErrors;
                
            case stubRequest::SOURCE_HEADER:
                return $this->headerErrors;
            
            default:
                return $this->paramErrors;
        }
    }

    /**
     * cancels the request, e.g. if it was detected that it is invalid
     */
    public function cancel()
    {
        $this->isCancelled = true;
    }

    /**
     * checks whether the request has been cancelled or not
     *
     * @return  bool
     */
    public function isCancelled()
    {
        return $this->isCancelled;
    }

    /**
     * checks whether raw data is valid or not
     *
     * @param   stubValidator  $validator  validator to use
     * @return  bool
     */
    public function validateRawData(stubValidator $validator)
    {
        return $validator->validate($this->getRawData());
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
        $rawData = $this->getRawData();
        if ($validator->validate($rawData) === true) {
            return $rawData;
        }
        
        return null;
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
        return $filter->execute($this->getRawData());
    }

    /**
     * returns the raw data
     *
     * @return  string
     */
    protected abstract function getRawData();

    /**
     * checks whether a request value is valid or not
     *
     * @param   stubValidator  $validator  validator to use
     * @param   string         $valueName  name of request value
     * @param   int            $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function validateValue(stubValidator $validator, $valueName, $source = stubRequest::SOURCE_PARAM)
    {
        $data = $this->getValues($source);
        if (isset($data[$valueName]) === true) {
            return $validator->validate($data[$valueName]);
        }
        
        return false;
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
        $data = $this->getValues($source);
        if (isset($data[$valueName]) === true && $validator->validate($data[$valueName]) === true) {
            return $data[$valueName];
        }
        
        return null;
    }

    /**
     * returns a filtered request value
     *
     * @param   stubFilter  $filter     filter to use
     * @param   string      $valueName  name of request value
     * @param   int         $source     optional  source type: cookie, header, param
     * @return  mixed
     */
    public function getFilteredValue(stubFilter $filter, $valueName, $source = stubRequest::SOURCE_PARAM)
    {
        $data = $this->getValues($source);
        if (isset($data[$valueName]) === false) {
            $value = null;
        } else {
            $value = $data[$valueName];
        }
            
        try {
            $value = $filter->execute($value);
        } catch (stubFilterException $fe) {
            $this->addValueError($fe->getError(), $valueName, $source);
            return null;
        }
        
        return $value;
    }

    /**
     * return an array of all keys registered in this request
     *
     * @param   int            $source  optional  source type: cookie, header, param
     * @return  array<string>
     */
    public function getValueKeys($source = stubRequest::SOURCE_PARAM)
    {
        return array_keys($this->getValues($source));
    }

    /**
     * returns the array with data from requested source
     *
     * @param   int  $source  source type: cookie, header, param
     * @return  array<string,string>
     */
    protected function getValues($source)
    {
        switch ($source) {
            case stubRequest::SOURCE_PARAM:
                return $this->unsecureParams;
                
            case stubRequest::SOURCE_COOKIE:
                return $this->unsecureCookies;
                
            case stubRequest::SOURCE_HEADER:
                return $this->unsecureHeaders;
            
            default:
                return $this->unsecureParams;
        }
    }
}
?>