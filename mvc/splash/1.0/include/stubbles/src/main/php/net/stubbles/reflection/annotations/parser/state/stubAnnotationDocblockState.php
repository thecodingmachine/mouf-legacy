<?php
/**
 * Docblock state
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
 * Docblock state
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
class stubAnnotationDocblockState extends stubAnnotationAbstractState implements stubAnnotationState
{
    /**
     * processes a token
     *
     * @param   string  $token
     * @throws  ReflectionException
     */
    public function process($token)
    {
        if ('@' === $token) {
            $this->parser->changeState(stubAnnotationState::ANNOTATION_NAME);
            return;
        }
        
        // all character except * and space and line breaks
        if (' ' !== $token && '*' !== $token && "\n" !== $token && "\t" !== $token) {
            $this->parser->changeState(stubAnnotationState::TEXT);
        }
    }
}
?>