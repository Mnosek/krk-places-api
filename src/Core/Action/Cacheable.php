<?php

namespace Core\Action;

/**
 * Interface for cacheable api actions
 * @author Michał Nosek <mmnosek@gmail.com>
 */
interface Cacheable {
    /**
     * Should return cache expiration time
     * @return int
     */
    public function getCacheExp();
}
