<?php

require '../bootstrap-stubbles.php';

stubClassLoader::load('net::stubbles::reflection::reflection');

class Base extends stubBaseObject {


    public function display($string) {
        $procClazz = $this->getClass()->getAnnotation('Proc')->getValue();


        $clazz = new stubReflectionClass('BoldProcessor');
        var_dump($clazz);
        var_dump($procClazz);
        $proc = $clazz->newInstance();
        $proc = $procClazz->newInstance();

        echo $proc->process($string);
    }
}

/**
 * Enter description here...
 *
 * @Proc(BoldProcessor.class);
 */
class Foo extends Base {
}

class BoldProcessor {

    public function __construct() {
    }

    public function process($string) {
        return "<b>$string</b>";
    }
}

class stubProcAnnotation extends stubAbstractAnnotation implements stubAnnotation {

    private $clazz;

    public function setValue($clazz) {
        echo "<pre>";
        var_dump($clazz);
        $this->clazz = $clazz;
    }

    public function getValue() {
        return $this->clazz;
    }

    public function getAnnotationTarget() {
        return stubAnnotation::TARGET_CLASS;
    }
}

$clazz = new stubReflectionClass('BoldProcessor');
$clazz = unserialize(serialize($clazz));
$clazz->newInstance();

exit();

$foo = new Foo();
$foo->display('Hallo');
?>