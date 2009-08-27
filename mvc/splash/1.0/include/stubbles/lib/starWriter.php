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
?><?php StarStreamWrapper::register(); ?><?php
require 'star://' . __FILE__ . '?org::stubbles::star::StarFile';
require 'star://' . __FILE__ . '?org::stubbles::star::StarWriter';
require 'star://' . __FILE__ . '?org::stubbles::star::StarArchive';
require 'star://' . __FILE__ . '?org::stubbles::star::StarConsole';
?><?php __halt_compiler();star4       20080706203732                                                                                                                                                                                                                                     org::stubbles::star::StarFile                                                                                                                                                                                                           2775    13865           org::stubbles::star::StarWriter                                                                                                                                                                                                         1446    16640           org::stubbles::star::StarArchive                                                                                                                                                                                                        7099    18086           org::stubbles::star::StarConsole                                                                                                                                                                                                        7958    25185           <?php
/**
 * Class for very simple file handling.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * Class for very simple file handling.
 * 
 * @package  star
 */
class StarFile
{
    /**
     * name of the file
     *
     * @var  string
     */
    protected $name;
    /**
     * the path that gets removed if file is added to a star archive
     *
     * @var  string
     * @see  getPathWithBaseRemoved()
     */
    protected $removePath = null;
    
    /**
     * constructor
     *
     * @param  string  $name        name of the file
     * @param  string  $removePath  optional  part of the directory name that should be removed
     */
    public function __construct($name, $removePath = null)
    {
        $this->name       = $name;
        if (null !== $removePath) {
            $this->removePath = realpath($removePath) . DIRECTORY_SEPARATOR;
        }
    }
    
    /**
     * returns the name of the file
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * returns the basename of the file
     *
     * @return  string
     */
    public function getBaseName()
    {
        return basename($this->name);
    }
    
    /**
     * get the extension of the file
     *
     * @return  string
     */
    public function getExtension()
    {
        $pathinfo = pathinfo($this->name);
        if (isset($pathinfo['extension']) == true) {
            return $pathinfo['extension'];
        }
        
        return null;
    }
    
    /**
     * set the extension
     *
     * @param  string  $extension
     */
    public function setExtension($extension)
    {
        $pathinfo   = pathinfo($this->name);
        $pathinfo['basename'] = substr($pathinfo['basename'], 0, ((strlen($pathinfo['extension']) + 1) * -1));
        $this->name = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['basename'] . '.' . $extension;
    }
    
    /**
     * returns the path of the file
     *
     * @return  string
     */
    public function getPath()
    {
        return dirname($this->name);
    }
    
    /**
     * returns the path of the file but with the base removed
     *
     * @var  string
     */
    public function getPathWithBaseRemoved()
    {
        if (null !== $this->removePath) {
            return str_replace(DIRECTORY_SEPARATOR, '/', str_replace($this->removePath, '', $this->getPath()));
        }
        
        return $this->getPath();
    }

    /**
     * returns the contents of the file
     *
     * @return  string
     */
    public function getContents()
    {
        if (file_exists($this->name) == true) {
            return file_get_contents($this->name);
        }
        
        return '';
    }
}
?><?php
/**
 * Class to write data into files.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * Class to write data into files.
 * 
 * @package  star
 */
class StarWriter extends StarFile
{
    /**
     * pointer to file to write
     *
     * @var  resource
     */
    protected $fp;
    
    /**
     * open the file
     * 
     * Warning: Opening existing files will truncate them!
     * 
     * @throws  StarException
     */
    public function open()
    {
        $fp = fopen($this->name, 'wb+');
        if (false === $fp) {
            throw new StarException('Could not open file ' . $this->name);
        }
        
        $this->fp = $fp;
    }
    
    /**
     * write data to star file
     *
     * @param   string  $data  data to write
     * @return  int     amount of written bytes
     * @throws  StarException
     */
    public function write($data)
    {
        if (false === ($result = fwrite($this->fp, $data))) {
            throw new StarException('Cannot write ' . strlen($data) . ' bytes to ' . $this->name);
        }
        
        return $result;
    }
    
