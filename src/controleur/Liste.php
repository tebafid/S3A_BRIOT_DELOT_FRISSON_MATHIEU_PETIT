<?php


namespace wishlist\controleur;

use wishlist\modele\Liste as Lst;
use wishlist\vue\Liste as VLst;
use wishlist\modele\Utilisateur as Uti;

class Liste
{

    protected $user;

    public function __construct(){
        $this -> user = uti::getCurrentUser();
    }

    public function choixListe(){
        $liste = Lst::where('no','=',$this -> user) -> get();
        $aff = new VLst(DEMANDEUR,$liste);
        $aff -> afficherListe($liste);
        echo $aff -> render();
    }

    //creer une nouvelle liste
    public function nouvelleListe(){
        $a = new VLst(DEMANDEUR,null);
        $a -> creerListe();
        echo $a -> render();
    }

    //methode permettant d'enregistrer une liste
    public function enregistrerListe(){
        $app = \Slim\Slim::getInstance();
        $titre = $app -> request -> post('titre');
        $liste = new Lst();
        $liste -> titre = filter_var($titre, FILTER_SANITIZE_STRING);
        $liste -> expiration = filter_var($app -> request -> post ('expire'), FILTER_SANITIZE_NUMBER_INT);
        $liste -> id = $this -> user;
        $liste -> token = dechex(random_int(0,0xFFFFFFF));
        $liste -> save();
        $this -> choisListe();
    }

    //methode permettant d'afficher une liste
    public function afficherListe(string $name){
        $liste = Lst::where('token','=',$name) -> first();
        $aff = new VLst(DEMANDEUR,$liste);
        $aff -> afficherListe();
        echo $aff -> render();

    }

    //ajouter un message sur une liste
    //ajouter méthode permettant de modifier la liste
}