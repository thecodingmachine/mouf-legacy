<?php
/**
 * Class for reading data from star archives via stream wrapper.
 * 
 * This class contains code from lang.base.php of the XP-framework,
 * written by Timm Friebe and Alex Kiesel.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * Class for reading data from star archives via stream wrapper.
 * 
 * This class contains code from lang.base.php of the XP-framework,
 * written by Timm Friebe and Alex Kiesel.
 * 
 * @package  star
 * @see      http://php.net/stream_wrapper_register
 */
class StarStreamWrapper
{
    /**
     * switch whether class has already been registered as stream wrapper or not
     *
     * @var  bool
     */
    private static $registered = false;
    /**
     * current position in star archive
     *
     * @var  int
     */
    protected $position;
    /**
     * current star archive data
     *
     * @var  array
     */
    protected $archive;
    /**
     * id of the file entry to retrieve
     *
     * @var  string
     */
    protected $id;

    /**
     * registers the class as stream wrapper for the star protocol
     * 
     * @throws  StarException
     */
    public static function register()
    {
        if (true == self::$registered) {
            return;
        }
        
        if (stream_wrapper_register('star', __CLASS__) == false) {
            throw new StarException('A handler has already been registered for the star protocol.');
        }
        
        self::$registered = true;
    }

    /**
     * returns index of requested archive
     *
     * @param   string  $archive  archive to retrieve index for
     * @return  array
     */
    public static function acquire($archive)
    {
        static $archives = array();
        if (isset($archives[$archive]) == true) {
            return $archives[$archive];
        }
        
        $archives[$archive] = array();
        if (file_exists($archive) == false) {
            return array();
        }
            
        $current           =& $archives[$archive];
        $current['handle'] = fopen($archive, 'rb');
        if (__FILE__ == $archive && defined('__COMPILER_HALT_OFFSET__') == true) {
            fseek($current['handle'], __COMPILER_HALT_OFFSET__);
        } else {
            fseek($current['handle'], 0);
        }
        
        $header = unpack('a4id/c1version/a8indexsize/a14buildtime/a*reserved', fread($current['handle'], 0x0100));
        if (false === $header) {
            // invalid star file
            return array();
        }
        
        $current['index']  = array();
        $current['header'] = $header;
        if (__FILE__ == $archive && defined('__COMPILER_HALT_OFFSET__') == true) {
            $current['header']['totalSize'] = __COMPILER_HALT_OFFSET__ + 0x0100;
        } else {
            $current['header']['totalSize'] = 0x0100;
        }
        
        if (1 === $header['version']) {
            $key = 'a80id/a72filename/a80path/a8size/a8offset/a*reserved';
        } else {
            $key = 'a232id/a8size/a8offset/a*reserved';
        }

        for ($i = 0; $i < $header['indexsize']; $i++) {
            $entry  = unpack($key, fread($current['handle'], 0x0100));
            $current['index'][$entry['id']]  = array('size' => (int) $entry['size'], 'offset' => (int) $entry['offset']);
            $current['header']['totalSize'] += 0x0100 + ((int) $entry['size']);
        }
        
        return $archives[$archive];
    }

