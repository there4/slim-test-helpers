<?php

namespace There4\Slim\Test;

use Illuminate\Database\Eloquent\Model as Eloquent;
use \Slim\Slim;

// @todo write unit tests + require dbunit in composer.json + check if need to require Illuminate\Database\Eloquent\Model
class WebDbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
	/** @var \Slim\Slim */
	protected $app;

	/** @var WebTestClient */
	protected $client;

	// Run for each unit test to setup our slim app environment
	public function setup()
	{
		parent::setUp();
		// Establish a local reference to the Slim app object
		$this->app = $this->getSlimInstance();
		$this->client = new WebTestClient($this->app);
	}

	// Instantiate a Slim application for use in our testing environment. You
	// will most likely override this for your own application.
	public function getSlimInstance()
	{
		// @todo Find a way not to duplicate code from WebTestCase. Using trait file?
		$slim = new Slim(array(
			'version' => '0.0.0',
			'debug'   => false,
			'mode'    => 'testing'
		));
		// force to overwrite the App singleton, so that \Slim\Slim::getInstance()
		// returns the correct instance.
		$slim->setName('default');

		// make sure we don't use a caching router
		$slim->router = new NoCacheRouter($slim->router);
		return $slim;
	}

	public function getConnection()
	{
		$pdo = Eloquent::getConnectionResolver()->connection()->getPdo();
		return $this->createDefaultDBConnection($pdo, ':memory:');
	}

	public function getDataSet()
	{
		throw new \Exception('Method getDataSet() not implemented!');
	}
}