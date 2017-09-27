<?php

namespace There4Test\Slim\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Slim\App;
use There4\Slim\Test\WebTestCase;
use There4\Slim\Test\WebTestClient;

class WebTestClientTest extends TestCase
{
    /**
     * @var App
     */
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
        self::assertEquals(200, $client->response->getStatusCode());
        self::assertEquals($expectedOutput, (string)$client->response->getBody());
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
        self::assertEquals(200, $client->response->getStatusCode());
        self::assertEquals('12', (string)$client->response->getBody());

        $client->get('/14');
        self::assertEquals(200, $client->response->getStatusCode());
        self::assertEquals('14', (string)$client->response->getBody());
    }

    public function testBodyResponse()
    {
        $this->getSlimInstance()->get('/', function ($req, $res) {
            return $res->write("body");
        });

        $client = new WebTestClient($this->getSlimInstance());
        $body   = $client->get('/');

        self::assertEquals('body', $body);
    }

    public function testPostParametersTransferred()
    {
        $this->getSlimInstance()->post('/post', function ($req, $res) {
            return $res->write((string) $req->getBody());
        });

        $client = new WebTestClient($this->getSlimInstance());
        $data   = ['test' => 'data'];
        $body   = $client->post('/post', $data);

        self::assertEquals(json_encode($data), $body);
    }

    public function getValidRequests()
    {
        $methods = $this->getValidRequestMethods();
        $uri = $this->getValidUri();
        return array_map(
            function ($value) use ($uri) {
                $input = ($value == 'post') ? ['test => data'] : array();
                return array($value, $uri, $input);
            },
            $methods
        );
    }

    private function getSlimInstance()
    {
        if (!$this->slim) {
            $testCase   = new WebTestCase();
            $this->slim = $testCase->getSlimInstance();
            $methods    = $this->getValidRequestMethods();
            $callback   = function ($req, $res) {
                return $res->write('This is a test!');
            };

            foreach ($methods as $method) {
                $this->slim->map([$method], $this->getValidUri(), $callback);
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
        $key    = "my_cookie";
        $value  = "test";
        $client->setCookie($key, $value);

        $body = $client->get('/');
        self::assertEquals($value, $client->request->getCookieParams()[$key]);
    }

    private function getValidRequestMethods()
    {
        return array(
            'get',
            'post',
            'patch',
            'put',
            'delete',
            'options',
            'head');
    }

    private function getValidUri()
    {
        return '/testing';
    }

    public function testInternalError()
    {
        $this->getSlimInstance()->get('/internalerror', function ($request, $response, $args) {
            throw new Exception('Testing /internalerror.');
            return $response;
        });

        $container = $this->getSlimInstance()->getContainer();
        $container['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($c) {
                $data = array('message' => 'Internal Server Error');
                return $c['response']->withJson($data, 500);
            };
        };

        $client = new WebTestClient($this->getSlimInstance());
        $client->get('/internalerror');
        self::assertEquals(500, $client->response->getStatusCode());
        $data = json_decode($client->response->getBody());
        self::assertEquals('Internal Server Error', $data->message);
    }
}
