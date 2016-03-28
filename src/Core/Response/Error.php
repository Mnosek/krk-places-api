<?php

namespace Core\Response;

use Phalcon\Http\Response as PhalconResponse;
use RuntimeException;
use LengthException;
use InvalidArgumentException;
use Exception;


/**
 * API error response model
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class Error extends PhalconResponse
{
    /**
     * Constructor
     * @param Exception $e
     */
    public function __construct(Exception $e)
    {
        parent::__construct();

        $this->setStatusCode($this->statusCode($e), $this->statusMsg($e))
             ->setContent($this->content($e))
             ->setHeader("Content-Type", "application/json");
    }


    /**
     * Returns status code depending on exception
     * @param  Exception $e
     * @return int
     */
    private function statusCode(Exception $e)
    {
        switch (get_class($e)) {
            case 'LengthException':
                return 204;
            case 'RuntimeException':
                return 503;
            case 'InvalidArgumentException':
                return 400;
            default:
                return 500;
        }
    }


    /**
     * Returns status code msg depending on exception
     * @param  Exception $e
     * @return string
     */
    private function statusMsg(Exception $e)
    {
        switch (get_class($e)) {
            case 'LengthException':
                return 'No Content';
            case 'RuntimeException':
                return 'Service Unavailable';
            case 'InvalidArgumentException':
                return 'Bad request';
            default:
                return 'Internal Server Error';
        }
    }


    /**
     * Returns response content
     * @param  Exception $e
     * @return string json encoded content body
     */
    private function content(Exception $e)
    {
        $msg = $e->getMessage() ?: '';
        if (!$msg) {
            switch (get_class($e)) {
                case 'LengthException':
                    $msg = 'No data found';
                    break;
                case 'RuntimeException':
                    $msg = 'Service is contemporary unavailable. Please try again later';
                    break;
                case 'InvalidArgumentException':
                    $msg = 'Invalid request. Check headers and arguments';
                    break;
                default:
                    $msg = 'An internal server error occured, please try again later';
            }
        }

        return json_encode(array('error' => $msg));
    }
}
