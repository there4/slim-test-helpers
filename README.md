# Slim Test Helpers [![Build Status](https://travis-ci.org/there4/slim-test-helpers.svg?branch=master)](https://travis-ci.org/there4/slim-test-helpers) [![Code Climate](https://codeclimate.com/github/there4/slim-test-helpers/badges/gpa.svg)](https://codeclimate.com/github/there4/slim-test-helpers) [![Test Coverage](https://codeclimate.com/github/there4/slim-test-helpers/badges/coverage.svg)](https://codeclimate.com/github/there4/slim-test-helpers/coverage)
> Integration testing helpers for the Slim Framework 3

For a full example, please see the companion repo at [there4/slim-unit-testing-example][example].

## Example

Here's a test for a very simple endpoint that returns the version from the
application config. We're asserting that Slim responded with a `200` and that
the version matches what we expect.

```php
class VersionTest extends LocalWebTestCase
{
    public function testVersion()
    {
        $this->client->get('/version');
        $this->assertEquals(200, $this->client->response->getStatusCode());
        $this->assertEquals($this->app->config('version'), $this->client->response->getBody());
    }
}
```

Here is an example on how to pass data to a POST endpoint in a test case and 
retrieve it later in the endpoint. We are passing encoded JSON data in the body
of the request. The data is retrieved in the endpoint using 
`$app->request->getBody()`.

```php
// test file
class UserTest extends LocalWebTestCase
{
    public function testVersion()
    {
        ......
        $data = array("user" => 1);
        $data = json_encode($data);
        $this->client->post('/user', $data);
        ......
    }
}

// endpoint file
.....
$app->post('/user', function() use ($app) {
    .....
    $data = $app->request->getBody();
    $data = json_decode($data, true);
    ......
});
```

### Example with DbUnit

If you wish to use Database fixture, use class `WebDbTestCase`. *Caution*: Make 
sure the names you use for you fixture models won't conflict with your actual
DB tables.

```php
class LocalDbWebTestCase extends \There4\Slim\Test\WebDbTestCase
{
    /**
     * You must implement this method
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fixture.xml'
        );
    }
}
```

## Setup

You'll need a bootstrap file for phpunit that can instantiate your Slim application. You can see [an example boostrap] in [the sample app][example].

You'll implement your own `getSlimInstance()` method that returns an instance of your app [by extending][webtestcase] the `WebTestCase` helper.

[example]: https://github.com/there4/slim-unit-testing-example
[bootstrap]: https://github.com/there4/slim-unit-testing-example/blob/master/tests/bootstrap.php
[webtestcase]: https://github.com/there4/slim-test-helpers/blob/master/src/There4/Slim/Test/WebTestCase.php
