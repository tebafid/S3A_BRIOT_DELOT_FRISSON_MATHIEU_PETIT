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

    /**
     * affiche la reservation de l'item
     * @return string
     */
    private function getHtmlReservation(){
        $refReserver = $this->container->router->pathFor('reservation', ['token' => $this->data['token'], 'id' => $this->data['item']->id]);
        if(!isset($_SESSION['iduser'])){
            if(isset($_COOKIE['nom']))
                $p = $_COOKIE['nom'];
            else
                $p = '';
            $name = "<label>Nom :</label><input type='text' name='nom' value='{$p}' required/>";
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

    /**
     * affiche item deja reservé
     * @return string
     */
    private function getHtmlItemDejaReserve(){
        $html = "Item deja reservé";
        return $html;
    }

    /**
     * affiche l'ajout d'un item
     * @return string
     */
    private function getHtmlAjoutItem(){
        $refAjout = $this->container->router->pathFor('ajoutItem', ['tokenModif' => Liste::all()->where('no', '=', $this->data->no)->first()->tokenModif, 'id' => $this->data->no] );
        $html = "<h1>Ajout d'un item à la liste {$this->data->titre}</h1>";
        $html .= <<<END
<form method="POST" action="$refAjout" enctype="multipart/form-data">
<div>
	<label>Nom :</label>
	<input type="text" name="nom" required/>
</div>
<div>
	<label>Description :</label>
	<input type="text" name="descr"/>
</div>
<div>
	<label>Url :</label>
	<input type="text" name="url"/>
</div>
<div>
	<label>Prix :</label>
	<input type="text" name="prix" required/>
</div>
<div>
	<label>Image :</label>
	<input type="file" name="fileToUpload" id="fileToUpload"/>
</div>
<div>
	<button class="button" type="submit">Ajouter</button>
</div>
	
</form>
END;
        return $html;
    }

    private function getHtmlModificationItem(){
        $refModif = $this->container->router->pathFor('modificationItem', ['tokenModif' => Liste::all()->where('no', '=', $this->data->liste_id)->first()->tokenModif, 'id' => $this->data->id]);
        $html = <<<END
<form method="post" action="$refModif" enctype="multipart/form-data">
<div>
	<label>Nom :</label>
	<input type="text" name="nom" value="{$this->data->nom}" required/>
</div>
<div>
	<label>Description :</label>
	<input type="text" name="descr" value="{$this->data->description}"/>
</div>
<div>
	<label>Url :</label>
	<input type="text" name="url" value="{$this->data->url}"/>
</div>
<div>
	<label>Prix :</label>
	<input type="text" name="prix" value="{$this->data->tarif}" required/>
</div>
<div>
	<label>Image :</label>
	<input type="file" name="fileToUpload" id="fileToUpload"/>
</div>
<div>
	<button class="button" type="submit">Modifier</button>
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
            case 3: // affichage form modif item
                MainVue::$content = $this->getHtmlModificationItem();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }

}