<?php

namespace There4Test\Slim\Test;

use PHPUnit\Framework\TestCase;
use There4\Slim\Test\WebDbTestCase;

class WebDbTestCaseTest extends TestCase
{
    public function testExtendsDbUnit()
    {
        $testCase = new WebDbTestCase();
        self::assertInstanceOf(
            '\PHPUnit\DbUnit\TestCase',
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
        $testCase = new WebDbTestCase();
        self::assertInstanceOf(
            '\PHPUnit\DbUnit\DataSet\QueryDataSet',
            $testCase->getDataSet()
        );
    }
}
