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

    /**
     * affiche la création de la liste
     */
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

    /**
     * modifie la liste
     * @return string
     */
    private function modifliste(): string
    {
        $url = $this -> container -> router -> pathFor ( 'modifierliste', ['tokenModif' => $this -> tab['tokenModif']] );
        if ($this -> tab['acces'] == "public") {
            $a = "<label>Liste publique?</label><input type='checkbox' name='etat' value='yes' checked><br><br>";
        } else {
            $a = "<label>Liste publique?</label><input type='checkbox' name='etat' value='yes'><br><br>";
        }
        $html = <<<FIN
    <h1>Modifier une liste</h1>
    <form method="POST" action="$url">
	<label>Titre :<br> <input type="text" name="titre" value="{$this -> tab['titre']}"/></label><br>
	<label>Description : <br><input type="text" name="description" value="{$this -> tab['description']}"/></label><br>
	<label>Date d'expiration : <br><input type="date" name="date" value="{$this -> tab['expiration']}"/></label><br>
	$a
	<button class="button" type="submit">Enregistrer la modification</button>
    </form>	
    FIN;
        return $html;
    }

    /**
     * ajoute une liste a la base
     * @return string
     */
    private function ajouterUneListe(): string
    {
        $url = $this -> container -> router -> pathFor ( 'sajouterUneListe' );
        $html = <<<FIN
    <form method="POST" action="$url">
	<label>Code de modification :<br> <input type="text" name="token" required/></label><br>
	<button class="button" type="submit">Ajouter</button>
    </form>	
FIN;
        return $html;
    }

    /**
     * affiche les listes
     */
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

    /**
     * affiche une liste
     */
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




    // partie commentaire de liste

    /**
     * ajoute un commentaire
     * @return string
     */
    public function ajouterCommentaire(): string
    {
        $url = $this -> container -> router -> pathFor ( 'ajouterCom', ['token' => $this -> tab['token']] );
        if(isset($_COOKIE['commentaire'])){
            if(isset($_COOKIE['iduser'])){
                $value = User::find($_COOKIE['iduser'])->nom;
            }else{
                $value = $_COOKIE['commentaire'];
            }
            $content = "<label>Nom :<br> <input type='text' name='nom' value='$value' required/></label><br> ";
        }else{
            $content = "<label>Nom :<br> <input type='text' name='nom' required/></label><br> ";
        }
        $html = <<<FIN
<hr><h1>Ajouter un commentaire a cette liste</h1>
<form method="POST" action="$url">
	$content
	<label>Commentaire : <br><input type='text' name='commentaire' required/></label><br>
	<button class="button" type="submit">Publier</button>
</form>	<br>
FIN;
        return $html;
    }


    /**
     *affiche le commentaire de la liste
     * @return string
     */
    public function affichageCommentaire(): string
    {
        $com = Commentaire ::all ();
        if (Commentaire ::where ( "id_liste", "=", Liste ::where ( "token", "=", $this -> tab['token'] ) -> first () -> no ) -> first () != null) {
            $html = "<hr><h1>Commentaires</h1>";
        } else {
            $html = "<hr><h1>Aucun commentaires</h1>";
        }

        foreach ($com as $c) {
            if ($c -> id_liste == Liste ::where ( "token", "=", $this -> tab['token'] ) -> first () -> no) {
                $html .= "<h3>$c->nom : $c->text</h3>";
            }
        }

        return $html;
    }


}