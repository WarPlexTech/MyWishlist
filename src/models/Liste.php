<?php

namespace MyWishlist\models;

/**
 * Class Liste
 * @package MyWishlist\models
 */
class Liste extends BaseModel
{
    protected $table = 'liste';
    protected $primaryKey = 'no';
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'expiration',
        'token'
    ];
}