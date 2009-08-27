<?php
/**
 * Example for the XMLSerializer package
 *
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @link    http://www.stubbles.net/wiki/Docs/XMLSerializer
 * @uses    stubXMLSerializer
 */
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::xml::serializer::stubXMLSerializer',
                      'net::stubbles::xml::stubXMLStreamWriterFactory');

/**
 * A Superhero team
 *
 * @XMLTag(tagName="Team")
 */
class Team {
    /**
     * Name of the team
     *
     * @var string
     */
    private $name;

    /**
     * Members of the team
     *
     * @var array
     * @XMLTag(tagName="members", elementTagName="member")
     */
    public $members = array();

    /**
     * Create a new team
     *
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Get the name of the team
     *
     * @return string
     * @XMLAttribute(attributeName="name")
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add a new member to the team
     *
     * @param Hero $hero
     */
    public function addMember(Hero $hero) {
        $this->members[] = $hero;
    }
}

/**
 * Hero object
 */
class Hero {
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
}

class Bootstrap
{
    public static function main()
    {
        $serializer = new stubXMLSerializer();
        $writer = stubXMLStreamWriterFactory::createAsAvailable();

        $jla = new Team('Justice League of America');
        $jla->addMember(new Hero('Superman'));
        $jla->addMember(new Hero('Batman'));
        $jla->addMember(new Hero('Wonder Woman'));
        $serializer->serialize($jla, $writer);

        header('Content-Type: text/xml');
        echo $writer->asXML();
    }
}
Bootstrap::main();
?>