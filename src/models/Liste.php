<?php


namespace wishlist\models;


class Liste extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'liste';
    protected $primaryKey = 'no';
    public $timestamps = false;

    public function items(){
        return $this->hasMany('\wishlist\modele\Item', 'liste_id');
    }

}