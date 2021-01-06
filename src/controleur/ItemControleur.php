<?php


namespace wishlist\controleur;

use wishlist\modele\Liste as Liste;
use wishlist\vue\Liste as VueListe;
use wishlist\modele\Utilisateur as Uti;
use wishlist\modele\Item as Item;
use wishlist\vue\Item as VueItem;

class ItemControleur
{
    protected $liste;

    public function __construct($name)
    {
        $this->liste=Liste::where('token','=',$name)->first();
    }

    //methode permettant de creer un item
    public function itemCreation(){
        $aff = new VueListe(DEMANDEUR,$this->liste);
        $aff->afficherListeNvItem();
        echo $aff -> render();
    }

    //methode permettant d'ajouter des item dans la liste
    public function ajouterItem(){
        $app =  \Slim\Slim::getInstance();
        $nom = $app -> post('titre');
        $description = $app -> request -> post('descr');

        $item = new Item();
        $item -> nom = filter_var($nom,FILTER_SANITIZE_STRING);
        $item -> descr = filter_var($description, FILTER_SANITIZE_STRING);
        $item -> liste = $this -> liste -> no;
        $item -> tarif = intval($app -> request -> post('prix'));
        $item -> save();
        $aff = new Liste();
        $aff -> afficherListe($this -> liste -> token);
    }

    public function afficherItem(int $id){
        $item = Item::where('liste_id','=', $this -> liste -> no) -> where('id','=',$id) -> first();
        $aff = new VueItem(DEMANDEUR, $this -> liste, $item);
        echo $aff -> render();
    }

    //ajout de la reservation d'un item
    //ajout d'une methode permettant de modifier un item
    //ajout d'une methode permettant de supprimer modifier un item
    //methode permettant de rajouter une image a un item
    //methode permettant de supprimer une image a un item
}