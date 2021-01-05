<?php

namespace MyWishlist\models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package MyWishlist\models
 * @mixin Builder
 */
class BaseModel extends Model
{
    //On desactive le timestamping des mise a jour
    public $timestamps = false;
}