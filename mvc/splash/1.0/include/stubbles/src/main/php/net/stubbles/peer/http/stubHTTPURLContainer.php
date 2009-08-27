<?php
/**
 * Interface for URLs of scheme hypertext transfer protocol.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_http
 * @version     $Id: stubHTTPURLContainer.php 1934 2008-11-28 14:05:16Z mikey $
 */
stubClassLoader::load('net::stubbles::peer::stubHeaderList',
                      'net::stubbles::peer::stubURLContainer'
);
/**
 * Interface for URLs of scheme hypertext transfer protocol.
 *
 * @package     stubbles
 * @subpackage  peer_http
 */
interface stubHTTPURLContainer extends stubURLContainer
{
    /**
     * creates a stubHTTPConnection for this URL
     *
     * @param   stubHeaderList      $headers  optional  list of headers to be used
     * @return  stubHTTPConnection
     */
    public function connect(stubHeaderList $headers = null);
}
?>
