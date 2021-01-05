<?php

namespace MyWishlist\controllers;

/**
 * Tout les controlleurs heritent de cette classe
 * @package MyWishlist\controllers
 */
class BaseController
{

    protected $container;

    /**
     * BaseController constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

}