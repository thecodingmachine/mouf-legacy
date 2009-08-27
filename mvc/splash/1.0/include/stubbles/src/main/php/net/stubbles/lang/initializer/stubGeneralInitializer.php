<?php
/**
 * Initializer for general purpose stuff.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubRuntimeException',
                      'net::stubbles::lang::initializer::stubInitializer'
);
/**
 * Initializer for general purpose stuff.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_initializer
 */
class stubGeneralInitializer extends stubBaseObject implements stubInitializer
{
    /**
     * list of en-/disabled features
     *
     * @var  array<string,bool>
     */
    protected $features = array('logger' => true,
                                'rdbms'  => false,
                                'cache'  => false#,
                               # 'events' => false
                          );
    /**
     * list of initializer classes for the single features
     *
     * @var  array<string,string>
     */
    protected $classes  = array('logger' => 'net::stubbles::util::log::stubLoggerXJConfInitializer',
                                'rdbms'  => 'net::stubbles::rdbms::stubDatabaseXJConfInitializer',
                                'cache'  => 'net::stubbles::util::cache::stubCacheXJConfInitializer'#,
                                #'events' => 'net::stubbles::events::stubEventsXJConfInitializer'
                          );

    /**
     * constructor
     *
     * @param  array  $features  optional  list of en-/disabled features
     * @param  array  $classes   optional  list of initializer classes for the single features
     */
    public function __construct(array $features = null, array $classes = null)
    {
        if (null !== $features) {
            $this->features = array_merge($this->features, $features);
        }
        
        if (null !== $classes) {
            $this->classes = array_merge($this->classes, $classes);
        }
    }

    /**
     * initializing method
     */
    public function init()
    {
        if ($this->isLoggingEnabled() === true) {
            $this->getLoggerInitializer()->init();
        }
        
        if ($this->isCachingEnabled() === true) {
            $this->getCacheInitializer()->init();
        }
        
        if ($this->isDatabaseEnabled() === true) {
            $this->getDatabaseInitializer()->init();
        }
        
        #if ($this->isEventHandlingEnabled() === true) {
        #    $this->getEventsInitializer()->init();
        #}
    }

    /**
     * checks whether logging is enabled or not
     *
     * @return  bool
     */
    public function isLoggingEnabled()
    {
        return $this->features['logger'];
    }

    /**
     * returns the logger initializer to be used
     *
     * @return  stubLoggerInitializer
     * @throws  stubRuntimeException
     */
    public function getLoggerInitializer()
    {
        if (true === $this->features['logger']) {
            stubClassLoader::load('net::stubbles::util::log::stubLoggerInitializer');
            $loggerInitializer = $this->getInitializer('logger');
            if (($loggerInitializer instanceof stubLoggerInitializer) === false) {
                throw new stubRuntimeException('Configured logger initializer is not an instance of net::stubbles::util::log::stubLoggerInitializer');
            }
            
            return $loggerInitializer;
        }
        
        return null;
    }

    /**
     * checks whether database connections are enabled or not
     *
     * @return  bool
     */
    public function isDatabaseEnabled()
    {
        return $this->features['rdbms'];
    }

    /**
     * returns the database initializer to be used
     *
     * @return  stubDatabaseInitializer
     * @throws  stubRuntimeException
     */
    public function getDatabaseInitializer()
    {
        if (true === $this->features['rdbms']) {
            stubClassLoader::load('net::stubbles::rdbms::stubDatabaseInitializer');
            $databaseInitializer = $this->getInitializer('rdbms');
            if (($databaseInitializer instanceof stubDatabaseInitializer) === false) {
                throw new stubRuntimeException('Configured database initializer is not an instance of net::stubbles::rdbms::stubDatabaseInitializer');
            }
            
            return $databaseInitializer;
        }
        
        return null;
    }

    /**
     * checks whether caching is enabled or not
     *
     * @return  bool
     */
    public function isCachingEnabled()
    {
        return $this->features['cache'];
    }

    /**
     * returns the cache initializer to be used
     *
     * @return  stubCacheInitializer
     * @throws  stubRuntimeException
     */
    public function getCacheInitializer()
    {
        if (true === $this->features['cache']) {
            stubClassLoader::load('net::stubbles::util::cache::stubCacheInitializer');
            $cacheInitializer = $this->getInitializer('cache');
            if (($cacheInitializer instanceof stubCacheInitializer) === false) {
                throw new stubRuntimeException('Configured cache initializer is not an instance of net::stubbles::util::cache::stubCacheInitializer');
            }
            
            return $cacheInitializer;
        }
        
        return null;
    }

    /**
     * checks whether caching is enabled or not
     *
     * @return  bool
     */
    public function isEventHandlingEnabled()
    {
        return false;#$this->features['events'];
    }

    /**
     * returns the events initializer to be used
     *
     * @return  stubEventsInitializer
     * @throws  stubRuntimeException
     */
    public function getEventsInitializer()
    {
        #if (true === $this->features['events']) {
        #    stubClassLoader::load('net::stubbles::events::stubEventsInitializer');
        #    $eventsInitializer = $this->getInitializer('events');
        #    if (($eventsInitializer instanceof stubEventsInitializer) === false) {
        #        throw new stubRuntimeException('Configured events initializer is not an instance of net::stubbles::events::stubEventsInitializer');
        #    }

        #    return $eventsInitializer;
        #}

        return null;
    }

    /**
     * helper method to create the initializer
     *
     * @param   string           $type
     * @return  stubInitializer
     */
    protected function getInitializer($type)
    {
        $nqClassName = stubClassLoader::getNonQualifiedClassName($this->classes[$type]);
        if (class_exists($nqClassName) === false) {
            stubClassLoader::load($this->classes[$type]);
        }
        
        return new $nqClassName();
    }
}
?>