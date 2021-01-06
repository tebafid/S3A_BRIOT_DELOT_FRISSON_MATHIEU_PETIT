<?php

namespace wishlist\vue;

use wishlist\modele\Item;

class Liste extends Vue
{
    protected $liste;

    public function __construct($demandeur, $liste)
    {
        parent::__construct($demandeur);
        $this->liste = $liste;
    }

    public function creerListe(){
        $this->html = <<<fin
        <h3>Commencer une liste de voeux</h3>
        <form method='post' action=''>
        <p>Titre: <input type='text' name='titre'></p>
        <p>Expire: <input type='date' name='expire'></p>
        <input type='submit' value='Créer'>
        </form>;
fin;



    }

    public function afficherListes(){
        $app = \Slim\Slim::getInstance();
        $this->html = "<h2>Choisir une liste de voeux</h2>";
        foreach ($this->liste as $liste){
            $url = $app->urlFor('voir_liste', array('name' => $liste->tocken));
            $this->html .= <<<fin
    <div><a href="$url">$liste->titre</a></div>
fin;
        }
        $url2 = $app->urlFor('nouvelle_liste', array());
        $this->html .= <<<fin
    <a href="$url2">Créer une nouvelle liste</a>
fin;
    }

    public function afficherListeNvItem(){
        $this->html = "<h2>{$this->liste->titre}</h2>";
        $this->html .= Item::creerItem();
    }

    public function afficherListe(){
        $app = \Slim\Slim::getInstance();
        $this->html = "<h2>{$this->liste->titre}</h2>";
        foreach ($this->liste->items as $item){
            $i = new Item($this->role, $this->liste, $item);
            $this->html .= $i->afficherItem();
        }
        $url=$app->urlFor('formulaire_item', array('name'=>$this->liste->token));
        if($this->role==DEMANDEUR)
            $this->html .= "<a href='$url'>Ajouter un item</a>";
    }


}