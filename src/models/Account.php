<?php

namespace MyWishlist\models;

/**
 * Class Account
 * @package MyWishlist\models
 */
class Account extends BaseModel
{
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'password'
    ];
}