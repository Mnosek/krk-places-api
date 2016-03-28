<?php

namespace Core\Action;

use Core\Action\Cacheable;
use Core\Parser\NearbySearchParser;
use InvalidArgumentException;


/**
 * List places API action
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class ListPlaces extends AbstractAction implements Cacheable
{
    /**
     * Default coords if not provided
     * @var string
     */
    const MAIN_POINT_COORDS = '50.061693, 19.937341';


    /**
     * Google Places API place type
     * @var string
     */
    const API_PLACE_TYPE    = 'bar';


    /**
     * Google Places API radius
     * @var string
     */
    const API_PLACE_RADIUS  = '2000';


    /**
     * Returns Google Places API uri
     * @return string
     */
    public function getUri()
    {
        return 'place/nearbysearch/json';
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
        return ['location', 'keyword'];
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

        $params['type'] = self::API_PLACE_TYPE;

        if ($params['location']) {
                $params['rankby'] = 'distance';
        } else {
            $params['radius']   = self::API_PLACE_RADIUS;
            $params['location'] = self::MAIN_POINT_COORDS;
        }

        return $params;
    }


    /**
     * Returns parser for action
     * @return \Core\Parser\NearbySearchParser
     */
    public function getParser()
    {
        return new NearbySearchParser();
    }
}
