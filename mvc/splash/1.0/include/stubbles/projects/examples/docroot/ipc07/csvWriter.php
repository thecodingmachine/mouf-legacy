<?php
/**
 * Example for the XMLSerializer package
 *
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @see     http://www.stubbles.net/wiki/Docs/XMLSerializer
 * @uses    stubXMLSerializer
 */
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::reflection::reflection');

class stubCSVAnnotation extends stubAbstractAnnotation implements stubAnnotation {
    public $file;
    protected $delimeter;
    public function setDelimeter($delim) {
        $this->delimeter = $delim;
    }
    public function getDelimeter() {
        return $this->delimeter;
    }
    public function getAnnotationTarget() {
        return stubAnnotation::TARGET_CLASS;
    }
}

class stubCSVFieldAnnotation extends stubAbstractAnnotation implements stubAnnotation {
    public function getAnnotationTarget() {
        return stubAnnotation::TARGET_METHOD | stubAnnotation::TARGET_PROPERTY;
    }
}


/**
 * A person
 *
 * @CSV(file="../../cache/users.csv",
 *      delimeter=";")
 */
class Person {
    protected $id;
    protected $name;
    protected $age;
    protected $role;

    public function __construct($id, $name, $age, $role = 'user') {
        $this->id   = $id;
        $this->name = $name;
        $this->age  = $age;
        $this->role = $role;
    }

    /**
     * Get the id
     *
     * @CSVField
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Get the name
     *
     * @CSVField
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    /**
     * Get the age
     *
     * @CSVField
     * @return int
     */
    public function getAge() {
        return $this->age;
    }
    /**
     * Get the role
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }
}

$user = new Person(1, 'schst', 33, 'admin');

class CSVWriter {
    public static function write($obj) {
        $class = new stubReflectionClass(get_class($obj));
        if (!$class->hasAnnotation('CSV')) {
            throw new UnsupportedOperationException('Cannot write object to file.');
        }
        $csv = $class->getAnnotation('CSV');

        $fp = fopen($csv->file, 'a');
        $fields = array();
        foreach ($class->getMethods() as $method) {
            if (!$method->hasAnnotation('CSVField')) {
                continue;
            }
            $fields[] = $method->invoke($obj);
        }
        $line = implode($csv->getDelimeter(), $fields);
        fwrite($fp, $line . "\n");
        fclose($fp);
    }
}

CSVWriter::write($user);

echo "<pre>";
echo file_get_contents('../../cache/users.csv');
echo "</pre>";
?>