<?php
/**
 * Datespan that represents yesterday.
 *
 * @author      Ingo Sobolewski <ingo.sobolewski@1und1.de>
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanDay');
/**
 * Datespan that represents yesterday.
 *
 * @package     stubbles
 * @subpackage  util_datespan
 */
class stubDateSpanYesterday extends stubDateSpanDay
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('yesterday');
    }
}
?>