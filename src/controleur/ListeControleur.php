<?php


namespace wishlist\controleur;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use wishlist\modele\Item;
use wishlist\modele\Liste;
use wishlist\modele\Participant;
use wishlist\modele\Commentaire;
use wishlist\vue\ListeVue;
use wishlist\vue\UtilisateurVue;

class ListeControleur
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * utilise la vue pour afficher les listes publique
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherlistesPubliques(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);
        $listes = Liste::all()->where('public', '=', '1')->where('expiration', '>=', date("Y-m-d"))->sortBy('expiration');

        $vue->setData($listes);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

    /**
     * utilise la vue pour afficher les listes
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherliste(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);
        if($liste = Liste::all()->where('token', '=', $args['token'])->count() > 0){
            $liste = Liste::all()->where('token', '=', $args['token'])->first();
            $vue->setData($liste);
            $rs->getBody()->write($vue->render(1));
        }else if($liste = Liste::all()->where('tokenModif', '=', $args['token'])->count() > 0){
            $liste = Liste::all()->where('tokenModif', '=', $args['token'])->first();
            $vue->setData($liste);
            $rs->getBody()->write($vue->render(5));
        }

        return $rs;
    }

    /**
     * utilise la vue pour afficher les listes de l'utilisateur
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function afficherMesListes(Request $rq, Response $rs, $args): Response{
        if(!isset($_SESSION['iduser'])){
            $vue = new UtilisateurVue();
            $rs->getBody()->write($vue->render(3)); // afficher connexion
            return $rs;
        }

        $vue = new ListeVue($this->container);

        $listes = Liste::all()->where('user_id', '=', $_SESSION['iduser'])->sortBy('expiration');
        $vue->setData($listes);
        $rs->getBody()->write($vue->render(7));
        return $rs;
    }

    /**
     * utilise la vue pour afficher la création de liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function creationListe(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);

        $rs->getBody()->write($vue->render(2)); // form creation liste
        return $rs;
    }

    /**
     * utilise la vue pour créer la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function creerListe(Request $rq, Response $rs, $args): Response
    {
        $post = $rq->getParsedBody();

        $liste = new Liste();
        $liste->titre = filter_var ($post['titre'],FILTER_SANITIZE_STRING);
        $liste->description = filter_var ($post['description'], FILTER_SANITIZE_STRING);
        $liste->expiration = filter_var ($post['date'],FILTER_SANITIZE_STRING);
        $liste->public = $post['public'] == "yes";
        $token = $this->creerToken();
        $tokenModif = $this->creerToken();

        while ($token == $tokenModif)
            $tokenModif = $this->creerToken();

        $liste->token = $token;
        $liste->tokenModif = $tokenModif;

        if(isset($_SESSION['iduser'])){
            $user_id = $_SESSION['iduser'];
            $url = $this->container->router->pathFor('liste', ['token' => $token]);
        }else{
            $user_id = null;
            $url = $this->container->router->pathFor('infoListe', ['tokenModif' => $tokenModif]);
        }
        $liste->user_id = $user_id;
        $liste->save();

        $no = $liste->no;
        $timeExp = strtotime($liste->expiration);
        setcookie("listes[$no]", $liste->tokenModif, $timeExp + 60*60*24, '/');

        return $rs->withRedirect($url);
    }

    /**
     * crée un token : id de la liste
     * @return string
     * @throws \Exception
     */
    private function creerToken(){
        $token = bin2hex(random_bytes(10));
        while(Liste::all()->where('token', '=', $token)->count() > 0 && Liste::all()->where('tokenModif', '=', $token)->count() > 0){
            $token = bin2hex(random_bytes(10));
        }
        return $token;
    }

    /**
     * donne les informations sur la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function donneInfoListe(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);
        $liste = Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first();

        if($liste != null){
            $vue->setData($liste);
            $rs->getBody()->write($vue->render(4));
        }
        return $rs;
    }

    /**
     * supprime une liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     * @throws \Exception
     */
    public function supprimerListe(Request $rq, Response $rs, $args): Response
    {
        $liste = Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first();
        $items = Item::all()->where('liste_id', '=', $liste->num);
        foreach ($items as $item){
            $part = Participant::all()->where('item_id', '=', $item->id)->first();
            if($part != null) $part->delete();
            $item->delete();
        }
        $liste->delete();
        $refMenu = $this->container->router->pathFor('accueil');
        return $rs->withRedirect($refMenu);
    }

    /**
     * utilise la vue pour modifie la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modificationListe(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);
        $liste = Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first();

        $vue->setData($liste);
        $rs->getBody()->write($vue->render(6));
        return $rs;
    }

    /**
     * modifie la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function modifierListe(Request $rq, Response $rs, $args): Response
    {
        $post = $rq->getParsedBody();
        $liste = Liste::all()->where('tokenModif', '=', $args['tokenModif'])->first();
        $liste->titre = filter_var($post['titre'], FILTER_SANITIZE_STRING);
        $liste->description = filter_var($post['description'], FILTER_SANITIZE_STRING);
        $liste->expiration = filter_var($post['date'], FILTER_SANITIZE_STRING);
        $liste->public = filter_var($post['public'], FILTER_SANITIZE_STRING) == 'checked';
        $liste->save();

        $refListe = $this->container->router->pathFor('liste', ['token' => $liste->tokenModif]);
        return $rs->withRedirect($refListe);
    }

    /**
     * ajoute un commentaire a la liste
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    public function ajouterCommentaire(Request $rq, Response $rs, $args): Response
    {
        $post = $rq->getParsedBody();

        $commentaire = new Commentaire();
        if(!isset($_SESSION['iduser'])){
            setcookie('nom', filter_var($post['nom'], FILTER_SANITIZE_STRING), time() + 60*60*24*7, '/');
            $commentaire->nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        }else{
            $commentaire->user_id = $_SESSION['iduser'];
        }
        if(Liste::all()->where('token', '=', $args['token'])->count() > 0)
            $id = Liste::all()->where('token', '=', $args['token'])->first()->no;
        else
            $id = Liste::all()->where('tokenModif', '=', $args['token'])->first()->no;


        $commentaire->liste_id = $id;
        $commentaire->message = filter_var($post['message'], FILTER_SANITIZE_STRING);
        $commentaire->save();

        return $rs->withRedirect($this->container->router->pathFor('liste', ['token' => $args['token']]));

    }

}