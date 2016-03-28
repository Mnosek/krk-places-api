<?php

namespace Core\Response;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testLengthException()
    {
        $exception = new \LengthException();
        $error = new Error($exception);

        $this->assertEquals($error->getStatusCode(), '204 No Content');
        $this->assertEquals($error->getContent(), '{"error":"No data found"}');
        $this->assertEquals($error->getHeaders()->get('Content-Type'), 'application/json');
    }


    public function testRuntimeException()
    {
        $exception = new \RuntimeException();
        $error = new Error($exception);

        $this->assertEquals($error->getStatusCode(), '503 Service Unavailable');
        $this->assertEquals($error->getContent(), '{"error":"Service is contemporary unavailable. Please try again later"}');
        $this->assertEquals($error->getHeaders()->get('Content-Type'), 'application/json');
    }


    public function testInvalidArgumentException()
    {
        $exception = new \InvalidArgumentException();
        $error = new Error($exception);

        $this->assertEquals($error->getStatusCode(), '400 Bad request');
        $this->assertEquals($error->getContent(), '{"error":"Invalid request. Check headers and arguments"}');
        $this->assertEquals($error->getHeaders()->get('Content-Type'), 'application/json');
    }

    public function testException()
    {
        $exception = new \Exception();
        $error = new Error($exception);

        $this->assertEquals($error->getStatusCode(), '500 Internal Server Error');
        $this->assertEquals($error->getContent(), '{"error":"An internal server error occured, please try again later"}');
        $this->assertEquals($error->getHeaders()->get('Content-Type'), 'application/json');
    }


    public function testCustomException()
    {
        $exception = new \Exception('Some custom message');
        $error = new Error($exception);

        $this->assertEquals($error->getStatusCode(), '500 Internal Server Error');
        $this->assertEquals($error->getContent(), '{"error":"Some custom message"}');
        $this->assertEquals($error->getHeaders()->get('Content-Type'), 'application/json');
    }
}
