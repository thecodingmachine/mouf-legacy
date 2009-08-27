<?php
/**
 * Interface for rss feeds to be accessed via the rss processor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss
 */
stubClassLoader::load('net::stubbles::xml::rss::stubRSSFeedGenerator');
/**
 * Interface for rss feeds to be accessed via the rss processor.
 *
 * @package     stubbles
 * @subpackage  xml_rss
 */
interface stubRSSFeed extends stubObject
{
    /**
     * creates the rss feed
     *
     * @return  stubRSSFeedGenerator
     */
    public function create();
}
?>