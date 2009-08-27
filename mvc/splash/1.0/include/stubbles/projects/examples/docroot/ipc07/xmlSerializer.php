<?php
/**
 * Example for the XMLSerializer package
 *
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @see     http://www.stubbles.net/wiki/Docs/XMLSerializer
 * @uses    stubXMLSerializer
 */
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::xml::serializer::stubXMLSerializer',
                      'net::stubbles::xml::stubXMLStreamWriterFactory');

class PersonPlain {
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

    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getAge() {
        return $this->age;
    }
    public function getRole() {
        return $this->role;
    }
}


/**
 * A person
 *
 * @XMLTag(tagName="user")
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
     * @XMLAttribute(attributeName="userId")
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Get the name
     *
     * @XMLTag(tagName="realname")
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    /**
     * Get the age
     *
     * @XMLIgnore
     * @return int
     */
    public function getAge() {
        return $this->age;
    }
    /**
     * Get the role
     *
     * @XMLTag(tagName="role")
     * @return string
     */
    public function getRole() {
        return $this->role;
    }
}

$serializer = new stubXMLSerializer();
$writer = stubXMLStreamWriterFactory::createAsAvailable();

$user = new Person(1, 'schst', 33, 'admin');
$serializer->serialize($user, $writer);

echo "<pre>";

echo htmlentities($writer->asXML());

echo "\n\n";
echo "Using the PHP reflection\n";
$class = new ReflectionClass('Person');
echo $class->getName() . "\n";
foreach ($class->getMethods() as $method) {
    echo " -> " . $method->getName()  . "\n";
}

echo "\n";
echo "Using the Stubbles reflection\n";
$class = new stubReflectionClass('Person');
if ($class->hasAnnotation('XMLTag')) {
    $xmlTag = $class->getAnnotation('XMLTag');
    print_r($xmlTag);
}
foreach ($class->getMethods() as $method) {
    if ($method->hasAnnotation('XMLAttribute')) {
        $xmlAttribute = $method->getAnnotation('XMLAttribute');
        echo $method->getName() . ' -> ';
        echo $xmlAttribute->getAttributeName() . "\n";
    }
}
echo "</pre>";
?>