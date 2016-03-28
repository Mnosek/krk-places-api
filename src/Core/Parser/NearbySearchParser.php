<?php

namespace Core\Parser;

use stdClass;


/**
 * Nearby search parser
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class NearbySearchParser extends AbstractParser
{
    /**
     * Returns parsed response
     * @param  \Phalcon\Http\Client\Response
     * @return string json encoded result
     */
    public function parse($response)
    {
        $data = json_decode($response->body);
        $this->checkStatus($data);

        $places = array();

        foreach ($data->results as $result) {
            $place = array(
                'place_id' => $result->place_id,
                'name'     => $result->name,
                'vicinity' => $result->vicinity,
                'rating'   => isset($result->rating) ? $result->rating : false,
                'coords' => $this->getCoords($result->geometry)
            );
            array_push($places, $place);
        }

        return json_encode($places);
    }
}
