<?php

namespace Core\Action;

use Core\Provider;
use Core\Response\Response;
use Core\Response\Error;
use Exception;


/**
 * Api action base model
 * @author Michał Nosek <mmnosek@gmail.com>
 */
abstract class AbstractAction
{
    /**
     * Action query params
     * @var array
     */
    private $params = array();


    /**
     * Constructor
     * @param array $params query parameters
     */
    public function __construct($params)
    {
        $this->params = $params;
    }


    /**
     * Returns action params
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * Returns API response
     * @return \Core\Response\Response | \Core\Response\Error
     */
    public function getResponse()
    {
        try {
            $this->params = $this->resolveParams();
            $provider     = new Provider();
            $data         = $this->getParser()
                                 ->parse($provider->getData($this));

            $response = new Response($data);
        } catch (Exception $e) {
            $response = new Error($e);
        } finally {
            return $response;
        }
    }


    /**
     * Should return external Google Places API uri
     * @return string
     */
    abstract public function getUri();


    /**
     * Should return validated params
     * @return array params
     */
    abstract public function resolveParams();


    /**
     * Should return allowed action params
     * @return array
     */
    abstract public function getAllowedParams();


    /**
     * Should return action response parser
     * @return \Core\Parser\AbstractParser
     */
    abstract public function getParser();
}
