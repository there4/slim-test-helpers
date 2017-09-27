<?php

namespace There4Test\Slim\Test;

use PHPUnit\Framework\TestCase;
use There4\Slim\Test\WebTestCase;

class WebTestCaseTest extends TestCase
{
    public function testSetup()
    {
        $testCase = new WebTestCase();
        $testCase->setup();
        self::assertInstanceOf('\Slim\App', $testCase->getSlimInstance());
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
        self::assertInstanceOf('\Slim\App', $slim);
        foreach ($expectedConfig as $key => $value) {
            self::assertSame($expectedConfig[$key], $slim->getContainer()->get('settings')[$key]);
        }
    }
}
