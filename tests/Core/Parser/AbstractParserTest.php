<?php
namespace Core\Parser;

use stdClass;
use Exception;

class AbstractParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCoords()
    {
        $parser = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');

        $data = new stdClass;
        $data->location = new stdClass;
        $data->location->lat = '50.2323';
        $data->location->lng = '23.3545';

        $this->assertEquals($parser->getCoords($data), '50.2323,23.3545');
    }



    public function testEmptyData()
    {
        $this->setExpectedException('RuntimeException');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $stub->checkStatus(new stdClass());
    }

    public function testInvalidStatus()
    {
        $this->setExpectedException('Exception');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $data = new stdClass();
        $data->status = 'INVALID_STATUS_ACRO';
        $stub->checkStatus($data);
    }


    public function testZeroResults()
    {
        $this->setExpectedException('LengthException');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $data = new stdClass();
        $data->status = 'ZERO_RESULTS';
        $stub->checkStatus($data);
    }


    public function testQueryLimit()
    {
        $this->setExpectedException('RuntimeException');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $data = new stdClass();
        $data->status = 'QUERY_QUERY_LIMIT';
        $stub->checkStatus($data);
    }


    public function testRequestDenied()
    {
        $this->setExpectedException('RuntimeException');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $data = new stdClass();
        $data->status = 'REQUEST_DENIED';
        $stub->checkStatus($data);
    }


    public function testInvalidRequest()
    {
        $this->setExpectedException('InvalidArgumentException');
        $stub = $this->getMockForAbstractClass('\Core\Parser\AbstractParser');
        $data = new stdClass();
        $data->status = 'INVALID_REQUEST';
        $stub->checkStatus($data);
    }
}
