<?php

class stubActionAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD;
    }
}
?>
