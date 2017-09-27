<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Backward compatibility for PHPUnit 4/5
if (!class_exists('\PHPUnit\Framework\TestCase') &&
    class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

// Backward compatibility for DBUnit 2
if (!class_exists('\PHPUnit\DbUnit\TestCase') &&
    class_exists('\PHPUnit_Extensions_Database_TestCase')) {
    class_alias(
        '\PHPUnit_Extensions_Database_TestCase',
        'PHPUnit\DbUnit\TestCase'
    );
}

if (!class_exists('\PHPUnit\DbUnit\DataSet\QueryDataSet') &&
    class_exists('\PHPUnit_Extensions_Database_DataSet_QueryDataSet')) {
    class_alias(
        '\PHPUnit_Extensions_Database_DataSet_QueryDataSet',
        'PHPUnit\DbUnit\DataSet\QueryDataSet'
    );
}
