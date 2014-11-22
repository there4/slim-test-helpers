# Slim Test Helpers [![Build Status](https://travis-ci.org/there4/slim-test-helpers.svg?branch=master)](https://travis-ci.org/there4/slim-test-helpers)
> Integration testing helpers for the Slim Framework

For a full example, please see the companion repo at [there4/slim-unit-testing-example][example].

## Example

Here's a test for a very simple endpoint that returns the version from the
application config. We're asserting that Slim responded with a `200` and that
the version matches what we expect.

```php
class VersionTest extends LocalWebTestCase {
    public function testVersion() {
        $this->client->get('/version');
        $this->assertEquals(200, $this->client->response->status());
        $this->assertEquals($this->app->config('version'), $this->client->response->body());
    }
}
```

## Setup

You'll need a bootstrap file for phpunit that can instantiate your Slim
application. You can see [an example boostrap] in [the sample app][example].

You'll implement your own `getSlimInstance()` method that returns an instance of
your app [by extending][webtestcase] the `WebTestCase` helper.

[example]: https://github.com/there4/slim-unit-testing-example
[bootstrap]: https://github.com/there4/slim-unit-testing-example/blob/master/tests/bootstrap.php
[webtestcase]: https://github.com/there4/slim-test-helpers/blob/master/src/There4/Slim/Test/WebTestCase.php
