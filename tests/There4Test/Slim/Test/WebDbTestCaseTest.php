<?php

namespace There4Test\Slim\Test;

use There4\Slim\Test\WebDbTestCase;

class WebDbTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsDbUnit()
    {
        $testCase = new WebDbTestCase();
        self::assertInstanceOf(
            '\PHPUnit_Extensions_Database_TestCase',
            $testCase
        );
    }

    public function testSetup()
    {
        $testCase = new WebDbTestCase();
        $testCase->setup();

        $actualClass   = get_class($testCase->client);
        $expectedClass = 'There4\Slim\Test\WebTestClient';

        self::assertEquals($expectedClass, $actualClass);
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
        self::assertInstanceOf('\Slim\App', $slim);
        foreach ($expectedConfig as $key => $value) {
            self::assertEquals(
                $expectedConfig[$key],
                $slim->getContainer()->get('settings')[$key]
            );
        }
    }

    public function testGetDataset()
    {
        $testCase        = new WebDbTestCase();
        $actualDataSet   = get_class($testCase->getDataSet());
        $expectedDataSet = 'PHPUnit_Extensions_Database_DataSet_QueryDataSet';

        self::assertEquals($expectedDataSet, $actualDataSet);
    }
}
