<?php
/**
 * Parser is inside the annotation
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
stubClassLoader::load('net::stubbles::reflection::annotations::parser::state::stubAnnotationAbstractState',
                      'net::stubbles::reflection::annotations::parser::state::stubAnnotationState'
);
/**
 * Parser is inside the annotation
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
class stubAnnotationAnnotationState extends stubAnnotationAbstractState implements stubAnnotationState
{
    /**
     * processes a token
     *
     * @param  string  $token
     */
    public function process($token)
    {
        if ("\n" === $token) {
            $this->parser->changeState(stubAnnotationState::DOCBLOCK);
            return;
        }
        
        if ('{' === $token) {
            $this->parser->changeState(stubAnnotationState::ARGUMENT);
            return;
        }
        
        if ('[' === $token) {
            $this->parser->changeState(stubAnnotationState::ANNOTATION_TYPE);
            return;
        }
        
        if ('(' === $token) {
            $this->parser->changeState(stubAnnotationState::PARAMS);
            return;
        }
    }
}
?>