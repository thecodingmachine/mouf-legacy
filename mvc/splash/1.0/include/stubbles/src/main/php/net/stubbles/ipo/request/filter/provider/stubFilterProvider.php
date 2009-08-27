<?php
/**
 * Interface for filter providers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
/**
 * Interface for filter providers.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
interface stubFilterProvider extends stubObject
{
    /**
     * checks whether the filter provider is responsible for given filter
     *
     * @param   string  $shortcut
     * @return  bool
     */
    public function isResponsible($shortcut);

    /**
     * returns a filter instance
     *
     * @param   array       $args  optional  constructor arguments
     * @return  stubFilter
     */
    public function getFilter(array $args = null);
}
?>