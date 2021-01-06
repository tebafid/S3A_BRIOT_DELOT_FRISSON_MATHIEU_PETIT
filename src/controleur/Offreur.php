<?php


namespace wishlist\controleur;

use wishlist\modele\Liste as Lst;
use wishlist\vue\Liste as VLst;
use wishlist\modele\Item as Itm;

class Offreur
{

    //methode permettant d'afficher une liste
    public function afficherListe(string $name){
        $liste = Lst::where('token','=',$name) -> first();
        $aff = new VLst(OFFREUR,$liste);
        $aff -> afficheurListe();
        echo $aff -> render();
    }

    public function acquerirItem(string $name, Lst $id){
        $liste = Lst::where('token','=',$name) -> first();
        $item=Itm::where('liste_id','=',$liste->no) -> where('id','=',$id)->first();
        if (empty($item -> acquereur)){
            $app = \Slim\Slim::getInstance();
            $item -> acquereur = $app -> request -> post ('acquereur');
            $item -> message = $app -> request -> post ('message');
            $item -> save;
            $item -> afficherListe($name);
        }
    }

}