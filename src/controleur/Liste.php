<?php


namespace wishlist\controleur;

use wishlist\modele\Liste as Lst;
use wishlist\vue\Liste as VLst;
use wishlist\modele\Utilisateur as Uti;

class Liste
{

    protected $user;

    public function __construct(){
        $this -> user = uti::getCurrentUser();
    }

    // Methode permettant de choisir la liste a afficher
    public function choixListe(){
        $liste = Lst::where('no','=',$this -> user) -> get();
        $aff = new VLst(DEMANDEUR,$liste);
        $aff -> afficherListe($liste);
        echo $aff -> render();
    }

    // Methode permettant de creer une nouvelle liste
    public function nouvelleListe(){
        $a = new VLst(DEMANDEUR,null);
        $a -> creerListe();
        echo $a -> render();
    }

    /*
    // Methode permettant de creer une nouvelle liste
    public function nouvelleListe(){
        $a = new VLst();    
        $user = AccountController::getCurrentUser();
        // Verification des données envoyées
        if ( !AccountController::isConnected())
        $a->notConnectedError();

    // Transcrit la date reçue
        $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
        if ($expiration == null)
        $a->error("date incorrecte");
        if(time() - strtotime($expiration) >= 0)
        $a->error("Vous ne pouvez sélectionner une date passée.");

    // Crée la nouvelle liste
        $wishlist = new WishList();
        $wishlist->user_id = $user->id_account;
        $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
        $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
        $wishlist->expiration = $expiration;
        $wishlist->public =  false;
        $wishlist->token = stripslashes (crypt(
        $_POST['list_title'] . $_POST['list_descr'] . $_POST['list_expiration'],
        $_SESSION['user_login'] . "sel de mer"
        ));

        try {
            if ($wishlist->save()) {
                $a->addHeadMessage("Votre liste a bien été créée", 'good');
                $a->creerListe($wishlist,$user);
            } else {
                $a->addHeadMessage("Votre liste n'a pu être créée", 'bad');
                $this->getFormList(null);
            }
        echo $a -> render();    
        }
        catch (QueryException $e) {
        $vue->addHeadMessage("Une erreur est survenue à la sauvegarde...", "bad");
        }
    }
    */
   
    /*
    *Methode permettant de modifier la liste avec les données passées en post
    *@param $id l'id de la liste
    *@param $token le token de la liste
    */
    /*
    public function editerListe($id, $token) {
        $view = new ListView();
        $user = AccountController::getCurrentUser();
        // Vérifie les données envoyées
        if ( !AccountController::isConnected() )
        $view->notConnectedError();
    
        $wishlist = WishList::where('no','=',$id)->where('token','=',$token)->first();
        if (!isset($wishlist) || $wishlist->user_id != $user->id_account && $user->admin == false){
          $view->addHeadMessage("Vous ne pouvez pas modifier cette liste", 'bad');
          $view->renderList($wishlist,$user);
          return;
        }
    
        // Transcrit la date reçue
        $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
        if ($expiration == null) $view->error("date incorrecte");
    
        // Crée la nouvelle liste
    
        if ($wishlist == null) $view->error('cette liste n\'existe pas');
    
        $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
        $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
        $wishlist->expiration = $expiration;
        $wishlist->public = isset($_POST['list_public']) ? true : false;
        try {
          if ($wishlist->save()) {
            $view->addHeadMessage("Votre liste a bien été modifiée", 'good');
            $view->renderList($wishlist,$user);
          } else {
            $view->addHeadMessage("Votre liste n'a pu être modifiée", 'bad');
            $this->getFormList(null);
          }
        }
        catch (QueryException $e) {
          $vue->addHeadMessage("Une erreur est survenue à la sauvegarde...", "bad");
        }
      }
      */


    //Methode permettant d'enregistrer une liste
    public function enregistrerListe(){
        $app = \Slim\Slim::getInstance();
        $titre = $app -> request -> post('titre');
        $liste = new Lst();
        $liste -> titre = filter_var($titre, FILTER_SANITIZE_STRING);
        $liste -> expiration = filter_var($app -> request -> post ('expire'), FILTER_SANITIZE_NUMBER_INT);
        $liste -> id = $this -> user;
        $liste -> token = dechex(random_int(0,0xFFFFFFF));
        $liste -> save();
        $this -> choisListe();
    }

    //Methode permettant d'afficher une liste
    public function afficherListe(string $name){
        $liste = Lst::where('token','=',$name) -> where('token', '=', $token) -> first();
        $user = AccountController::getCurrentUser();
        // Affiche la liste via la vue
        $aff = new VLst();
        $aff -> afficherListe($liste, $user);
        echo $aff -> render();

    }

    /**
    *Methode permettant d'afficher les listes existantes
    */
    /*
    public function dispAllList() {
        $view = new ListView();
        $publicLists = WishList::where('public','=', 1)->get();
        if (AccountController::isConnected()){
        $user = AccountController::getCurrentUser();
        if ($user->admin)
            $ownLists = WishList::select('*')->orderBy('expiration','ASC')->get();
        else
            $ownLists = WishList::where('user_id','=', $user->id_account)->orderBy('expiration','ASC')->get();
        $view->renderLists($publicLists, $ownLists);
        } else {
        $view->renderLists($publicLists, null);
        }
    }
    */

    /**
    *Methode permettant de supprimer une liste
    *@param $id l'id de la liste
    *@param $token le token de la liste
    */
    /*
    public function deleteList($id, $token) {
        $view = new ListView();
        $wishlist = Wishlist::where('no','=',$id)->where('token','=',$token)->first();
        if ($wishlist == null) $view->error("la liste n'existe pas");

        $user = AccountController::getCurrentUser();
        if ($wishlist->user_id != $user->id_account || $user->admin == 1){
        $view->addHeadMessage("Vous ne pouvez pas supprimer cette liste", 'bad');
        $view->renderList($wishlist,$user);
        }
        if ($wishlist->delete()) {
        $view->addHeadMessage("Votre liste a bien été supprimée", 'good');
        $this->dispAllList();
        }
        else {
        $view->error('impossible de supprimer la liste');
        }
    }
    */

    /**
    *Methode permettant d'afficher la liste via la vue
    *@param $id l'id de la liste
    *@param $token le token de la liste
    */
    /*
    public function getFormList($id, $token) {

        if (!isset($id)){
        $list = new WishList();
        }
        else{
        $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
        }
        $vue = new ListView();
        $vue->renderFormList($list);

    }
    */

    /**
    *Methode permettant de gèrer la disponibilité de la liste au public
    */
    /*
    public function dispPublicCreators() {
        $vue = new ListView();
        $creators = Account::whereIn(
        'id_account', WishList::select('user_id')->where('public','=',true)->get()->toArray()
        )->get();


        $vue->renderCreators($creators);
        }

    public function setListPublic($id, $token) {
        $vue = new ListView();
        $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
        $user = AccountController::getCurrentUser();

        if($list->public == false){
        $list->public=true;
        $vue->addHeadMessage(" Votre liste est devenue publique.", 'good');
        }
        else if($list->public==true)
        $vue->addHeadMessage(" Votre liste est déjà publique.", 'bad');

        $list->save();
        $vue->renderList($list, $user);
        }
    }
    */

    //ajouter un message sur une liste
}