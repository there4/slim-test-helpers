<?php

namespace There4\Slim\Test;

use PDO;
use \PHPUnit\Framework\TestCase;

class WebDbTestCase extends TestCase
{
    /** @var \Slim\App */
    protected $app;

    /** @var WebTestClient */
    public $client;

    /** Database Connection **/
    private static $conn;

    public static function setUpBeforeClass() : void
    {
        self::$conn = new PDO('sqlite::memory:');
    }

    public static function tearDownAfterClass(): void
    {
        self::$conn = null;
    }

    // Run for each unit test to setup our slim app environment
    protected function setUp() : void
    {
        parent::setUp();

        // Establish a local reference to the Slim app object
        $this->app    = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
    }

    // Instantiate a Slim application for use in our testing environment. You
    // will most likely override this for your own application.
    public function getSlimInstance() : object
    {
        return SlimInstance::getInstance();
    }

    public function getConnection() : object
    {
        return static::$conn;
    }
}
