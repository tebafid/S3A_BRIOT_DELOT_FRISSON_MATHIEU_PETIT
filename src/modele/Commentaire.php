<?php


namespace wishlist\modele;

class Commentaire extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'commentaire';
    protected $primaryKey = 'id';
    public $timestamps = false;
}