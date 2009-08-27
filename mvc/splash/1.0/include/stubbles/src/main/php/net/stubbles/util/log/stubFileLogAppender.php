<?php
/**
 * A log appenders that writes log data to files.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 */
stubClassLoader::load('net::stubbles::util::log::stubLogAppender');
/**
 * A log appenders that writes log data to files.
 * 
 * This log appender writes the log data into a logfile using the error_log()
 * function of PHP. The logfile name will be [target]-[Y-m-d].log where target
 * is the return value of stubLogData::getTarget().
 *
 * @package     stubbles
 * @subpackage  util_log
 * @uses        http://php.net/error_log
 */
class stubFileLogAppender extends stubBaseObject implements stubLogAppender
{
    /**
     * the directory to write the logfiles into
     *
     * @var  string
     */
    protected $logDir = '';
    /**
     * mode for new directories
     *
     * @var  int
     */
    protected $mode   = 0700;

    /**
     * constructor
     *
     * @param  string  $logDir  optional  the directory to write the logfiles into
     */
    public function __construct($logDir = null)
    {
        if (null == $logDir) {
            $logDir = stubConfig::getLogPath();
        }
        
        $this->setLogDir($logDir);
    }

    /**
     * sets the configuration data
     *
     * @param  array  $config
     */
    public function setConfig(array $config)
    {
        if (isset($config['logDir']) == true) {
            $this->logDir = $config['logDir'];
        }
        
        if (isset($config['mode']) === true) {
            $this->mode = $config['mode'];
        }
    }

    /**
     * returns the configuration
     *
     * @return  array
     */
    public function getConfig()
    {
        return array('logDir' => $this->logDir,
                     'mode'   => $this->mode
               );
    }

    /**
     * set the logpath
     * 
     * The log director may have placeholders: {Y} will be replaced with the
     * current year, {M} will be replaced with the current month, e.g.
     * /path/to/logs/{Y}/{M} would become /path/to/logs/2007/01.
     *
     * @param  string  $logDir
     */
    public function setLogDir($logDir)
    {
        $this->logDir = $logDir;
    }

    /**
     * returns the logpath
     *
     * @return  string
     */
    public function getLogDir()
    {
        return $this->logDir;
    }

    /**
     * sets the mode for new log directories
     *
     * @param  int  $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * returns the mode for new log directories
     *
     * @return  int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * builds the log directory
     *
     * @return  string
     */
    protected function buildLogDir()
    {
        return str_replace('{Y}', date('Y'), str_replace('{M}', date('m'), $this->logDir));
    }

    /**
     * append the log data to the log target
     * 
     * The basename of the logfile will be [target]-[Y-m-d].log where target
     * is the return value of stubLogData::getTarget().
     *
     * @param  stubLogData  $logData
     */
    public function append(stubLogData $logData)
    {
        $logDir  = $this->buildLogDir();
        if (file_exists($logDir) == false) {
            mkdir($logDir, $this->mode, true);
        }
        
        error_log($logData->get() . "\n", 3, $logDir . '/' . $logData->getTarget() . '-' . date('Y-m-d') . '.log');
    }

    /**
     * finalize the log target
     */
    public function finalize()
    {
        // nothing to do, therefore intentionelly left blank
    }
}
?>