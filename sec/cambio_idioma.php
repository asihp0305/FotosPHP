<?php


//$_SESSION["idiom"] = $_POST["idioma"];

if(isset($_COOKIE["cookieIdioma"])){
    setcookie("cookieIdioma",$_POST["idioma"],time() + (86400 * 7),"/");
}
?>