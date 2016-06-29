<?php

namespace There4\Slim\Test;

use \Slim\App;

class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Slim\App */
    protected $app;

    /** @var WebTestClient */
    protected $client;

    // Run for each unit test to setup our slim app environment
    public function setup()
    {
        // Establish a local reference to the Slim app object
        $this->app = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);
    }

    // Instantiate a Slim application for use in our testing environment. You
    // will most likely override this for your own application.
    public function getSlimInstance()
    {
        $slim = new App(array(
          'settings' => [
            'version' => '0.0.0',
            'debug'   => false,
            'mode'    => 'testing']
        ));
        // force to overwrite the App singleton, so that \Slim\App::getInstance()
        // returns the correct instance.
        //$slim->setName('default');

        // make sure we don't use a caching router
        // $slim->getContainer()->router = new NoCacheRouter($slim->getContainer()->router);
        return $slim;
    }
}
