<?php
/**
 * Interface for matching methods.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_matcher
 */
/**
 * Interface for matching methods.
 * 
 * @package     stubbles
 * @subpackage  reflection_matcher
 */
interface stubMethodMatcher extends stubObject
{
    /**
     * checks whether the matcher is satisfied with the given method
     *
     * @param   ReflectionMethod  $method
     * @return  bool
     */
    public function matchesMethod(ReflectionMethod $method);

    /**
     * checks whether the matcher is satisfied with the given method
     *
     * @param   stubReflectionMethod  $method
     * @return  bool
     */
    public function matchesAnnotatableMethod(stubReflectionMethod $method);
}
?>