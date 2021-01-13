<?php


namespace wishlist\controleur;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use wishlist\vue\MainVue;
use \wishlist\vue\UtilisateurVue;
use \wishlist\modele\Utilisateur;

class UtilisateurControleur
{
    private $container;

    public function __construct($container)
    {
        $this -> container = $container;
    }

    public function creationCompte(Request $rq, Response $rs, $args): Response
    {
        $vue = new UtilisateurVue($this->container);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }

    public function creerCompte(Request $rq, Response $rs, $args): Response
    {
        $vue = new UtilisateurVue($this->container);

        $post = $rq->getParsedBody();
        $nom = filter_var($post['nom'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($post['prenom'], FILTER_SANITIZE_STRING);
        $login = filter_var($post['login'], FILTER_SANITIZE_STRING);
        $password = filter_var($post['password'], FILTER_SANITIZE_STRING);

        $nb = Utilisateur::where('login', '=', $login)->count();
        if($nb == 0){
            $util = new Utilisateur();
            $util->nom = $nom;
            $util->prenom = $prenom;
            $util->login = $login;
            $util->password = password_hash($password, PASSWORD_DEFAULT);
            $util->save();

            $rs->getBody()->write($vue->render(1));
            return $rs;
        }else{
            $rs->getBody()->write($vue->render(2));
            return $rs;
        }
    }

    public function connexion(Request $rq, Response $rs, $args): Response
    {
        $vue = new UtilisateurVue($this->container);
        if (isset( $_SESSION['iduser'] )) {
            $rs->getBody()->write($vue->render(99999)); // utilisateur deja connecter
        } else {
            $rs->getBody()->write($vue->render(3)); // form connexion
        }
        return $rs;
    }

    public function seConnecter(Request $rq, Response $rs, $args): Response
    {
        $vueUtil = new UtilisateurVue($this->container);

        $post = $rq->getParsedBody();
        $login = filter_var($post['login'], FILTER_SANITIZE_STRING);
        $password = filter_var($post['password'], FILTER_SANITIZE_STRING);

        if(Utilisateur::where('login', '=', $login)->count() > 0){
            $util = Utilisateur::where('login', '=', $login)->first();
            if(password_verify($password, $util->password)){ // connecté
                $_SESSION['user'] = $util->login;

                header('Location: ' . $actionConnexion = $this->container->router->pathFor('accueil'));
                exit();
            }else{ // mot de passe faux
                $rs->getBody()->write($vueUtil->render(4));
                return $rs;
            }
        }else{ // login non existant
            $rs->getBody()->write($vueUtil->render(4));
            return $rs;
        }
    }

    public function deconnexion(Request $rq, Response $rs, $args): Response
    {
        session_destroy();
        header('Location: ' . $actionConnexion = $this->container->router->pathFor('accueil'));
        exit();
    }

    public function afficherCompte(Request $rq, Response $rs, $args): Response
    {

    }

}