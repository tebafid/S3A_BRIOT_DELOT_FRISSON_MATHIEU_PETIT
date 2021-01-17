<?php


namespace wishlist\vue;


class MainVue
{
    protected $container;
    protected static $content;

    public function __construct($c){
        $this->container = $c;
    }

    /**
     * rendu vue
     * @param int $i
     * @return String
     */
    public function render(int $i) : String {
        $html = "<h1> Bienvenue sur my MyWishList </h1>";

        MainVue::$content = $html;
        return substr(include ("html/index.php"), 0,-1);
    }

    public static function getContent(): String {
        return MainVue::$content;
    }
}