<?php

namespace There4Test\Slim\Test;

use There4\Slim\Test\WebDbTestCase;

class WebDbTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsDbUnit()
    {
        $testCase = new WebDbTestCase();
        $this->assertInstanceOf('\PHPUnit_Extensions_Database_TestCase', $testCase);
    }

    public function testGetSlimInstance()
    {
        $expectedConfig = array(
            'version' => '0.0.0',
            'debug'   => false,
            'mode'    => 'testing'
        );
        $testCase = new WebDbTestCase();
        $slim = $testCase->getSlimInstance();
        $this->assertInstanceOf('\Slim\App', $slim);
        foreach ($expectedConfig as $key => $value) {
              $this->assertSame($expectedConfig[$key], $slim->getContainer()->get('settings')[$key]);
        }
    }
}
