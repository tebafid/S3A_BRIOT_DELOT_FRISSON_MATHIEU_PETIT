<?php


namespace wishlist\vue;


class MainVue
{
    protected $container;
    protected static $content;

    public function __construct($c){
        $this->container = $c;
    }

    public function render(int $i) : String {
        $html = "<h1> Bienvenue sur my MyWishList </h1>";

        MainVue::$content = $html;
        return substr(include ("html/index.php"), 0,-1);
    }

    public function getContent(): String {
        return MainVue::$content;
    }
}