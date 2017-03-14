<?php

namespace There4\Slim\Test;

class WebTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Slim\App */
    protected $app;

    /** @var WebTestClient */
    protected $client;

    // Run for each unit test to setup our slim app environment
    public function setup()
    {
        // Establish a local reference to the Slim app object
        // Ensure no cache Router
        $this->app    = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
    }

    // Return the configured Slim App object
    public function getSlimInstance()
    {
        return SlimInstance::getInstance();
    }
}