    /**
     * close the file
     *
     * @return  bool
     * @throws  StarException
     */
    public function close()
    {
        if (false === fclose($this->fp)) {
            throw new StarException('Cannot close file ' . $this->name);
        }
      
        $this->fp = null;
        return true;
    }
}
?><?php
/**
 * Class for creating star archives.
 * 
 * This class contains code from skeleton/lang/archive/Archive.class.php
 * of the XP-framework, written by Timm Friebe and Alex Kiesel.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * Class for creating star archives.
 * 
 * This class contains code from skeleton/lang/archive/Archive.class.php
 * of the XP-framework, written by Timm Friebe and Alex Kiesel.
 * 
 * @package  star
 */
class StarArchive
{
    /**
     * the file to write star data into
     *
     * @var  StarWriter
     */
    protected $writer;
    /**
     * preface for star archive
     *
     * @var  string
     */
    protected $preface;
    /**
     * switch whether to prepend the StarStreamWrapper class in preface or not
     *
     * @var  bool
     */
    protected $prependStreamWrapper = true;
    /**
     * the index of files to put into the star
     *
     * @var  array
     */
    protected $index                = array();
    /**
     * metadata of star archive: could be version, packages, etc.
     *
     * @var  array<string,string>
     */
    protected $metadata             = array();
    /**
     * star archive version to create
     *
     * @var  int
     */
    protected $version                  = 2;
    
    /**
     * constructor
     *
     * @param  StarWriter  $writer  writer to use
     */
    public function __construct(StarWriter $writer, $version = 2)
    {
        $this->writer = $writer;
        $this->writer->setExtension('star');
        $this->version = $version;
    }
    
    /**
     * add a file to the star archive
     *
     * @param  StarFile  $file  the file to add
     * @param  string    $id    id for the file
     */
    public function add(StarFile $file, $id)
    {
        $data             = $file->getContents();
        $this->index[$id] = array('basename' => $file->getBaseName(),
                                  'path'     => $file->getPathWithBaseRemoved(),
                                  'datasize' => strlen($data),
                                  'offset'   => -1,
                                  'payload'  => $data
                            );
    }
    
    /**
     * adds meta data to the star archive
     *
     * @param  string  $name
     * @param  string  $value
     */
    public function addMetaData($name, $value)
    {
        $this->metadata[$name] = $value;
    }
    
    /**
     * set the preface
     *
     * @param  string  $preface
     * @param  bool    $prependStreamWrapper  optional
     */
    public function setPreface($preface, $prependStreamWrapper = true)
    {
        $this->preface = $preface;
        if (strlen($this->preface) > 0 && $this->writer->getExtension() != 'php') {
            $this->writer->setExtension('php');
        } elseif (strlen($this->preface) == 0 && $this->writer->getExtension() != 'star') {
            $this->writer->setExtension('star');
        }
        
        $this->prependStreamWrapper = $prependStreamWrapper;
    }
    
    /**
     * creates the star archive
     *
     * @param   bool  $selfRunning  optional  set to true if file should be self-running
     * @throws  StarException
     */
    public function create()
    {
        $this->writer->open();
        if (strlen($this->preface) > 0) {
            $preFace = '';
            if (true === $this->prependStreamWrapper) {
                $dirname = str_replace('star://', '', dirname(__FILE__));
                if (file_exists($dirname . '/starReader.php') === true) {
                    $preFace .= file_get_contents($dirname . '/starReader.php');
                } else {
                    $preFace .= file_get_contents($dirname . '/StarStreamWrapper.php');
                    $preFace .= file_get_contents($dirname . '/StarException.php');
                    $preFace .= file_get_contents($dirname . '/StarClassRegistry.php');
                    $preFace .= "<?php StarStreamWrapper::register(); ?>";
                }
            }
            $preFace .= trim($this->preface) . "<?php __halt_compiler();";
            $this->writer->write($preFace);
            $offset = strlen($preFace);
        } else {
            $offset = 0;
        }
        
        $ids = array_keys($this->index);
        $this->writer->write(pack('a4c1a8a14a229',
                                  'star',
                                  $this->version,
                                  (string) count($ids),
                                  date('YmdHis'),
                                  "\0"
                             )
        );
        
        // write index
        foreach ($ids as $id) {
            $this->writer->write($this->getHeader($id, $offset));
            $offset += $this->index[$id]['datasize'];
        }
        
        // write index
        foreach ($ids as $id) {
            $this->writer->write($this->index[$id]['payload']);
        }
        
        if (count($this->metadata) > 0) {
            $this->writer->write("\n");
            foreach ($this->metadata as $name => $value) {
                $this->writer->write($name . ' => ' . $value . "\n");
            }
        }
        
        $this->writer->close();
    }

