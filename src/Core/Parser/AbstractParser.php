<?php

namespace Core\Parser;

use Phalcon\Http\Client\Repsonse as HttpResponse;
use InvalidArgumentException;
use RuntimeException;
use LengthException;
use Exception;
use stdClass;


/**
 * Api response parser base model
 * @author Michał Nosek <mmnosek@gmail.com>
 */
abstract class AbstractParser
{
    /**
     * Checks Google Places API response status
     * @param  stdClass $data
     * @return boolean
     *
     * @throws LengthException if no results
     * @throws RuntimeException if out of query limit or invalid api key
     * @throws InvalidArgumentException if invalid api request
     */
    public function checkStatus($data)
    {
        if (!isset($data->status)) {
            throw new RuntimeException;
        }

        switch ($data->status) {
            case 'OK':
                return true;
            case 'ZERO_RESULTS':
                throw new LengthException();
            case 'QUERY_QUERY_LIMIT':
                throw new RuntimeException();
            case 'REQUEST_DENIED':
                throw new RuntimeException();
            case 'INVALID_REQUEST':
                throw new InvalidArgumentException();
            default:
                throw new Exception();
        }
    }


    /**
     * Should return parsed response
     * @param  mixed $data
     * @return string json encoded result
     */
    abstract public function parse($data);
}
