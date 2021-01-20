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
            $name = "<input type='text' name='nom' value='{$p}' placeholder='nom' required/>";
        }else{
            $name = "";
        }

        $html = <<<FIN
<h1>Reservation d'objet :</h1>
<form method="POST" action="$refReserver">
<div class="form">
    $name
    <input type="text" name="commentaire" placeholder="commentaire"/>
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
<h1>Ajout d'objet : </h1>
<form method="POST" action="$refAjout" enctype="multipart/form-data">
<div class="form">
    <input type="text" name="nom" placeholder="nom" required/>
    <input type="text" name="descr" placeholder="description"/>
    <input type="text" name="url" placeholder="url"/>
    <input type="text" name="prix" placeholder="prix" required/>
    <div>
    	<label>Image :</label>
    	<input type="file" name="fileToUpload" id="fileToUpload"/>
    </div>
    <button class="button" type="submit">Ajouter</button>
</div>

	
</form>
END;
        return $html;
    }

    private function getHtmlModificationItem(){
        $refModif = $this->container->router->pathFor('modificationItem', ['tokenModif' => Liste::all()->where('no', '=', $this->data->liste_id)->first()->tokenModif, 'id' => $this->data->id]);
        $html = <<<END
<h1>Modification d'objet : </h1>
<form method="post" action="$refModif" enctype="multipart/form-data">
<div class="form">
	<input type="text" name="nom" value="{$this->data->nom}" placeholder="nom" required/>
	<input type="text" name="descr" value="{$this->data->description}" placeholder="description"/>
	<input type="text" name="url" value="{$this->data->url}" placeholder="url"/>
	<input type="text" name="prix" value="{$this->data->tarif}" placeholder="prix" required/>
    <div>
	    <label>Image :</label>
	    <input type="file" name="fileToUpload" id="fileToUpload"/>
    </div>
	<button class="button" type="submit">Modifier</button>
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