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

        $item = new Item();
        $item->liste_id = $args['id'];
        $item->nom = $nom;
        $item->descr = $descr;
        $item->tarif = $prix;
        $item->url = $url;
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

        if(Liste::all()->where('no', '=', $item->liste_id)->first()->id == Liste::all()->where('tokenModif', '=', $args['tokenModif']))
            return $rs;

        if($item->reserve == 1){
            //$ref = $this->container->router->pathFor('itemreserver'); a finir
            return $rs;
        }else{
            $item->delete();
            $ref = $this->container->router->pathFor('liste', ['token' => $args['tokenModif']]);
        }
        return $rs->withRedirect($ref);
    }

}