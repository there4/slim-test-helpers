<?php

namespace There4Test\Slim\Test;

use There4\Slim\Test\WebTestCase;

class WebTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testSetup()
    {
        $testCase = new WebTestCase();
        $testCase->setup();
        $this->assertInstanceOf('\Slim\App', $testCase->getSlimInstance());
    }

    public function testGetSlimInstance()
    {
        $expectedConfig = array(
            'version' => '0.0.0',
            'debug'   => false,
            'mode'    => 'testing'
        );
        $testCase = new WebTestCase();
        $slim = $testCase->getSlimInstance();
        $this->assertInstanceOf('\Slim\App', $slim);
        foreach ($expectedConfig as $key => $value) {
            $this->assertSame($expectedConfig[$key], $slim->getContainer()->get('settings')[$key]);
        }
    }
}
