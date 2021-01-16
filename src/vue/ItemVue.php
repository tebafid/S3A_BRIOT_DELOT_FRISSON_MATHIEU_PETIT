<?php


namespace wishlist\vue;

use wishlist\modele\Liste;

class ItemVue extends MainVue
{
    private $data;

    public function __construct($c){
        $this->container = $c;
    }

    public function setData($d){
        $this->data = $d;
    }

    private function getHtmlReservation(){
        $refReserver = $this->container->router->pathFor('reservation', ['token' => $this->data['token'], 'id' => $this->data['item']->id]);
        if(!isset($_SESSION['iduser'])){
            $name = "<label>Nom :</label><input type='text' name='nom' required/>";
        }else{
            $name = "";
        }

        $html = <<<FIN
<form method="POST" action="$refReserver">
<div>
    $name
</div>
<div>
    <label>Commentaire :</label>
    <input type="text" name="commentaire"/>
</div>
<div>
    <button class="button" type="submit">Réserver</button>
</div>
</form>
FIN;
        return $html;
    }

    private function getHtmlItemDejaReserve(){
        $html = "Item deja reservé";
        return $html;
    }

    private function getHtmlAjoutItem(){ //  a revoir
        $refAjout = $this->container->router->pathFor('ajoutItem', ['tokenModif' => Liste::all()->where('no', '=', $this->data->no)->first()->tokenModif, 'id' => $this->data->no] );
        $html = "<h1>Ajout d'un item à la liste {$this->data->titre}</h1>";
        $html .= <<<END
<form method="POST" action="$refAjout">
<div>
	<label>Nom :</label>
	<input type="text" name="nom" required/>
</div>
<div>
	<label>Description :</label>
	<input type="text" name="descr"/>
</div>
<div>
	<label>Tarif :</label>
	<input type="text" name="tarif" required/>
</div>
<div>
	<label>Url :</label>
	<input type="text" name="url"/>
</div>
<div>
	<button class="button" type="submit">Ajouter</button>
</div>
	
</form>
END;
        return $html;
    }

    public function render(int $i) : String {
        switch ($i){
            case 0: // affichage form reservation
                MainVue::$content = $this->getHtmlReservation();
                break;
            case 1: // affichage form reservation non valide
                MainVue::$content = $this->getHtmlItemDejaReserve();
                break;
            case 2: // affichage form ajout item
                MainVue::$content = $this->getHtmlAjoutItem();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }

}