    /**
     * returns the header of entries depending on requested version
     *
     * @param   string  $id      id of entry to create header data for
     * @param   int     $offset  offset where entry is located in file
     * @return  string
     */
    protected function getHeader($id, $offset)
    {
        switch ($this->version) {
            case 1:
                $method = 'getHeaderForVersion1';
                break;
            
            case 2:
            default:
                $method = 'getHeaderForVersion2';
        }
        
        return $this->$method($id, $offset);
    }

    /**
     * returns the header of entries for star files version 1
     *
     * @param   string  $id      id of entry to create header data for
     * @param   int     $offset  offset where entry is located in file
     * @return  string
     */
    protected function getHeaderForVersion1($id, $offset)
    {
        return pack('a80a72a80a8a8a8', $id,
                                       $this->index[$id]['basename'],
                                       $this->index[$id]['path'],
                                       (string) $this->index[$id]['datasize'],
                                       (string) $offset,
                                       "\0"
               );
    }

    /**
     * returns the header of entries for star files version 2
     *
     * @param   string  $id      id of entry to create header data for
     * @param   int     $offset  offset where entry is located in file
     * @return  string
     */
    protected function getHeaderForVersion2($id, $offset)
    {
        return pack('a232a8a8a8', $id,
                                  (string) $this->index[$id]['datasize'],
                                  (string) $offset,
                                  "\0"
               );
    }
}
?><?php
/**
 * Class for creating star archives via console.
 * 
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  star
 */
/**
 * used to read command line arguments
 * 
 * @see  http://pear.php.net/package/Console_Getargs/
 */
require_once 'Console/Getargs.php';
/**
 * Class for creating star archives via console.
 * 
 * @package  star
 */
class StarConsole
{
    /**
     * configuration for Console_Getargs
     *
     * @var  array
     */
    private static $config = array('target'     => array('short'   => 't',
                                                         'min'     => 0,
                                                         'max'     => 1,
                                                         'desc'    => 'Name of the archive to create.',
                                                         'default' => ''
                                                   ),
                                   'ini'        => array('short'   => 'i',
                                                         'min'     => 0,
                                                         'max'     => 1,
                                                         'desc'    => 'Name of the ini file to use.',
                                                         'default' => ''
                                                   ),
                                   'removePath' => array('short'   => 'r',
                                                         'min'     => 0,
                                                         'max'     => 1,
                                                         'desc'    => 'Path to remove from logfiles.',
                                                         'default' => ''
                                                   ),
                                   'star'       => array('short'   => 's',
                                                         'min'     => 0,
                                                         'max'     => 1,
                                                         'desc'    => 'Star version to use: 1 or 2.',
                                                         'default' => ''
                                                   ),
                                   'verbose'    => array('short'   => 'v',
                                                         'min'     => 0,
                                                         'max'     => 0,
                                                         'desc'    => 'Be verbose.'
                                                   )
                              );
    /**
     * access to arguments
     *
     * @var  Console_Getargs
     */
    private $args;
    /**
     * ini configuration file to use
     *
     * @var  string
     */
    private $iniFile;
    /**
     * target to compile to
     *
     * @var  string
     */
    private $target;
    /**
     * star version to use
     *
     * @var  int
     */
    private $starVersion = 2;
    
    /**
     * constructor
     *
     * @throws  Exception
     */
    private function __construct()
    {
        $args = Console_Getargs::factory(self::$config);

        if (PEAR::isError($args)) {
            throw new StarException($args->getMessage(), $args->getCode());
        }
        
        $this->args = $args;
    }
    
