<?php
namespace Core\Parser;


class DetailsParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = $this->getMockBuilder('\Phalcon\Http\Client\Response')
                               ->disableOriginalConstructor()
                               ->setMethods(array())
                               ->getMock();

        $this->response->body = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .
                               'data' . DIRECTORY_SEPARATOR . 'google_details.txt');

        $this->parsed = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .
                        'data' . DIRECTORY_SEPARATOR . 'google_details_parsed.txt');
    }


    public function testParse()
    {
        $stub = $this->getMockBuilder('\Core\Parser\DetailsParser')
                     ->disableOriginalConstructor()
                     ->setMethods(array('checkStatus'))
                     ->getMock();

        $stub->method('checkStatus')
             ->willReturn(true);

        $parsed = $stub->parse($this->response);

        $this->assertJsonStringEqualsJsonString($parsed, $this->parsed);
    }
}
