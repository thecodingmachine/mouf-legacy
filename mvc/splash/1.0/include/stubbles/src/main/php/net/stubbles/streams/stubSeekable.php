<?php
/**
 * A seekable stream may be altered in its position to read data.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 * @version     $Id: stubSeekable.php 1790 2008-08-30 19:19:56Z mikey $
 */
/**
 * A seekable stream may be altered in its position to read data.
 *
 * @package     stubbles
 * @subpackage  streams
 */
interface stubSeekable extends stubObject
{
    /**
     * set position equal to offset  bytes
     */
    const SET     = SEEK_SET;
    /**
     * set position to current location plus offset
     */
    const CURRENT = SEEK_CUR;
    /**
     * set position to end-of-file plus offset
     */
    const END     = SEEK_END;

    /**
     * seek to given offset
     *
     * @param  int  $offset
     * @param  int  $whence  one of stubSeekable::SET, stubSeekable::CURRENT or stubSeekable::END
     */
    public function seek($offset, $whence = stubSeekable::SET);

    /**
     * return current position
     *
     * @return  int
     */
    public function tell();
}
?>