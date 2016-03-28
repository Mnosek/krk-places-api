<?php

namespace Core\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testValidResponse()
    {
        $response = new Response('{"msg": "Some test data content"}');

        $this->assertEquals($response->getStatusCode(), '200 OK');
        $this->assertEquals($response->getContent(), '{"msg": "Some test data content"}');
        $this->assertEquals($response->getHeaders()->get('Content-Type'), 'application/json');
    }
}
