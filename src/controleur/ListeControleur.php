<?php


namespace wishlist\controleur;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use wishlist\modele\Liste;
use wishlist\vue\ListeVue;

class ListeControleur
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function afficherlistesPubliques(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);
        $listes = Liste::all()->where('public', '=', '1')->where('expiration', '>=', date("Y-m-d"))->sortBy('expiration');

        $vue->setData($listes);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

    public function afficherliste(Request $rq, Response $rs, $args): Response
    {
        $liste = Liste::where('token', '=', $args['token'])->first();

        $vue = new ListeVue($this->container);
        $vue->setData($liste);
        $rs->getBody()->write($vue->render(1));
        return $rs;
    }

    public function creationListe(Request $rq, Response $rs, $args): Response
    {
        $vue = new ListeVue($this->container);

        $rs->getBody()->write($vue->render(2)); // form creation liste
        return $rs;
    }

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
            $url = 'affichage token modif'; // a finir
        }
        $liste->user_id = $user_id;
        $liste->save();

        return $rs->withRedirect($url);
    }

    private function creerToken(){
        $token = bin2hex(random_bytes(10));
        while(Liste::all()->where('token', '=', $token)->count() > 0 && Liste::all()->where('tokenModif', '=', $token)->count() > 0){
            $token = bin2hex(random_bytes(10));
        }
        return $token;
    }
}