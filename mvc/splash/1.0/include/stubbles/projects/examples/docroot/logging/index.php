<?php
echo 'The PHP code:<br />';
highlight_file(__FILE__);

require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::ipo::request::stubWebRequest',
                      'net::stubbles::ipo::session::stubPHPSession',
                      'net::stubbles::util::log::log',
                      'net::stubbles::util::log::stubLoggerXJConfInitializer');
class Bootstrap
{
    public static function main()
    {
        // initialize logger from configuration
        $loggerXJConfInitializer = new stubLoggerXJConfInitializer();
        $loggerXJConfInitializer->init();

        // now the real work for logging
        // first we create a new logdata object with target trail and level
        // info
        // the type of the logdata object can be configured in
        // config/xml/config.xml with the property net.stubbles.util.log.class
        // if property not set an instance of
        // net::stubbles::util::log::stubBaseLogData will be created
        $logData = stubLogDataFactory::create('trail', stubLogger::LEVEL_INFO);

        // we add some more data that we want to log
        $logData->addData('logging/index.php');
        $logData->addData('foo');

        // and finally we send the logdata to all log appenders that listen for
        // the level of the logdata (in this case stubLogger::LEVEL_INFO)
        stubLogger::logToAll($logData);

        // now take a look into the log directory, there should be a logfile
        // named trail-Y-m-d.log containing the logdata
        // the contents are
        // [timestamp]|[session-id]|logging/index.php|foo

        // Display logs
        echo '<br /><br />The logfiles:<br />';
        foreach (new DirectoryIterator(stubConfig::getLogPath()) as $log) {
            if ($log->isDot()) {
                continue;
            }
            if ($log->isDir()) {
                continue;
            }
            printf('<b>%s</b></br /><pre>%s</pre>', $log->getFilename(), file_get_contents($log->getPathname()));
        }
    }
}
Bootstrap::main();
?>