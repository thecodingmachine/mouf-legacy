<?php
class BMW implements Car {
    protected $driver;
    protected $engine;
    protected $tire;

    /**
     * Create a new BMW
     *
     * @param Engine $engine
     * @param Tire $tire
     * @Inject
     */
    public function __construct(Engine $engine, Tire $tire) {
        $this->engine = $engine;
        $this->tire = $tire;
    }

    /**
     * Set the driver
     *
     * @param Person $driver
     * @Inject
     */
    public function setDriver(Person $driver) {
        $this->driver = $driver;
    }

    public function moveForward($miles) {
        $this->driver->sayHello();
        $this->engine->start();
        $this->tire->rotate();
    }
}

class Schst implements Person {
    public function sayHello() {
        echo "My name is Stephan\n";
    }
}

class Goodyear implements Tire {
    public function rotate() {
        echo "Rotating Goodyear tire\n";
    }
}

class TwoLitresEngine implements Engine {
    public function start() {
        echo "Starting 2l engine\n";
    }
}
?>