<?php


namespace wishlist\vue;

use wishlist\modele\Commentaire;
use wishlist\modele\Item;
use wishlist\modele\Liste;
use wishlist\modele\Participant;
use wishlist\modele\Utilisateur;

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
                $refListe = $this->container->router->pathFor('liste', ['token' => $liste->token]);
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

    private function getHtmlMesListe(){
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
                $refListe = $this->container->router->pathFor('liste', ['token' => $liste->tokenModif]);
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
        $lienActuel = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlDePartage = str_replace($this->data->tokenModif , $this->data->token, $lienActuel);
        $html = <<<END
<h1>Liste : {$this->data->titre}</h1>
<h3>Description : {$this->data->description}</h3>
<h3>Url de partage : {$urlDePartage}</h3>
<table class='styled-table' style="border-collapse: collapse"><thead>
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
                if ($item->reservation == 0) {
                    if(isset($_COOKIE['listes']) && isset($_COOKIE['listes'][$this->data->no])){
                        $reserv = "<p>Non réservé</p>";
                    }
                    else if(isset($_SESSION['iduser']) && $_SESSION['iduser'] == $this->data->no){
                        $reserv = "<p>Non réservé</p>";
                    }
                    else if($this->data->expiration >= date('Y-m-d')){
                        $refReservation = $this->container->router->pathFor('formReservation', ['token' => $this->data->token, 'id' => $item->id]);
                        $reserv = "<a class='reservButton' href='$refReservation'>Reserver</a>";
                    }else{
                        $reserv = "<p>Pas de réservation</p>";
                    }
                }else {
                    if($this->data->expiration < date('Y-m-d')){
                        $p = Participant::all()->where('item_id', '=', $item->id)->first();
                        $reserv = "<pre> Réservé par " . $p->nom . "</pre>";
                    }
                    else if(isset($_COOKIE['listes']) && isset($_COOKIE['listes'][$this->data->no])){
                        $reserv = "<p>Réservé</p>";
                    }else{
                        $p = Participant::all()->where('item_id', '=', $item->id)->first();
                        $reserv = "<pre> Réservé par " . $p->nom . "</pre>";
                    }
                }
                $html .= <<<END
<tr>
<td><div style='height:80px; width: 80px;'><img style='height:100%; width: 100%;' src='$img'></div></td>
<td><div>{$item['nom']}</div></td> 
<td><div>{$item['descr']}</div></td>
<td><div>{$item['url']}</div></td>
<td><div>{$item['tarif']}</div></td>
<td><div>{$reserv}</div></td>
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

    private function getHtmlInfoListe(){
        $refListeModif = $this->container->router->pathFor('liste', ['token' => $this->data->tokenModif]);

        $lienActuel = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlModif = str_replace('infoListe', 'liste', $lienActuel);
        $urlPartage = str_replace($this->data->tokenModif, $this->data->token, $urlModif);

        $html = <<<END
<div>
    <h1>Liste créé</h1>
</div>
<div>
    Votre liste a bien été crée, dorénavant utilisez ces urls afin de modifier ou de partager votre liste
</div>
<br/>
<div>
    <h3>url de modification :</h3> {$urlModif}
</div>
<br/>
<div>
    <h3>url de partage :</h3> {$urlPartage}
</div>
<br/><br/>
<div>
    <a class='button' href='$refListeModif'>Continuer</a>
</div>
END;

        return $html;
    }

    private function getHtmlListeModif(){
        $lienActuel = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlDePartage = str_replace($this->data->tokenModif , $this->data->token, $lienActuel);

        $html = "<h1>Liste : {$this->data->titre}</h1>";
        $html .= "<h3>Description : {$this->data->description}</h3>";
        $html .= "<h3>Url de partage : {$urlDePartage}</h3>";
        $html .= <<<END
<table class='styled-table'><thead>
<tr>
    <th>Image</th>
    <th>Item</th>
    <th>Description</th>
    <th>Url</th>
    <th>Tarif</th>
    <th>Etat de reservation</th>
    <th>Action</th>
</tr>
</thead><tbody>
END;
        $items = Item::all()->where('liste_id', '=', $this->data->no);
        if (count($items) != 0) {
            foreach ($items as $item) {
                //$url_modif = $this->container->router->pathFor('modifitem', ['tokenModif' => $this -> tab['tokenModif'], 'id' => $item['id']] );
                $refSupprItem = $this->container->router->pathFor('supprimerItem', ['tokenModif' => $this->data->tokenModif, 'id' => $item->id]);

                if(file_exists("../uploads/{$item->img}")) {
                    $img = "../uploads/{$item->img}";
                } else {
                    $img = "../uploads/base.png";
                }
                if ($item->reservation == 0) {
                    if(isset($_COOKIE['listes']) && isset($_COOKIE['listes'][$this->data->no])){
                        $reserv = "<p>Non réservé</p>";
                    }
                    else if(isset($_SESSION['iduser']) && $_SESSION['iduser'] == $this->data->no){
                        $reserv = "<p>Non réservé</p>";
                    }
                    else if($this->data->expiration >= date('Y-m-d')){
                        $refReservation = $this->container->router->pathFor('formReservation', ['token' => $this->data->token, 'id' => $item->id]);
                        $reserv = "<a class='reservButton' href='$refReservation'>Reserver</a>";
                    }else{
                        $reserv = "<p>Pas de réservation</p>";
                    }
                } else {
                    if($this->data->expiration < date('Y-m-d')){
                        $p = Participant::all()->where('item_id', '=', $item->id)->first();
                        $reserv = "<pre> Réservé par " . $p->nom . "</pre>";
                    }
                    else if(isset($_COOKIE['listes']) && $_COOKIE['listes'][$this->data->no]){
                        $reserv = "<p>Réservé</p>";
                    }else{
                        $p = Participant::all()->where('item_id', '=', $item->id)->first();
                        $reserv = "<pre> Réservé par " . $p->nom . "</pre>";
                    }
                }

                $html .= <<<END
<tr>
<td><div style='height:80px; width: 80px;'><img style='height:100%; width: 100%;' src='$img'></div></td>
<td><div>{$item['nom']}</div></td> 
<td><div>{$item['descr']}</div></td>
<td><div>{$item['url']}</div></td>
<td><div>{$item['tarif']}</div></td>
<td><div>{$reserv}</div></td>
<td><a href='$refSupprItem'><i class='fa fa-trash'></i></a></td>
</tr>
END;
            }//<td><a href='$url_modif'><i class='fa fa-edit'></i></a>
        } else {
            $html .= <<<END
<tr>
<td><div>Vide</div></td>
<td><div></div></td> 
<td><div></div></td>
<td><div></div></td>
<td><div></div></td>
<td><div></div></td>
<td><div></div></td>
</tr>
END;
        }
        $html .= "</tbody></table>";
        if ($this->data->expiration >= date("Y-m-d")) {
            $refAjoutItem = $this->container->router->pathFor('formAjoutItem', ['tokenModif' => $this->data->tokenModif, 'id' => $this->data->no]);
            $refModifListe = $this->container->router->pathFor('formModificationListe', ['tokenModif' => $this->data->tokenModif]);
            $refSupprListe = $this->container->router->pathFor('supprimerListe', ['tokenModif' => $this->data->tokenModif]);

            $html .= "<a class='button' href='$refAjoutItem'>Ajouter un item</a>
                      <a class='button' href='$refModifListe'>Modifier la liste</a>
                      <a class='button red' href='$refSupprListe'>Supprimer la liste</a>";
        }else {
            $refSupprListe = $this->container->router->pathFor('supprimerListe', ['tokenModif' => $this->data->tokenModif]);
            $html .= "<a class='button' href='$refSupprListe'>Supprimer la liste</a>";
        }

        return $html;
    }

    private function getHtmlModificationListe(){
        $refModif = $this->container->router->pathFor('modificationListe', ['tokenModif' => $this->data->tokenModif]);

        if($this->data->public == 1)
            $check = "<label>public ?</label><input type='checkbox' name='public' value='checked' checked/>";
        else
            $check = "<label>public ?</label><input type='checkbox' name='public' value='checked'/>";

        $html = <<<END
<div>
    <h1>Modification de la liste</h1>
</div>
<form method="POST" action="$refModif">
<div>
    <label>Titre :</label>
    <input type="text" name="titre" value="{$this->data->titre}"/>
</div>
<div>
    <label>Description :</label>
    <input type="text" name="description" value="{$this->data->description}"/>
</div>
<div>
    <label>Date d'expiration</label>
    <input type="date" name="date" value="{$this->data->expiration}"/>
</div>
<div>
    $check
</div>
<div>
    <button class="button" type="submit">Modifier liste</button>
</div>
</form>

END;
        return $html;
    }

    private function getHtmlAjoutCommentaire(){
        $refAjout = $this->container->router->pathFor('ajouterCommentaire', ['token' => $this->data->token]);
        if(isset($_SESSION['iduser'])){
            $nomField = "<label style='display: inline-block; width: 15%'>" . Utilisateur::all()->where('id', '=', $_SESSION['iduser'])->first()->prenom . " :</label>";
        }else if(isset($_COOKIE['nom'])){
            $nomField = "<input type='text' name='nom' value='{$_COOKIE['nom']}' placeholder='nom' style='width: 15%' required/>";
        }else{
            $nomField = "<input type='text' name='nom' placeholder='nom' style='width: 15%' required/>";
        }
        $html = <<<END
<hr/>
<form method="post" action="$refAjout">
<div style="white-space:nowrap">
    $nomField
    <input type="text" name="message" placeholder="message" style="width: 85%" required/>
</div>
<div style="text-align: right">
    <button class="button" type="submit" style="width: 20%; margin-top: 0px; padding:5px">Envoyer</button>
</div>
</form>
END;
        return $html;
    }

    private function getHtmlCommentaire(){
        $commentaires = Commentaire::all()->where('liste_id', '=', $this->data->no);
        $html = "<hr>";
        if($commentaires->count() == 0){
            $html .= "Aucun commentaire";
        }

        foreach ($commentaires as $commentaire){
            if($commentaire->user_id != null){
                $name = Utilisateur::all()->where('id', '=', $commentaire->user_id)->first()->prenom;
            }else{
                $name = $commentaire->nom;
            }
            $html .= <<<END
<div>
    $name : $commentaire->message
</div>
END;
        }
        return $html;
    }

    public function render(int $i) : String {
        switch ($i){
            case 0: // affichage listes publiques
                MainVue::$content = $this->getHtmlMenuListesPubliques() . $this->getHtmlListesPubliques();
                break;
            case 1: // affichage liste
                MainVue::$content = $this->getHtmlListe() . $this->getHtmlAjoutCommentaire() . $this->getHtmlCommentaire();
                break;
            case 2: // form creation liste
                MainVue::$content = $this->getHtmlCreationListe();
                break;
            case 3: // creation liste
                MainVue::$content = $this->getHtmlCreationListe();
                break;
            case 4: // infoListe
                MainVue::$content = $this->getHtmlInfoListe();
                break;
            case 5: // affichage liste avec modif
                MainVue::$content = $this->getHtmlListeModif() . $this->getHtmlAjoutCommentaire() . $this->getHtmlCommentaire();
                break;
            case 6: // affichage le menu de modification
                MainVue::$content = $this->getHtmlModificationListe();
                break;
            case 7: // affichage des listes de l'utilisateur
                MainVue::$content = $this->getHtmlMesListe();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }
}