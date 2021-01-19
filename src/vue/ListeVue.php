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

    /**
     * affiche les listes publiques
     * @return string
     */
    private function getHtmlListesPubliques(){
        $html = '<h1>Listes publiques :</h1>';
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

    /**
     * affiche listes utilisateur
     * @return string
     */
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

    /**
     * affiche l'ensemble des listes
     * @return string
     */
    private function getHtmlListe(){
        $lienActuel = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlDePartage = str_replace($this->data->tokenModif , $this->data->token, $lienActuel);
        $html = <<<END
<h1>{$this->data->titre}</h1>
<h3>{$this->data->description}</h3>
<h3>Expiration : {$this->data->expiration}</h3>
<h3>Url de partage : {$urlDePartage}</h3>
<table class='styled-table' style="border-collapse: collapse"><thead>
<tr>
    <th></th>
    <th>Nom</th>
    <th>Description</th>
    <th>Url</th>
    <th>Prix</th>
    <th>Etat de reservation</th>
</tr>
</thead><tbody>
END;
        $items = Item::all()->where('liste_id', '=', $this->data->no);
        $racine = $this->container->router->pathFor('accueil');
        if (count($items) != 0) {
            foreach ($items as $item) {
                if ($item->img != null && file_exists(dirname(__FILE__) . "/../../web/img/{$item->img}")) {
                    $img = $racine . "/../web/img/{$item->img}";
                } else {
                    $img = $racine . "/../web/img/base-img.png";
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
<td><div>{$item->nom}</div></td> 
<td><div>{$item->descr}</div></td>
<td><div>{$item->url}</div></td>
<td><div>{$item->tarif}</div></td>
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
        $html .= "</tbody></table>";

        return $html;
    }

    /**
     * affiche le menu des listes pour un utilisateur connecté
     * @return string
     */
    private function getHtmlMenuListesUtilisateur(){
        $refMesListe = $this->container->router->pathFor('mesListes');
        $refCreationListe = $this->container->router->pathFor('formCreationListe');
        $refListeEnCours = $this->container->router->pathFor('mesListesNonExpire');
        $refListeExpire = $this->container->router->pathFor('mesListesExpire');

        $param1 = "href='$refMesListe'";
        $param2 = "href='$refListeEnCours'";
        $param3 = "href='$refListeExpire'";

        if(str_contains($_SERVER['REQUEST_URI'], 'mesListesNonExpire')){
            $param2 .= "class='active'";
        }else if(str_contains($_SERVER['REQUEST_URI'], 'mesListesExpire')){
            $param3 .= "class='active'";
        }else if(str_contains($_SERVER['REQUEST_URI'], 'mesListes')){
            $param1 .= "class='active'";
        }

            $html = <<<END
<div class="menu">
  <a $param1>Mes listes</a>
  <a $param2>Listes en cours</a>
  <a $param3>Listes expirées</a>
  <a href="$refCreationListe">Créer une liste</a>
</div>
END;
        return $html;
    }

    /**
     * affiche la création de liste
     * @return string
     */
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

    /**
     * affiche l'information sur la liste
     * @return string
     */
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

    /**
     * affiche la liste modifiable
     * @return string
     */
    private function getHtmlListeModif(){
        $lienActuel = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlDePartage = str_replace($this->data->tokenModif , $this->data->token, $lienActuel);

        $html = "<h1>{$this->data->titre}</h1>";
        $html .= "<h3>{$this->data->description}</h3>";
        $html .= "<h3>Expiration : {$this->data->expiration}</h3>";
        $html .= "<h3>Url de partage : {$urlDePartage}</h3>";
        $html .= <<<END
<table class='styled-table'><thead>
<tr>
    <th></th>
    <th>Nom</th>
    <th>Description</th>
    <th>Url</th>
    <th>Prix</th>
    <th>Etat de reservation</th>
    <th>Actions</th>
</tr>
</thead><tbody>
END;
        $items = Item::all()->where('liste_id', '=', $this->data->no);
        $racine = $this->container->router->pathFor('accueil');
        if (count($items) != 0) {
            foreach ($items as $item) {
                $refModifItem = $this->container->router->pathFor('formModificationItem', ['tokenModif' => $this->data->tokenModif, 'id' => $item->id]);
                $refSupprItem = $this->container->router->pathFor('supprimerItem', ['tokenModif' => $this->data->tokenModif, 'id' => $item->id]);

                if($item->img != null && file_exists(dirname(__FILE__) . "/../../web/img/{$item->img}")) {
                    $img = $racine . "/../web/img/{$item->img}";
                } else {
                    $img = $racine . "/../web/img/base-img.png";
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
<td><div>{$item->nom}</div></td> 
<td><div>{$item->descr}</div></td>
<td><div>{$item->url}</div></td>
<td><div>{$item->tarif}</div></td>
<td><div>{$reserv}</div></td>
<td style="text-align: center">
    <div style="display: inline-block"><a href='$refModifItem'><i class='fa fa-edit'></i></a></div>
    <div style="display: inline-block"><a href='$refSupprItem'><i class='fa fa-trash'></i></a></div>
</td>
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
<td><div></div></td>
</tr>
END;
        }
        $html .= "</tbody></table>";
        if ($this->data->expiration >= date("Y-m-d")) {
            $refAjoutItem = $this->container->router->pathFor('formAjoutItem', ['tokenModif' => $this->data->tokenModif, 'id' => $this->data->no]);
            $refModifListe = $this->container->router->pathFor('formModificationListe', ['tokenModif' => $this->data->tokenModif]);
            $refSupprListe = $this->container->router->pathFor('supprimerListe', ['tokenModif' => $this->data->tokenModif]);

            $html .= <<<END
<div style="margin-bottom: 20px;">
    <a class='button' href='$refAjoutItem'>Ajouter un item</a>
    <a class='button' href='$refModifListe'>Modifier la liste</a>
    <a class='button rouge' href='$refSupprListe'>Supprimer la liste</a>
</div>
END;
        }else {
            $refSupprListe = $this->container->router->pathFor('supprimerListe', ['tokenModif' => $this->data->tokenModif]);
            $html .= "<div><a class='button' href='$refSupprListe'>Supprimer la liste</a></div>";
        }

        return $html;
    }

    /**
     * affiche la modification de liste
     * @return string
     */
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

    /**
     * affiche l'ajout de commentaire
     * @return string
     */
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

    /**
     * affiche les commentaires
     * @return string
     */
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
                MainVue::$content = $this->getHtmlListesPubliques();
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
                MainVue::$content = $this->getHtmlMenuListesUtilisateur() . $this->getHtmlMesListe();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }
}