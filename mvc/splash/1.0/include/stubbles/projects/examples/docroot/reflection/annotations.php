<?php
/**
 * Example that shows how to define annotations
 *
 * @author Stephan Schmidt <schst@stubbles.net>
 */

/**
 * Load stubbles
 */
require '../bootstrap-stubbles.php';

// load the complete reflection package
stubClassLoader::load('net::stubbles::reflection::reflection');

/**
 * Simple annotation with two properties
 *
 */
class stubFooAnnotation extends stubAbstractAnnotation implements stubAnnotation {
    public $bar;
    public $baz;

    /**
     * Set the bar property
     *
     * @param mixed $bar
     */
    public function setBar($bar) {
        $this->bar = $bar;
    }

    /**
     * Set the baz property
     *
     * @param mixed $bar
     */
    public function setBaz($baz) {
        $this->baz = $baz;
    }

    /**
     * Get the bar property
     *
     * @return mixed
     */
    public function getBar() {
        return $this->bar;
    }

    /**
     * Get the baz property
     *
     * @return mixed
     */
    public function getBaz() {
        return $this->baz;
    }

    /**
     * Define the possible targets of this annotation
     *
     * @return int
     */
    public function getAnnotationTarget() {
        return stubAnnotation::TARGET_CLASS | stubAnnotation::TARGET_PROPERTY;
    }
}

/**
 * Class without the annotation
 */
class NoAnnotation {
}

/**
 * Class that makes use of the Foo annotation.
 *
 * @Foo(bar='This is bar', baz=42)
 */
class MyClass {
}

define('MY_CONSTANT', 'This is a constant.');

/**
 * Class that makes use of the Foo annotation and a constant.
 *
 * @Foo(bar=MY_CONSTANT, baz=false)
 */
class ConstantAnnotation {
}

class ClazzWithConstant
{
    const FOO = 'This is a class constant.';
}

/**
 * Class that makes use of the Foo annotation and a class constant.
 *
 * @Foo(bar=ClazzWithConstant::FOO, baz=false)
 */
class AnotherConstantAnnotation {
}

/**
 * Class that makes use of the Foo annotation and a constant.
 *
 * @Foo(bar=MyClass.class, baz=true)
 */
class MyClass2 {
}

print "<pre>";
checkForFoo('NoAnnotation');
checkForFoo('MyClass');
checkForFoo('ConstantAnnotation');
checkForFoo('AnotherConstantAnnotation');
checkForFoo('MyClass2');
print "</pre>";

function checkForFoo($className) {
    echo "Introspecting {$className}\n";
    // extract the annotation
    $clazz = new stubReflectionClass($className);
    if ($clazz->hasAnnotation('Foo')) {
        $foo = $clazz->getAnnotation('Foo');

        print "Annotation foo is present:\n";
        printf("bar : %s\n", varToString($foo->getBar()));
        printf("baz : %s\n", varToString($foo->getBaz(), true));
    } else {
        print "Annotation foo is *not* present:\n";
    }
    print "\n";
}

function varToString($var) {
    if (is_object($var)) {
        return $var->__toString();
    }
    return var_export($var, true);
}
?>