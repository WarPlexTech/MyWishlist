<?php

namespace MyWishlist\models;

/**
 * Class Item
 * @package MyWishlist\models
 */
class Item extends BaseModel
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    protected $fillable = [
        'liste_id',
        'nom',
        'descr',
        'img',
        'url',
        'tarif',
        'reserve'
    ];
}