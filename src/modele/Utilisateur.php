<?php


namespace wishlist\modele;


class Utilisateur extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //permet de récuperer le num de l'utilisateur
    public static function getCurrentUser(){
        return 1;
    }

}