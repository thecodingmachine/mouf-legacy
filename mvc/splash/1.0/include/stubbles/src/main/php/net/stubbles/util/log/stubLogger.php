<?php
/**
 * Class for logging.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogAppender',
                      'net::stubbles::util::log::stubLogData'
);
/**
 * Class for logging.
 * 
 * The logger is the interface to log data into differant targets. The logger
 * itself does not know where to write the log data - it just uses log appenders
 * which in turn do the real work. A logger is a collection of such appenders.
 * Even if it looks like a singleton it is possible to have differant instances
 * of a logger. Each logger uses a differant id and can be applied on differant
 * logging levels. For instance, you may have one logger that is applicable for
 * debug logging and another one that is used for the other error levels.
 *
 * @package     stubbles
 * @subpackage  util_log
 */
class stubLogger extends stubBaseObject
{
    /**
     * default logger id
     */
    const DEFAULT_ID            = 'default';
    /**
     * log level: no logging
     */
    const LEVEL_NONE            = 0;
    /**
     * log level: debug only
     */
    const LEVEL_DEBUG           = 1;
    /**
     * log level: info data only
     */
    const LEVEL_INFO            = 2;
    /**
     * log level: warnings only
     */
    const LEVEL_WARN            = 4;
    /**
     * log level: errors only
     */
    const LEVEL_ERROR           = 8;
    /**
     * log level: all
     */
    const LEVEL_ALL             = 15;
    /**
     * list of available logger instances
     *
     * @var  array<stubLogger>
     */
    protected static $instances = array();
    /**
     * id of the logger
     *
     * @var  string
     */
    protected $id;
    /**
     * log level where this logger is for
     *
     * @var  int
     */
    protected $level;
    /**
     * list of log appenders to log data to
     *
     * @var  array<stubLogAppender>
     */
    protected $logAppender      = array();

    /**
     * constructor
     *
     * If no level is specified the logger will be applicable for all log levels.
     * 
     * @param  string  $id     id of the logger
     * @param  int     $level  optional  level where the logger is applicable for
     */
    protected final function __construct($id, $level = self::LEVEL_ALL)
    {
        $this->id    = $id;
        $this->level = $level;
    }

    /**
     * destructor
     * 
     * Calls all log appenders that they should finalize their work.
     */
    public final function __destruct()
    {
        foreach ($this->logAppender as $logAppender) {
            $logAppender->finalize();
        }
    }

    /**
     * returns a logger instance
     * 
     * If the logger with the given id already exists this will be returned,
     * else a new logger will be created using the given level and returned.
     * If no level is specified and a new logger is created it will be
     * applicable for all levels.
     *
     * @param   string      $id     id of the logger
     * @param   int         $level  optional  level where the logger is applicable for
     * @return  stubLogger
     */
    public static function getInstance($id = self::DEFAULT_ID, $level = self::LEVEL_ALL)
    {
        if (isset(self::$instances[$id]) == false) {
            self::$instances[$id] = new self($id, $level);
        }
        
        return self::$instances[$id];
    }

    /**
     * returns a list of existing instances
     *
     * @return  array<string>
     */
    public static function getInstanceList()
    {
        return array_keys(self::$instances);
    }

    /**
     * destroys the logger with the given id
     *
     * @param  string  $id  id of the logger
     */
    public static function destroyInstance($id)
    {
        if (isset(self::$instances[$id]) == true) {
            self::$instances[$id] = null;
            unset(self::$instances[$id]);
        }
    }

    /**
     * clone is not supported
     *
     * @throws  stubLoggerException
     */
    protected final function __clone()
    {
        throw new stubLoggerException('Cloning a logger is not allowed.');
    }

    /**
     * returns the id of the logger
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * returns the level where the logger is applicable for
     *
     * @return  int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * checks whether the logger is applicable for the given level
     *
     * @param   int   $level
     * @return  bool
     */
    public function isApplicable($level)
    {
        if (self::LEVEL_ALL == $level && self::LEVEL_ALL > $this->level) {
            return false;
        }
        
        return (($this->level & $level) != 0);
    }

    /**
     * adds a log appender to the logger
     * 
     * A log appender is responsible for writing the log data.
     *
     * @param  stubLogAppender  $logAppender
     */
    public function addLogAppender(stubLogAppender $logAppender)
    {
        $this->logAppender[] = $logAppender;
    }

    /**
     * returns a list of log appenders appended to the logger
     *
     * @return  array<stubLogAppender>
     */
    public function getLogAppenders()
    {
        return $this->logAppender;
    }

    /**
     * log data to all loggers created
     *
     * @param  stubLogData  $logData  contains the data to log
     */
    public static function logToAll(stubLogData $logData)
    {
        foreach (self::$instances as $logger) {
            if (null === $logger || $logger->isApplicable($logData->getLevel()) == false) {
                continue;
            }
            
            $logger->log($logData);
        }
    }

    /**
     * sends log data to all registered log appenders
     *
     * @param  stubLogData  $logData  contains the data to log
     */
    public function log(stubLogData $logData)
    {
        foreach ($this->logAppender as $logAppender) {
            $logAppender->append($logData);
        }
    }
}
?>