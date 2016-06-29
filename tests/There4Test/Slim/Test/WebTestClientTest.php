<?php

namespace There4Test\Slim\Test;

use There4\Slim\Test\WebTestCase;
use There4\Slim\Test\WebTestClient;

class WebTestClientTest extends \PHPUnit_Framework_TestCase
{
    private $slim;

    /**
     * @dataProvider getValidRequests
     * @param string $name
     * @param string $uri
     * @param mixed $input
     */
    public function testValidRequests($name, $uri, $input)
    {
        $client = new WebTestClient($this->getSlimInstance());
        $expectedOutput = 'This is a test!';
        call_user_func(array($client, $name), $uri, $input);
        $this->assertSame(200, $client->response->getStatusCode());
        $this->assertSame($expectedOutput, (string)$client->response->getBody());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidRequestMethods()
    {
        $client = new WebTestClient($this->getSlimInstance());
        $client->foo($this->getValidUri());
    }

    public function testMultipleRequest()
    {
        $this->getSlimInstance()->get('/{id}', function ($req, $res, $args) {
            return $res->write($args['id']);
        });

        $client = new WebTestClient($this->getSlimInstance());
        $client->get('/12');
        $this->assertSame(200, $client->response->getStatusCode());
        $this->assertSame('12', (string)$client->response->getBody());

        $client->get('/14');
        $this->assertSame(200, $client->response->getStatusCode());
        $this->assertSame('14', (string)$client->response->getBody());
    }

    public function testBodyResponse()
    {
        $this->getSlimInstance()->get('/', function ($req, $res) {
            return $res->write("body");
        });

        $client = new WebTestClient($this->getSlimInstance());
        $body = $client->get('/');
        $this->assertSame('body', $body);
    }

    public function getValidRequests()
    {
        $methods = $this->getValidRequestMethods();
        $uri = $this->getValidUri();
        return array_map(function ($value) use ($uri) {
            $input = ($value == 'post') ? 'test data' : array();
            return array($value, $uri, $input);
        }, $methods);
    }

    private function getSlimInstance()
    {
        if (!$this->slim) {
            $testCase = new WebTestCase();
            $this->slim = $testCase->getSlimInstance();
            $methods = $this->getValidRequestMethods();
            $callback = function ($req, $res) {
                return $res->write('This is a test!');
            };
            foreach ($methods as $method) {
                $this->slim->$method($this->getValidUri(), $callback);
            }
        }
        return $this->slim;
    }

    public function testCookieSetInRequest()
    {
        $this->getSlimInstance()->get('/', function ($req, $res) {
            return $res->write("body");
        });

        $client = new WebTestClient($this->getSlimInstance());
        $key = "my_cookie";
        $value = "test";
        $client->setCookie($key, $value);
        $body = $client->get('/');
        $this->assertSame($value, $client->request->getCookieParams()[$key]);
    }

    private function getValidRequestMethods()
    {
        return array('get', 'post', 'patch', 'put', 'delete', 'options');
    }

    private function getValidUri()
    {
        return '/testing';
    }
}
