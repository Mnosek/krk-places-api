<?php

namespace Core\Response;

use Phalcon\Http\Response as PhalconResponse;


/**
 * API success response model
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class Response extends PhalconResponse
{
    /**
     * Constructor
     * @param string $data json encoded response body
     */
    public function __construct($data)
    {
        parent::__construct();

        $this->setStatusCode(200, "OK")
             ->setContent($data)
             ->setHeader("Content-Type", "application/json");
    }
}
