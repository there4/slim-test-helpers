<?php

namespace There4\Slim\Test;

class WebTestCase extends \PHPUnit_Framework_TestCase
{
    // Run for each unit test to setup our slim app environment
    public function setup()
    {
        // Establish a local reference to the Slim app object
        $this->app = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
    }

    // Instantiate a Slim application for use in our testing environment. You
    // will most likely override this for your own application.
    public function getSlimInstance() {
        return new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug'   => false,
            'mode'    => 'testing'
        ));
    }
}

/* End of file WebTestCase.php */