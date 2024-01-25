<?php

namespace There4\Slim\Test;

use Slim\App;

use Slim\Psr7\Environment;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class WebTestClient
{
    /** @var \Slim\App */
    public $app;

    /** @var  \Slim\Psr7\Request */
    public $request;

    /** @var  \Slim\Psr7\Response */
    public $response;

    private $cookies = array();

    public function __construct(App $slim)
    {
        $this->app = $slim;
    }

    public function __call($method, $arguments)
    {
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }

    public function get($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('get', $path, $data, $optionalHeaders);
    }

    public function post($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('post', $path, $data, $optionalHeaders);
    }

    public function patch($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('patch', $path, $data, $optionalHeaders);
    }

    public function put($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('put', $path, $data, $optionalHeaders);
    }

    public function delete($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('delete', $path, $data, $optionalHeaders);
    }

    public function head($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('head', $path, $data, $optionalHeaders);
    }

    public function options($path, $data = array(), $optionalHeaders = array())
    {
        return $this->request('options', $path, $data, $optionalHeaders);
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    private function request($method, $path, $data = array(), $optionalHeaders = array())
    {
        //Make method uppercase
        $method = strtoupper($method);
        $options = array(
            'REQUEST_METHOD' => $method,
            'REQUEST_URI'    => $path
        );

        $query = '';
        if ($method === 'GET') {
            $query = http_build_query($data);
        } else {
            $params  = json_encode($data);
        }

        // Prepare a mock environment
        $env = Environment::mock(array_merge($options, $optionalHeaders));

        // $uri = Uri::createFromEnvironment($env);
        $uri = new Uri($env['REQUEST_SCHEME'], $env['SERVER_NAME'], $env['SERVER_PORT'], $path, $query);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $headerdata = [];
        $special = [
            'CONTENT_TYPE' => 1,
            'CONTENT_LENGTH' => 1,
            'PHP_AUTH_USER' => 1,
            'PHP_AUTH_PW' => 1,
            'PHP_AUTH_DIGEST' => 1,
            'AUTH_TYPE' => 1,
        ];
        foreach ($env as $key => $value) {
            $key = strtoupper($key);
            if (isset($special[$key]) || strpos($key, 'HTTP_') === 0) {
                if ($key !== 'HTTP_CONTENT_LENGTH') {
                    $headerdata[$key] =  $value;
                }
            }
        }
        $headers = new Headers($headerdata);

        // $headers = Headers::createFromEnvironment($env);
        $cookies = $this->cookies;

        // $serverParams = $env->all();
        // $body = new RequestBody();

        // Attach JSON request
        if (isset($params)) {
            $headers->addHeader('Content-Type', 'application/json;charset=utf8');
            $stream->write($params);
        }

        $this->request  = new SlimRequest(strtoupper($method), $uri, $headers, $cookies, $env /* $serverParams */, $stream);

        // Process request
        $this->response = $this->app->handle($this->request);

        // Return the application output.
        return (string)$this->response->getBody();
    }

    public function setCookie($name, $value)
    {
        $this->cookies[$name] = $value;
    }
}
