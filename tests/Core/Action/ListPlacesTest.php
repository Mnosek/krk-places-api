<?php

namespace Core\Action;

class ListPlacesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParser()
    {
        $action = new ListPlaces(array());
        $this->assertInstanceOf('\Core\Parser\AbstractParser', $action->getParser());
    }


    public function testGetCacheExp()
    {
        $action = new ListPlaces(array());
        $this->assertInternalType('int', $action->getCacheExp());
        $this->assertGreaterThan(0, $action->getCacheExp());
    }


    public function testGetUri()
    {
        $action = new ListPlaces(array());
        $this->assertInternalType('string', $action->getUri());
        $this->assertGreaterThan(0, strlen($action->getUri()));
    }


    public function testGetAllowedParams()
    {
        $action = new ListPlaces(array());
        $this->assertContainsOnly('string', $action->getAllowedParams());
    }


    public function testResolveValidParams()
    {
        $stub = $this->getMockBuilder('\Core\Action\ListPlaces')
                     ->disableOriginalConstructor()
                     ->setMethods(array('getAllowedParams', 'getParams'))
                     ->getMock();

        $stub->method('getAllowedParams')
             ->willReturn(array('location', 'keyword', 'some_other_param'));

        $stub->method('getParams')
              ->willReturn(array('location' => '34.33', 'keyword' => 'test'));

        $this->assertContains('34.33', $stub->resolveParams());
        $this->assertContains('test', $stub->resolveParams());
        $this->assertContains('bar', $stub->resolveParams());
    }


    public function testResolveInvalidParams()
    {
        $stub = $this->getMockBuilder('\Core\Action\ListPlaces')
                     ->disableOriginalConstructor()
                     ->setMethods(array('getAllowedParams', 'getParams'))
                     ->getMock();

        $stub->method('getAllowedParams')
             ->willReturn(array('location'));

        $stub->method('getParams')
              ->willReturn(array('invalid_param' => 'something'));

        $this->setExpectedException('InvalidArgumentException');
        $stub->resolveParams();
    }
}
