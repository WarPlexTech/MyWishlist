<?php


namespace MyWishlist\middleware;

/**
 * Class Middleware
 * @package MyWishlist\middleware
 */
class Middleware
{
    protected $container;

    /**
     * Middleware constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }


}