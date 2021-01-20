<?php


namespace wishlist\controleur;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use wishlist\modele\Item;
use wishlist\modele\Utilisateur;
use wishlist\modele\Participant;
use wishlist\modele\Liste;
use wishlist\vue\ItemVue;

class ItemControleur
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * ajoute un item a une liste : endroit pour entrer les infos
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajouterItem(Request $rq, Response $rs, $args): Response
    {
        $vue = new ItemVue($this->container);
        $liste = Liste::all()->where('tokenModif', '=', $args['tokenModif'])->where('no', '=', $args['id'])->first();
        $vue->setData($liste);
        $rs->getBody()->write($vue->render(2));
        return $rs;
    }

    /**
     * ajoute un item a une liste a partir des infos
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajoutItem(Request $rq, Response $rs, $args): Response
    {
        if(Liste::all()->where('tokenModif', '=', $args['tokenModif'])->where('no', '=', $args['id'])->count() == 0) return $rs;

        $post = $rq->getParsedBody();
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $descr = filter_var($post['descr'], FILTER_SANITIZE_STRING);
        $prix = filter_var($post['prix'], FILTER_SANITIZE_STRING);
        $url = filter_var($post['url'], FILTER_SANITIZE_STRING);

        if($_FILES["fileToUpload"]["name"] != ''){
            // source : https://www.w3schools.com/php/php_file_upload.asp
            $target_dir = dirname(__FILE__) . '/../../web/img/';
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if(!$check) {
                echo "Ce fichier n'est pas une image.";
                return $rs;
            }

// Check file size 10Mb
            if ($_FILES["fileToUpload"]["size"] > 10000000) {
                echo "Cette image est trop grosse.";
                return $rs;
            }

// Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                echo "Désolé, seulement les JPG, JPEG, PNG & GIF sont accepté.";
                return $rs;
            }

            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                return $rs;
            }

            $img = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
        }else{
            $img = null;
        }

        $item = new Item();
        $item->liste_id = $args['id'];
        $item->nom = $nom;
        $item->descr = $descr;
        $item->tarif = $prix;
        $item->url = $url;
        $item->img = $img;
        $item->save();

        $refListe = $this->container->router->pathFor('liste', ['token' => $args['tokenModif']]);
        return $rs->withRedirect($refListe);
    }

    /**
     * reservation : endroit pour écrire les informations
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function reservation(Request $rq, Response $rs, $args): Response
    {
        $item = Item::all()->where('id', '=', $args['id'])->first();
        $vue = new ItemVue($this->container);

        if($item->reservation == 1){
            $rs->getBody()->write($vue->render(1));
        }else{
            $vue->setData(['item' => $item, 'token' => $args['token']]);
            $rs->getBody()->write($vue->render(0));
        }
        return $rs;
    }

    /**
     * reserve a partir des informations
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function reserver(Request $rq, Response $rs, $args): Response
    {
        $item = Item::all()->where('id', '=', $args['id'])->first();
        if($item->reservation == 1){
            $vue = new ItemVue($this->container);
            $rs->getBody()->write($vue->render(1));
            return $rs;
        }

        $post = $rq->getParsedBody();
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $commentaire = filter_var($post['commentaire'], FILTER_SANITIZE_STRING);

        $participant = new Participant();
        $participant->item_id = $args['id'];
        if(isset($_SESSION['iduser'])){
            $participant->user_id = $_SESSION['iduser'];
            $participant->nom = Utilisateur::all()->where('id', '=', $_SESSION['iduser'])->first()->nom;
        }else{
            $participant->nom = $nom;
            setcookie('nom', filter_var($post['nom'], FILTER_SANITIZE_STRING), time() + 60*60*24*7, '/');
        }
        $participant->message = $commentaire;
        $participant->save();



        $item->reservation = 1;
        $item->save();

        $refAffichageListe = $this->container->router->pathFor('liste', ['token' => $args['token']]);
        return $rs->withRedirect($refAffichageListe);
    }

    /**
     * supprime un item d'une liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function supprimerItem(Request $rq, Response $rs, $args): Response{
        $item = Item::all()->where('id', '=', $args['id'])->first();

        if(Liste::all()->where('no', '=', $item->liste_id)->first()->no != Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first()->no)
            return $rs; // ne peut pas suppr un item sans le bon token de modif

        if($item->reserve == 1){
            return $rs; // ne peut pas suppr un item reservé
        }else{
            $item->delete();
            $ref = $this->container->router->pathFor('liste', ['token' => $args['tokenModif']]);
        }
        return $rs->withRedirect($ref);
    }

    /**
     * Permet d'afficher le formulaire de la modification d'item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modificationItem(Request $rq, Response $rs, $args): Response{
        $item = Item::all()->where('id', '=', $args['id'])->first();

        if(Liste::all()->where('no', '=', $item->liste_id)->first()->no != Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first()->no)
            return $rs; // ne peut pas modifier un item sans le bon token de modif

        if($item->reserve == 1){
            return $rs; // ne peut pas modifier un item reservé
        }else{
            $vue = new ItemVue($this->container);
            $vue->setData($item);
            $rs->getBody()->write($vue->render(3));
        }
        return $rs;
    }

    /**
     * Modifie l'item
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modifierItem(Request $rq, Response $rs, $args): Response{
        $post = $rq->getParsedBody();
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($post['descr'], FILTER_SANITIZE_STRING);
        $prix = filter_var($post['prix'], FILTER_SANITIZE_STRING);
        $url = filter_var($post['url'], FILTER_SANITIZE_STRING);

        if($_FILES["fileToUpload"]["name"] != ''){
            // source : https://www.w3schools.com/php/php_file_upload.asp
            $target_dir = dirname(__FILE__) . '/../../web/img/';
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if(!$check) {
                echo "Ce fichier n'est pas une image.";
                return $rs;
            }

// Check file size 10Mb
            if ($_FILES["fileToUpload"]["size"] > 10000000) {
                echo "Cette image est trop grosse.";
                return $rs;
            }

// Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                echo "Désolé, seulement les JPG, JPEG, PNG & GIF sont accepté.";
                return $rs;
            }

            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                return $rs;
            }

            $img = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
        }else{
            $img = null;
        }

        $item = Item::all()->where('id', '=', $args['id'])->first();
        $item->nom = $nom;
        $item->descr = $description;
        $item->url = $url;
        $item->tarif = $prix;
        $item->img = $img;
        $item->save();

        $refListe = $this->container->router->pathFor('liste', ['token' => $args['tokenModif']]);
        return $rs->withRedirect($refListe);
    }

}