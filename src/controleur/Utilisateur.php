<?php


namespace wishlist\controleur;

use wishlist\modele\Utilisateur as Uti;
use wishlist\vue\Utilisateur as VUti;

class Utilisateur
{

    //methode permettant a un utilisateur de s'enregistrer
    public function registerForm(){
        $v = new VUti();
        $v -> registerForm();
    }

    //methode permettant de creer un utilisateur
    public function createUser(string $nom, string $password){
        $utilisateur = new Uti();
        $utilisateur -> nom = $nom;
        $utilisateur -> password = password_hash($password, PASSWORD_DEFAULT,['cost' => 12]);
        $utilisateur -> save();
    }

    public function authenticateUser(string $nom, string $password) : Uti{
        $uti = Uti::where('nom','=',$nom) -> first();
        if (! is_null($uti)){
            if(password_verify($password,$uti->password))
                return $uti;
        }
    }

    //methode permettant a l'utilisateur de partager une liste
    //methode permettant a l'utilisateur de consulter les reservations d'une liste

}