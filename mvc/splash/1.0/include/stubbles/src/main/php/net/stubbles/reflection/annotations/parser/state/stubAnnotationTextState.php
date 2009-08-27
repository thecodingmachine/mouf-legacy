<?php
/**
 * Text within a docblock state.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
stubClassLoader::load('net::stubbles::reflection::annotations::parser::state::stubAnnotationAbstractState',
                      'net::stubbles::reflection::annotations::parser::state::stubAnnotationState'
);
/**
 * Text within a docblock state.
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
class stubAnnotationTextState extends stubAnnotationAbstractState implements stubAnnotationState
{
    /**
     * processes a token
     *
     * @param   string  $token
     * @throws  ReflectionException
     */
    public function process($token)
    {
        if ("\n" === $token) {
            $this->parser->changeState(stubAnnotationState::DOCBLOCK);
        }
    }
}
?>