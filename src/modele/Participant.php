<?php


namespace wishlist\modele;


class Participant extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participant';
    protected $primaryKey = 'id';
    public $timestamps = false;
}