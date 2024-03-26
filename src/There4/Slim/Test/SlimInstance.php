<?php
namespace There4\Slim\Test;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use \Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Environment;

/**
 * Static class to get configured Slim App Instance for use for TestCases
 */
class SlimInstance
{
    /**
     * Instantiate a Slim application for use in our testing environment.
     *
     * @param array $settings Any additional test environment settings
     *
     * @return App Instance of Slim App
     */
    public static function getInstance(array $settings = [])
    {
        $defaultSettings = array(
            'version'         => '0.0.0',
            'debug'           => false,
            'mode'            => 'testing',
            'routerCacheFile' => false
        );

        $container = new Container();
        $container['settings'] = new Collection(array_merge($defaultSettings, $settings));
        $container['environment'] = new Collection(Environment::mock());


        // Create App
        AppFactory::setContainer($container);
        
        return AppFactory::create();
    }
}
