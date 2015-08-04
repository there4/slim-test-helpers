<?php

namespace There4\Slim\Test;

use \Slim;

class WebTestClient
{
    /** @var \Slim\Slim */
    public $app;

    /** @var  \Slim\Http\Request */
    public $request;

    /** @var  \Slim\Http\Response */
    public $response;

    public function __construct(Slim\Slim $slim)
    {
        $this->app = $slim;
    }

    public function __call($method, $arguments)
    {
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }

    public function get($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('get', $path, $data, $optionalHeaders);
    }

    public function post($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('post', $path, $data, $optionalHeaders);
    }

    public function patch($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('patch', $path, $data, $optionalHeaders);
    }

    public function put($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('put', $path, $data, $optionalHeaders);
    }

    public function delete($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('delete', $path, $data, $optionalHeaders);
    }

    public function head($path, $data = array(), $optionalHeaders = array())
    {
        $this->request('head', $path, $data, $optionalHeaders);
    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    private function request($method, $path, $data = array(), $optionalHeaders = array())
    {
        // Capture STDOUT
        ob_start();

        $options = array(
            'REQUEST_METHOD' => strtoupper($method),
            'PATH_INFO'      => $path,
            'SERVER_NAME'    => 'local.dev'
        );

        if ($method === 'get') {
            $options['QUERY_STRING'] = http_build_query($data);
        } elseif (is_array($data)) {
            $options['slim.input']   = http_build_query($data);
        } else {
            $options['slim.input']   = $data;
        }

        // Prepare a mock environment
        Slim\Environment::mock(array_merge($options, $optionalHeaders));
        $env = Slim\Environment::getInstance();
        $this->app->router = new NoCacheRouter($this->app->router);
        $this->app->request = new Slim\Http\Request($env);
        $this->app->response = new Slim\Http\Response();

        // Establish some useful references to the slim app properties
        $this->request  = $this->app->request();
        $this->response = $this->app->response();

        // Execute our app
        $this->app->run();

        // Return the application output. Also available in `response->body()`
        return ob_get_clean();
    }
}
