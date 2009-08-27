<?php
/**
 * Class for file based output streams.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 */
stubClassLoader::load('net::stubbles::streams::stubResourceOutputStream',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::lang::exceptions::stubIOException'
);
/**
 * Class for file based output streams.
 *
 * @package     stubbles
 * @subpackage  streams
 */
class stubFileOutputStream extends stubResourceOutputStream
{
    /**
     * constructor
     *
     * @param   string|resource  $file
     * @param   string           $mode  option  opening mode if $file is a filename
     * @throws  stubIllegalArgumentException
     * @throws  stubIOException
     */
    public function __construct($file, $mode = 'wb')
    {
        if (is_string($file) === true) {
            $fp = @fopen($file, $mode);
            if (false === $fp) {
                throw new stubIOException('Can not open file ' . $file . ' with mode ' . $mode);
            }
        } elseif (is_resource($file) === true && get_resource_type($file) === 'stream') {
            $fp = $file;
        } else {
            throw new stubIllegalArgumentException('File must either be a filename or an already opened file/stream resource.');
        }
        
        $this->setHandle($fp);
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->close();
    }
}
?>