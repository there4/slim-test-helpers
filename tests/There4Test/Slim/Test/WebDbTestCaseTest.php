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

    public function testSetup()
    {
        $testCase = new WebDbTestCase();
        $testCase->setup();
        $actual_class = get_class($testCase->client);
        $expected_class = 'There4\Slim\Test\WebTestClient';
        $this->assertEquals($expected_class, $actual_class);
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
              $this->assertEquals($expectedConfig[$key], $slim->getContainer()->get('settings')[$key]);
        }
    }

    public function testGetDataset()
    {
        $testCase = new WebDbTestCase();
        $actual_ds = get_class($testCase->getDataSet());
        $expected_ds = 'PHPUnit_Extensions_Database_DataSet_QueryDataSet';
        $this->assertEquals($expected_ds, $actual_ds);
    }
}