    /**
     * print out a help screen
     */
    private static function help(StarException $sce)
    {
        $header = "star stubbles archive creator 1.0\n".
                  'Usage: ' . basename($_SERVER['SCRIPT_NAME']) . " [options]\n\n";
        if ($sce->getCode() === CONSOLE_GETARGS_ERROR_USER) {
            echo Console_Getargs::getHelp(self::$config, $header, $sce->getMessage())."\n";
        } else if ($sce->getCode() === CONSOLE_GETARGS_HELP) {
            echo Console_Getargs::getHelp(self::$config, $header)."\n";
        }
    }
    
    /**
     * the main method
     */
    public static function main()
    {
        $startTime = microtime(true);
        try {
            $starConsole = new self();
        } catch (StarException $sce) {
            self::help($sce);
            exit(1);
        }
        
        try {
            $starConsole->readArgs();
        } catch (StarException $sce) {
            self::help($sce);
            exit(1);
        }
        
        $starConsole->process();
        $endTime = microtime(true);
        echo 'Done in ' . ($endTime - $startTime) . " seconds\n";
    }
    
    /**
     * read arguments from command line
     */
    private function readArgs()
    {
        if ($this->args->isDefined('ini') == false) {
            if (file_exists('./compile.ini') == false) {
                throw new StarException('Can not compile: no ini set and no default compile.ini found.', CONSOLE_GETARGS_ERROR_USER);
            }
            
            $this->iniFile = './compile.ini';
        } else {
            if (file_exists($this->args->getValue('ini')) == false) {
                throw new StarException('Can not compile: ' . $this->args->getValue('ini') . ' not found.', CONSOLE_GETARGS_ERROR_USER);
            }
            
            $this->iniFile = $this->args->getValue('ini');
        }
        
        if ($this->args->isDefined('target') == true) {
            $this->target = $this->args->getValue('target');
        }
        
        if ($this->args->isDefined('star') == true) {
            $this->starVersion = $this->args->getValue('star');
        }
    }
    
    /**
     * do the real action: execute phing for each day and shop
     */
    private function process()
    {
        $this->verbose('Reading ini file ' . $this->iniFile . "\n");
        $conf = parse_ini_file($this->iniFile, true);
        if (isset($conf['MAIN']) == false || (isset($conf['MAIN']['target']) == false && null == $this->target)) {
            echo 'No target found in ' . $this->iniFile . ". Please enter a target via option -t.\n";
            exit(1);
        }
        if (isset($conf['INCLUDES']) == false) {
            echo 'No includes found in ' . $this->iniFile . ".\n";
            exit(1);
        }
        
        $target = ((null == $this->target) ? ($conf['MAIN']['target']) : ($this->target));
        $this->verbose('Writing star data to ' . $target . "\n");
        $starArchive = new StarArchive(new StarWriter($target), $this->starVersion);
        $removePath  = null;
        if ($this->args->isDefined('removePath') == true) {
            $removePath = $this->args->getValue('removePath');
        }
        foreach ($conf['INCLUDES'] as $id => $fileName) {
            $this->verbose('Include ' . $fileName . ' with id ' . $id . "\n");
            $starArchive->add(new StarFile($fileName, $removePath), $id);
        }
        
        if (isset($conf['PREFACE']) == true && is_array($conf['PREFACE']) == true) {
            $prefaceContents = '';
            foreach ($conf['PREFACE'] as $preface) {
                if (file_exists($preface) == false) {
                    echo 'Preface file ' . $preface . " does not exist.\n";
                }
                
                $this->verbose('Preface ' . $preface . "\n");
                $prefaceContents .= file_get_contents($preface);
            }
            
            $starArchive->setPreface($prefaceContents);
        }
        
        if (isset($conf['META-INF']) == true && is_array($conf['META-INF']) == true) {
            foreach ($conf['META-INF'] as $name => $value) {
                $starArchive->addMetaData($name, $value);
            }
        }
        
        $this->verbose("Creating star\n");
        $starArchive->create();
    }
    
    /**
     * display message to default output
     *
     * @param unknown_type $message
     */
    private function verbose($message)
    {
        if ($this->args->isDefined('verbose') == true) {
            echo $message;
        }
    }
}
?>
title => Stubbles Archive Writer
package => org::stubbles::star
version => 0.8.0
author => Stubbles Development Team <http://stubbles.net>
copyright = © 2007-2008 Stubbles Development Team
