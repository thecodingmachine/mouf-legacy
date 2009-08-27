<?php
/**
 * Basic class for log data.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 * @version     $Id: stubBaseLogData.php 1928 2008-11-13 21:21:01Z mikey $
 */
stubClassLoader::load('net::stubbles::util::log::stubLogData',
                      'net::stubbles::util::log::stubLogger',
                      'net::stubbles::ipo::session::stubSession'
);
/**
 * Basic class for log data.
 * 
 * This is a basic implementation of the stubLogData interface. The first two
 * fields of the log data will be the time when the object was created and the
 * session id: [Y-m-d H:m:s]|[session_id]
 *
 * @package     stubbles
 * @subpackage  util_log
 */
class stubBaseLogData extends stubBaseObject implements stubLogData
{
    /**
     * target where the log data should go to
     *
     * @var  string
     */
    protected $target;
    /**
     * the log level of the log data
     *
     * @var  int
     * @see  stubLogger::LEVEL_*
     */
    protected $level;
    /**
     * the data to log
     *
     * @var  array<string>
     */
    protected $logData = array();

    /**
     * constructor
     * 
     * How the target is interpreted depends on the log appender which
     * takes the log data. A file log appender might use this as the basename
     * of a file, while a database log appender might use this as the name
     * of the table to write the log data into. Therefore it is advisable to
     * only use ascii characters, numbers and underscores to be sure that the
     * log appender will not mess up the log data.
     *
     * @param  string       $target   target where the log data should go to
     * @param  int          $level    optional  log level of the log data
     */
    public function __construct($target, $level = stubLogger::LEVEL_INFO)
    {
        $this->target    = $target;
        $this->level     = $level;
        $this->logData[] = date('Y-m-d H:i:s');
    }

    /**
     * sets the session
     *
     * @param  stubSession  $session  the session of the current user
     * @Inject(optional=true)
     */
    public function setSession(stubSession $session)
    {
        $this->logData[] = $session->getId();
    }

    /**
     * adds data to the log object
     * 
     * Each call to this method will add a new field. If the data contains line
     * breaks they will be replaced by <nl>. If the data contains the value of
     * stubLogData::SEPERATOR or windows line feeds they will be removed.
     *
     * @param   string           $data
     * @return  stubBaseLogData
     */
    public function addData($data)
    {
        settype($data, 'string');
        $this->logData[] = $this->escapeData($data);
        return $this;
    }

    /**
     * helper method that escapes the data to be logged
     *
     * @param   string  $data
     * @return  string
     */
    protected function escapeData($data)
    {
        return str_replace(chr(13), '', str_replace("\n", '<nl>', str_replace(stubLogData::SEPERATOR, '', $data)));
    }

    /**
     * returns the whole log data as one line
     *
     * @return  string
     */
    public function get()
    {
        return join(stubLogData::SEPERATOR, $this->logData);
    }

    /**
     * returns the log level of the log data
     * 
     * @return  int
     * @see     stubLogger::LEVEL_*
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * returns the target where the log data should go to
     * 
     * @return  string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
?>