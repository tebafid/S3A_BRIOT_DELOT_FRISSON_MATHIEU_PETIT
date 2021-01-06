<?php

namespace wishlist\modele;

class Item extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'item';
    protected $primaryKey = 'num';
    public $timestamps = false;
}