<?php
/**
 * Interface for factories creating stubRequestValueErrors.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueError');
/**
 * Interface for factories creating stubRequestValueErrors.
 *
 * @package     stubbles
 * @subpackage  ipo_request
 */
interface stubRequestValueErrorFactory
{
    /**
     * creates the  RequestValueError with the id from the given source
     *
     * @param   string                 $id      id of RequestValueError to create
     * @return  stubRequestValueError
     */
    public function create($id);
}
?>