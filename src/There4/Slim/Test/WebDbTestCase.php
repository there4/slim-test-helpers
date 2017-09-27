<?php

namespace There4\Slim\Test;

use PDO;
use PHPUnit\DbUnit\TestCase;
use PHPUnit\DbUnit\DataSet\QueryDataSet;

class WebDbTestCase extends TestCase
{
    /** @var \Slim\App */
    protected $app;

    /** @var WebTestClient */
    public $client;

    /** Database Connection **/
    protected $conn;

    // Run for each unit test to setup our slim app environment
    public function setup()
    {
        parent::setUp();

        // Establish a local reference to the Slim app object
        $this->app    = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
    }

    // Instantiate a Slim application for use in our testing environment. You
    // will most likely override this for your own application.
    public function getSlimInstance()
    {
        return SlimInstance::getInstance();
    }

    public function getConnection()
    {
        if ($this->conn === null) {
            $pdo = new PDO('sqlite::memory:');
            $this->conn = $this->createDefaultDBConnection($pdo, ':memory:');
        }
        return $this->conn;
    }

    public function getDataSet()
    {
        return new QueryDataSet(
            $this->getConnection()
        );
    }
}
