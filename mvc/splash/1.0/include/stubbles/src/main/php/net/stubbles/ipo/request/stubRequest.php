<?php
/**
 * Interface for handling request variables.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueError',
                      'net::stubbles::ipo::request::filter::stubFilter',
                      'net::stubbles::ipo::request::validator::stubValidator'
);
/**
 * Interface for handling request variables.
 * 
 * The request contains all data send by the user-agent: parameters,
 * headers and cookies. It allows to retrieve this values via validators
 * and filters. Errors that occurred during filtering are collected as well.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 * @see         http://stubbles.net/wiki/Docs/Validators
 */
interface stubRequest extends stubObject
{
    /**
     * registry key for request class to be used
     */
    const CLASS_REGISTRY_KEY = 'net.stubbles.ipo.request.class';
    /**
     * request source: cookies
     */
    const SOURCE_COOKIE      = 1;
    /**
     * request source: header
     */
    const SOURCE_HEADER      = 2;
    /**
     * request source: parameters
     */
    const SOURCE_PARAM       = 4;

    /**
     * checks if requestor accepts cookies
     *
     * @return  bool
     */
    public function acceptsCookies();

    /**
     * checks whether a request value is set or not
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValue($valueName, $source = self::SOURCE_PARAM);

    /**
     * add a value error for a request value
     *
     * @param  stubRequestValueError  $valueError
     * @param  string                 $valueName
     * @param  int                    $source
     */
    public function addValueError(stubRequestValueError $valueError, $valueName, $source = self::SOURCE_PARAM);

    /**
     * checks whether a request value has any error after a filter was applied
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueError($valueName, $source = self::SOURCE_PARAM);

    /**
     * checks whether a request value has a specific error after a filter was applied
     *
     * @param   string  $valueName  name of request value
     * @param   string  $errorId    id of error to check for
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueErrorWithId($valueName, $errorId, $source = stubRequest::SOURCE_PARAM);

    /**
     * returns a request value error with a specific id
     *
     * @param   string  $valueName  name of request value
     * @param   string  $errorId    id of error to check for
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  stubRequestValueError|null
     */
    public function getValueErrorWithId($valueName, $errorId, $source = stubRequest::SOURCE_PARAM);
    
    /**
     * returns a list of errors for given request value
     *
     * @param   string  $valueName  name of request value
     * @param   int     $source     optional  source type: cookie, header, param
     * @return  array<stubRequestValueError>
     */
    public function getValueError($valueName, $source = self::SOURCE_PARAM);

    /**
     * checks whether there are any value errors
     *
     * @param   int   $source  optional  source type: cookie, header, param
     * @return  bool
     */
    public function hasValueErrors($source = self::SOURCE_PARAM);

    /**
     * returns a list of all request value names with their errors
     *
     * @param   int  $source  optional  source type: cookie, header, param
     * @return  array<string,array<stubRequestValueError>>
     */
    public function getValueErrors($source = self::SOURCE_PARAM);

    /**
     * cancels the request, e.g. if it was detected that it is invalid
     */
    public function cancel();

    /**
     * checks whether the request has been cancelled or not
     *
     * @return  bool
     */
    public function isCancelled();

    /**
     * returns the request method
     *
     * @return  string
     */
    public function getMethod();

    /**
     * returns the uri of the request
     * 
     * @return  string
     */
    public function getURI();

    /**
     * checks whether raw data is valid or not
     *
     * @param   stubValidator  $validator  validator to use
     * @return  bool
     */
    public function validateRawData(stubValidator $validator);

    /**
     * returns the validated raw data
     * 
     * If the validator says the raw data is not valid the return value is null.
     *
     * @param   stubValidator  $validator  validator to use
     * @return  string
     */
    public function getValidatedRawData(stubValidator $validator);

    /**
     * returns the raw data filtered
     *
     * @param   stubFilter  $filter
     * @return  mixed
     * @throws  stubFilterException
     */
    public function getFilteredRawData(stubFilter $filter);

    /**
     * checks whether a request value is valid or nor
     *
     * @param   stubValidator  $validator  validator to use
     * @param   string         $valueName  name of request value
     * @param   int            $source     optional  source type: cookie, header, param
     * @return  bool
     */
    public function validateValue(stubValidator $validator, $valueName, $source = self::SOURCE_PARAM);

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
    public function getValidatedValue(stubValidator $validator, $valueName, $source = self::SOURCE_PARAM);

    /**
     * returns a filtered request value
     *
     * @param   stubFilter  $filter     filter to use
     * @param   string      $valueName  name of request value
     * @param   int         $source     optional  source type: cookie, header, param
     * @return  mixed
     */
    public function getFilteredValue(stubFilter $filter, $valueName, $source = self::SOURCE_PARAM);

    /**
     * return an array of all keys registered in this request
     *
     * @param   int            $source     optional  source type: cookie, header, param
     * @return  array<string>
     */
    public function getValueKeys($source = self::SOURCE_PARAM);
}
?>