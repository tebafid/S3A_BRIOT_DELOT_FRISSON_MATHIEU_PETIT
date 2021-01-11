<?php

echo "<div class='in'>";

use wishlist\vue\MainVue;

if(MainVue::getInMenu() != "" ){
    echo "<div class='inMenu'>";
    echo MainVue::getInMenu ();
    echo "</div>";
}else{
    echo "<center>";
}

echo "<div class='content'>";
echo MainVue::getContent ();
echo "</div></div>";

if(MainVue::getInMenu() == "" ){
    echo "</center>";
}

?>