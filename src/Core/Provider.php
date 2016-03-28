<?php

namespace Core;

use Phalcon\Http\Client\Request;
use Core\Action\Cacheable;
use Core\Action\AbstractAction;
use Redis;
use Exception;


/**
 * Places data provider.
 * Delivers data either from Google Places Api or Redis cache
 * @author Michał Nosek <mmnosek@gmail.com>
 */
final class Provider
{
    /**
     * Google Places API base URI
     * @var string
     */
    const GOOGLE_API_BASE_URI = 'https://maps.googleapis.com/maps/api/';


    /**
     * Google Places API access key
     * @var string
     */
    const GOOGLE_API_KEY      = 'AIzaSyAAjVdeHzjg4X_Y4DD0lBLJOqXooRGU8gU';


    /**
     * Redis server URI
     * @var string
     */
    const REDIS_URI           = '127.0.0.1';


    /**
     * Redis server port
     * @var string
     */
    const REDIS_PORT          = '6379';


    /**
     * External (Google Places API) data provider
     * @var \Phalcon\Http\Client\Provider\Curl
     */
    private $external;


    /**
     * Internal cache (Redis) data provider
     * @var \Redis
     */
    private $cache;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->external = $this->getExternal();
        $this->cache    = $this->getCache();
    }


    /**
     * Returns requested places data
     * @param  \Core\Action\AbstractAction $action
     * @return Phalcon\Http\Client\Response
     */
    public function getData(AbstractAction $action)
    {
        $params        = $action->getParams();
        $params['key'] = self::GOOGLE_API_KEY;

        if ($action instanceof Cacheable && $this->cache->isConnected()) {
            $data = unserialize($this->cache->get($this->getCacheKey($action)));
        }

        if (!isset($data) || !$data) {
            $data = $this->external->get($action->getUri(), $params);

            if ($action instanceof Cacheable && $this->cache->isConnected()) {
                $this->cache->set($this->getCacheKey($action), serialize($data));
                $this->cache->setTimeout($this->getCacheKey($action),
                                         $action->getCacheExp());
            }
        }
        return $data;
    }


    /**
     * Returns external (Google Places API) data provider
     * @var \Phalcon\Http\Client\Provider\Curl
     */
    private function getExternal()
    {
        $external = Request::getProvider();
        $external->setBaseUri(self::GOOGLE_API_BASE_URI);
        $external->header->set('Accept', 'application/json');

        return $external;
    }


    /**
     * Internal cache (Redis) data provider
     * @var \Redis
     */
    private function getCache()
    {
        $cache = new Redis();
        $cache->connect(self::REDIS_URI, self::REDIS_PORT);

        return $cache;
    }


    /**
     * Returns action related cache key
     * @param  \Core\Action\AbstractAction $action
     * @return string cache key
     */
    private function getCacheKey(AbstractAction $action)
    {
        $queryStr = http_build_query($action->getParams());
        return $action->getUri() . '?' . $queryStr;
    }
}
