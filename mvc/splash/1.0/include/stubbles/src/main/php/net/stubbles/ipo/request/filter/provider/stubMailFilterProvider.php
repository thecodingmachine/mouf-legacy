<?php
/**
 * Filter provider for the mail filter.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueErrorFactory',
                      'net::stubbles::ipo::request::filter::provider::stubFilterProvider'
);
/**
 * Filter provider for the mail filter.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_provider
 */
class stubMailFilterProvider extends stubBaseObject implements stubFilterProvider
{
    /**
     * checks whether the filter provider is responsible for given filter
     *
     * @param   string  $shortcut
     * @return  bool
     */
    public function isResponsible($shortcut)
    {
        return 'mail' === $shortcut;
    }

    /**
     * returns a filter instance
     *
     * The mail filter requires a stubRequestValueErrorFactory as constructor
     * argument. The $args array must have such an instance under its key 0
     * despite the fact that the $args param is declared as optional, which is
     * due to method declaration inheritence. If no stubRequestValueErrorFactory
     * is given under the named key a stubIllegalArgumentException will be
     * thrown.
     *
     * @param   array       $args  optional  constructor arguments
     * @return  stubFilter
     * @throws  stubIllegalArgumentException
     */
    public function getFilter(array $args = null)
    {
        if (isset($args[0]) === false || ($args[0] instanceof stubRequestValueErrorFactory) === false) {
            throw new stubIllegalArgumentException('Requested filter requires an instance of net::stubbles::ipo::request::stubRequestValueErrorFactory.');
        }
        
        stubClassLoader::load('net::stubbles::ipo::request::filter::stubMailFilter');
        return new stubMailFilter($args[0], $this->createMailValidator());
    }

    /**
     * helper method to create the mail validator instance
     *
     * @return  stubMailValidator
     */
    protected function createMailValidator()
    {
        static $mailValidator;
        if (null === $mailValidator) {
            stubClassLoader::load('net::stubbles::ipo::request::validator::stubMailValidator');
            $mailValidator = new stubMailValidator();
        }
        
        return $mailValidator;
    }
}
?>