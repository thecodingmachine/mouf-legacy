<?php
/**
 * Class for creating request value error codes from an xml configuration file.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueErrorFactory',
                      'net::stubbles::lang::exceptions::stubConfigurationException',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class for creating request value error codes from an xml configuration file.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 * @uses        http://php.xjconf.net/
 */
class stubRequestValueErrorXJConfFactory extends stubXJConfAbstractInitializer implements stubRequestValueErrorFactory
{
    /**
     * switches whether initialization has been done
     *
     * @var  array<string,stubRequestValueError>
     */
    protected static $requestValueErrors = null;

    /**
     * refreshes the internal cache of the class
     */
    public static function refresh()
    {
        self::$requestValueErrors = null;
    }

    /**
     * constructor
     * 
     * @throws  stubConfigurationException
     */
    public function __construct()
    {
        if (null != self::$requestValueErrors) {
            return;
        }
        
        self::$requestValueErrors = array();
        $xjconf = new stubXJConfProxy($this);
        try {
            $xjconf->process(stubFactory::getResourceURIs('ipo/request.xml'));
        } catch (stubXJConfException $xjce) {
            throw new stubConfigurationException($xjce->getMessage());
        }
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     */
    public function getDescriptor($type)
    {
        return 'request';
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        return self::$requestValueErrors;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        self::$requestValueErrors = $cacheData;
    }

    /**
     * will be called in case the stubXJConfProxy did not found the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        self::$requestValueErrors = array_merge(self::$requestValueErrors, $xjconf->getConfigValues());
    }

    /**
     * creates the stubRequestValueError with the id from the given source
     *
     * @param   string                 $id      id of RequestValueError to create
     * @return  stubRequestValueError
     * @throws  stubIllegalArgumentException
     */
    public function create($id)
    {
        if (isset(self::$requestValueErrors[$id]) == true) {
            return clone self::$requestValueErrors[$id];
        }
        
        throw new stubIllegalArgumentException('RequestValueError with id ' . $id . ' does not exist.');
    }
}
?>