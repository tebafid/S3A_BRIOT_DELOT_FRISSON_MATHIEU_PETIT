<?php


namespace wishlist\vue;

use wishlist\modele\Item;
use wishlist\modele\Liste;

class ListeVue extends MainVue
{
    private $data;

    public function __construct($c){
        $this->container = $c;
    }

    public function setData($d){
        $this->data = $d;
    }

    private function getHtmlListesPubliques(){
        $html = '';
        if(sizeof($this->data) > 0){
            $html .= <<<END
<table class='styled-table' ><thead><tr>
    <th>Titre</th>
    <th>Description</th>
    <th>Date d'expiration</th>
</tr></thead>
<tbody>
END;
            foreach ($this->data as $liste) {
                $refListe = $this->container->router->pathFor('liste', ['token' => $liste['token']]);
                $html .= <<<END
<tr>
    <td><a href='$refListe'><div>{$liste['titre']}</div></a></td>
    <td><a href='$refListe'><div>{$liste['description']}</div></td>
    <td><a href='$refListe'><div>{$liste['expiration']}</div></td>
</tr>
END;
            }
            $html .= "</tbody></table>";
        }else{
            $html .= 'Vide';
        }
        return $html;
    }

    private function getHtmlListe(){
        $html = <<<END
<h1>Liste : {$this->data->titre}</h1>
<h3>Description : {$this->data->description}</h3>
<h3>Clé de partage : {$this->data->token}</h3>
<table class='styled-table' ><thead>
<tr>
    <th>Image</th>
    <th>Item</th>
    <th>Description</th>
    <th>Url</th>
    <th>Tarif</th>
    <th>Etat de reservation</th>
</tr>
</thead><tbody>
END;
        $items = Item::all()->where('liste_id', '=', $this->data->no);
        if (count($items) != 0) {
            foreach ($items as $item) {
                if (file_exists ( "../uploads/{$item->img}" )) {
                    $img = "../uploads/{$item->img}";
                } else {
                    $img = "../uploads/base.png";
                }
                if ($item['etat'] == 0) {
                    if(Liste::where ( "no", "=", $item->liste_id)->first()->expiration >= date("Y-m-d")){
                        //$url_additem = $this->container-> router->pathFor('reserver', ['token' => $this -> tab['token'], "id" => $item['id']] );
                        //$etat = "<a class='button red' href='$url_additem'>Reserver</a>";
                        $etat = 'test';
                    }else{
                        $etat = "<p>Pas de réservation</p>";
                    }
                } else {
                    $p = Participation ::where ( "id_item", "=", $item["id"] ) -> first ();
                    $etat = "<pre>Réserver par : " . $p -> nom . " <br>Commentaire : " . $p -> commentaire . "</pre>";
                }
                $html .= <<<END
<tr>
<td><div style='height:80px; width: 80px;'><img style='height:100%; width: 100%;' src='$img'></div></td>
<td><div>{$item['nom']}</div></td> 
<td><div>{$item['descr']}</div></td>
<td><div>{$item['url']}</div></td>
<td><div>{$item['tarif']}</div></td>
<td><div>{$etat}</div></td>
</tr>
END;
            }
        } else {
            $html .= <<<END
<tr>
<td><div>Vide</div></td>
<td><div></div></td> 
<td><div></div></td>
<td><div></div></td>
<td><div></div></td>
<td><div></div></td>
</tr>
END;
        }
        $html .= "</tbody></table>";/*
        $html .= $this -> ajouterCommentaire ();
        $html .= $this -> affichageCommentaire ();*/

        return $html;
    }

    private function getHtmlMenuListesPubliques(){
        $refCreationListe = $this->container->router->pathFor('formCreationListe');
        $html = <<<END
<div class="menu">
  <a class="active">Les listes publiques</a>
  <a href="">Les listes en cours</a>
  <a href="">Les listes expirées</a>
  <a href="$refCreationListe">Créer une liste</a>
</div>
END;
        return $html;

    }

    private function getHtmlCreationListe(){
        $date = date('Y-m-d');
        $refNouvelleListe = $this->container->router->pathFor('creationListe');

        $html = <<<FIN
<h1>Créer une liste</h1>
<form method="POST" action="$refNouvelleListe">
<div>
    <label>Titre :</label>
    <input type="text" name="titre" required/>
</div>
<div>
    <label>Description : </label>
    <input type="text" name="description" required/>
</div>
<div>
    <label>Date d'expiration : </label>
    <input type="date" name="date" value=$date min=$date required/>
</div>
<div>
    <label>Public ?</label>
    <input type='checkbox' name='public' value='yes'>
</div>
<div>
    <button class="button" type="submit">Enregistrer</button>
</div>
</form>	
FIN;
        return $html;

    }

    public function render(int $i) : String {
        switch ($i){
            case 0: // affichage listes publiques
                MainVue::$content = $this->getHtmlMenuListesPubliques() . $this->getHtmlListesPubliques();
                break;
            case 1: // affichage liste
                MainVue::$content = $this->getHtmlListe();
                break;
            case 2: // form creation liste
                MainVue::$content = $this->getHtmlCreationListe();
                break;
            case 3: // creation liste
                MainVue::$content = $this->getHtmlCreationListe();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }
}