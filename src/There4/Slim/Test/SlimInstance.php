<?php
namespace There4\Slim\Test;

use \Slim\App;

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

        // Merge user settings
        $settings = array(
            'settings' => array_merge($defaultSettings, $settings)
        );

        // Create App
        return new App($settings);
    }
}