    /**
     * returns the metadata of an archive
     *
     * @param   string  $archive  archive to retrieve metadata for
     * @return  array
     */
    public static function getMetaData($archive)
    {
        $current  = self::acquire($archive);
        if (isset($current['index']) == false) {
            throw new StarException('Star file ' . $archive . ' does not exist or is not a valid star file.');
        }
        
        $metaData = array();
        fseek($current['handle'], $current['header']['totalSize']);
        while (feof($current['handle']) == false) {
            $line = trim(fgets($current['handle'], 4096));
            if (empty($line) == true) {
                continue;
            }
            
            $lineData = explode(' => ', $line);
            $metaData[$lineData[0]] = $lineData[1];
        }
        
        return $metaData;
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
        $current = self::acquire($this->archive);
        if (isset($current['index'][$this->id]) == false) {
            $this->parsePath(urldecode($path));
            $current = self::acquire($this->archive);
            if (isset($current['index'][$this->id]) == false) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * read the stream up to $count bytes
     *
     * @param   int     $count  amount of bytes to read
     * @return  string
     */
    public function stream_read($count)
    {
        $current = self::acquire($this->archive);
        if (isset($current['index'][$this->id]) == false) {
            return false;
        }
        
        if ($current['index'][$this->id]['size'] == $this->position || 0 == $count) {
            return false;
        }

        fseek($current['handle'], 0x0100 + sizeof($current['index']) * 0x0100 + $current['index'][$this->id]['offset'] + $this->position, SEEK_SET);
        $bytes = fread($current['handle'], min($current['index'][$this->id]['size'] - $this->position, $count));
        $this->position += strlen($bytes);
        return $bytes;
    }

    /**
     * checks whether stream is at end of file
     *
     * @return  bool
     */
    public function stream_eof()
    {
        $current= self::acquire($this->archive);
        return $this->position >= $current['index'][$this->id]['size'];
    }

    /**
     * returns status of stream
     *
     * @return  array
     */
    public function stream_stat()
    {
        $current = self::acquire($this->archive);
        return array('size' => $current['index'][$this->id]['size']);
    }

    /**
     * returns status of url
     *
     * @param   string      $path  path of url to return status for
     * @return  array|bool  false if $path does not exist, else 
     */
    public function url_stat($path)
    {
        $this->parsePath($path);
        $current = self::acquire($this->archive);

        if (isset($current['index'][$this->id]) == false) {
            $this->parsePath(urldecode($path));
            $current = self::acquire($this->archive);
            if (isset($current['index'][$this->id]) == false) {
                return false;
            }
        }
        
        return array('size' => $current['index'][$this->id]['size']);
    }

    /**
     * parses the path into class members
     *
     * @param  string  $path
     */
    protected function parsePath($path)
    {
        list($archive, $id) = sscanf($path, 'star://%[^?]?%[^$]');
        $this->archive      = $archive;
        $this->id           = $id;
    }
}
?><?php
/**
 * Exception to be thrown in case something wents wrong with handlign star files.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * Exception to be thrown in case something wents wrong with handlign star files.
 * 
 * @package  star
 */
class StarException extends Exception
{
    // intentionally left empty
}
?><?php
/**
 * Class registry for mapping of classes to star files.
 *
 * @author   Frank Kleine <mikey@stubbles.net>
 * @author   Stephan Schmidt <schst@stubbles.net>
 * @package  star
 */
/**
 * Class registry for mapping of classes to star files.
 *
 * @package  star
 */
class StarClassRegistry
{
    /**
     * switch whether init has been done or not
     *
     * @var  bool
     */
    protected static $initDone  = false;
    /**
     * path to star files
     *
     * @var  string
     */
    protected static $libPathes = array();
    /**
     * list of classes and the file where they are in
     *
     * @var  array<string,string>
     */
    protected static $classes   = array();
    /**
     * list of files and the classes they contain
     *
     * @var  array<string,array<string>>
     */
    protected static $files     = array();

    /**
     * set the path to the star files
     *
     * @param  string  $libPath    path to lib files
     * @param  bool    $recursive  optional  recurse into sub directories of lib path
     */
    public static function addLibPath($libPath, $recursive = true)
    {
        self::$libPathes[$libPath] = $recursive;
        self::$initDone            = false;
    }

    /**
     * returns the file where the given classes is stored in
     *
     * @param   string  $fqClassName  the full qualified class name
     * @return  string
     */
    public static function getFileForClass($fqClassName)
    {
        if (false === self::$initDone) {
            self::init();
        }
        
        if (isset(self::$classes[$fqClassName]) === true) {
            return self::$classes[$fqClassName];
        }

        return null;
    }

    /**
     * returns the uri for the given class
     *
     * @param   string  $fqClassName  the full qualified class name
     * @return  string
     */
    public static function getUriForClass($fqClassName)
    {
        if (false === self::$initDone) {
            self::init();
        }
        
        if (isset(self::$classes[$fqClassName]) === true) {
            return 'star://' . self::$classes[$fqClassName] . '?' . $fqClassName;
        }

        return null;
    }

    /**
     * returns all uris for a given resource
     *
     * @param   string  $fileName  file name of resource
     * @return  array
     */
    public static function getUrisForResource($resource)
    {
        if (false === self::$initDone) {
            self::init();
        }
        
        $uris = array();
        foreach (self::$files as $file => $contents) {
            foreach ($contents as $content) {
                if ($content === $resource) {
                    $uris[] = 'star://' . $file . '?' . $resource;
                    continue 2;
                }
            }
        }

        return $uris;
    }

    /**
     * returns a list of all classes within given file
     *
     * @param   string  $file  name of file
     * @return  array
     */
    public static function getClassNamesFromFile($file)
    {
        if (false === self::$initDone) {
            self::init();
        }
        
        if (isset(self::$files[$file]) === true) {
            return self::$files[$file];
        }

        return array();
    }

    /**
     * returns a list of all classes
     *
     * @return  string
     */
    public static function getClasses()
    {
        return array_keys(self::$classes);
    }

    /**
     * initialize the class registry
     */
    protected static function init()
    {
        if (true === self::$initDone) {
            return;
        }

        if (count(self::$libPathes) == 0) {
            self::$libPathes[dirname(__FILE__)] = true;
        }

        foreach (self::$libPathes as $libPath => $recursive) {
            if (file_exists($libPath . '/.cache') === true) {
                $cache = unserialize(file_get_contents($libPath . '/.cache'));
                self::$files    = array_merge(self::$files, $cache['files']);
                self::$classes  = array_merge(self::$classes, $cache['classes']);
                self::$initDone = true;
                continue;
            }

            if (true === $recursive) {
                $dirIt = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($libPath));
            } else {
                $dirIt = new DirectoryIterator($libPath);
            }

            $cache['files']   = array();
            $cache['classes'] = array();
            foreach ($dirIt as $file) {
                if ($file->isFile() === false || substr($file->getPathname(), -14) === 'starReader.php' || (substr($file->getPathname(), -5) !== '.star' && substr($file->getPathname(), -4) !== '.php')) {
                    continue;
                }

                $archiveData = StarStreamWrapper::acquire($file->getPathname());
                if (empty($archiveData) == true) {
                    continue;
                }

                $classes = array_keys($archiveData['index']);
                self::$files[$file->getPathname()]    = $classes;
                $cache['files'][$file->getPathname()] = $classes;

                foreach (array_keys($archiveData['index']) as $fqClassName) {
                    self::$classes[$fqClassName]    = $file->getPathname();
                    $cache['classes'][$fqClassName] = $file->getPathname();
                }
            }

            $cacheFile = $libPath . '/.cache';
            if (is_writable($libPath) === false && is_writable($cacheFile) === false) {
                throw new StarException("Unable to write starRegistry cache file to {$cacheFile}.");
            }
            
            file_put_contents($cacheFile, serialize($cache));
            self::$initDone = true;
        }
    }
}
?><?php StarStreamWrapper::register(); ?>