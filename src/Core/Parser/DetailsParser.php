<?php

namespace Core\Parser;

use stdClass;


/**
 * Place details parser
 * @author Michał Nosek <mmnosek@gmail.com>
 */
class DetailsParser extends AbstractParser
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

        $place = array(
            'place_id'      => $data->result->place_id,
            'name'          => $data->result->name,
            'address'       => isset($data->result->formatted_address) ? $data->result->formatted_address : '',
            'phone_no'      => isset($data->result->formatted_phone_number) ? $data->result->formatted_phone_number : '',
            'lat'           => $data->result->geometry->location->lat,
            'lng'           => $data->result->geometry->location->lng,
            'website'       => isset($data->result->website) ? $data->result->website : '',
            'ratings_total' => isset($data->result->user_ratings_total) ? $data->result->user_ratings_total : '',
            'url'           => isset($data->result->url) ? $data->result->url : '',
            'rating'        => isset($data->result->rating) ? $data->result->rating : '',
            'reviews'       => isset($data->result->reviews) ? $this->getReviews($data->result->reviews) : ''
        );

        return json_encode($place);
    }


    /**
     * Parses revievs part of details
     * @param  array $posts
     * @return array
     */
    public function getReviews($posts)
    {
        $reviews = array();

        foreach ($posts as $post) {
            $review = array(
                'author' => $post->author_name,
                'rating' => $post->rating,
                'text'   => $post->text,
                'time'   => $post->time
            );
            array_push($reviews, $review);
        }

        return $reviews;
    }
}
