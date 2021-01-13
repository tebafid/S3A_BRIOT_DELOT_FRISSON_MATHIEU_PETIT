<?php

echo "<div class='in'>";

use wishlist\vue\MainVue;
/*
if(MainVue::getContent() != "<h1> Bienvenue sur my MyWishList </h1>" ){
    echo "<div class='inMenu'>";
    echo MainVue::getContent();
    echo "</div>";
}else{
    echo "<center>";
}*/

echo "<div class='content'>";
echo MainVue::getContent();
echo "</div></div>";

/*
if(MainVue::getContent() == "<h1> Bienvenue sur my MyWishList </h1>" ){
    echo "</center>";
}*/

?>