<?php


namespace wishlist\vue;


class MainVue
{
    private $container;
    public static $content;
    public static $inMenu;

    public function __construct($c){
        $this->container = $c;
    }

    public function render() : String {
        $html = "<h1> Bienvenue sur my MyWishList </h1>";

        MainVue::$content = $html;
        MainVue::$inMenu = "";
        return substr(include ("html/index.php"), 1,-1);
    }

    public function getContent(): String {
        return MainVue::$content;
    }

    public function getInMenu(): String {
        return MainVue::$inMenu;
    }
}