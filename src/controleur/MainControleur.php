<?php


namespace wishlist\controleur;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use wishlist\vue\MainVue;

class MainControleur
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function accueil(Request $rq, Response $rs, $args) : Response {
        $vue = new MainVue($this->container);
        $rs->getBody()->write($vue->render(0));
        return $rs;
    }
}