<?php

namespace Core\Action;

use Core\Action\Cacheable;
use Core\Parser\DetailsParser;
use InvalidArgumentException;


/**
 * Get place API action
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class GetPlace extends AbstractAction implements Cacheable
{
    /**
     * Returns Google Places API uri
     * @return string
     */
    public function getUri()
    {
        return 'place/details/json';
    }


    /**
     * Returns cache expiration time (in seconds)
     * @return int
     */
    public function getCacheExp()
    {
        return 3600;
    }


    /**
     * Returns action allowed additional params
     * @return array
     */
    public function getAllowedParams()
    {
        return ['placeid'];
    }


    /**
     * Returns valid action params
     * @return array
     *
     * @throws \InvalidArgumentException if param is not allowed
     */
    public function resolveParams()
    {
        $params = $this->getParams();

        array_walk($params, function($var, $key){
            if (!in_array($key, $this->getAllowedParams())) {
                throw new InvalidArgumentException();
            }
        });
        return $params;
    }


    /**
     * Returns parser for action
     * @return \Core\Parser\DetailsParser
     */
    public function getParser()
    {
        return new DetailsParser();
    }
}
