<?php
session_start();
include("BBDD.php");

//Esto es para el autologin
 /*if(!isset($_POST["user"]) && isset($_SESSION["user"])){
   /*  $filt = $db->prepare("SELECT * FROM usuarios where user = ? AND pass = ? ");
    $filt->bind_param('ss',$_COOKIE("cookieUser"),$_COOKIE);
    $filt->execute(); 


    $_SESSION["id"] = $_COOKIE["cookieID"];
    $_SESSION["user"] = $_COOKIE["cookieUser"];
    $_SESSION["name"] = $_COOKIE["cookieName"];
    $_SESSION["Level"] = $_COOKIE["cookieLevel"];

    return;
}else{ */
if(!isset($_POST["user"]) && isset($_SESSION["user"])){
    return;
}
//esto es para el login
    //vamos a usar variables superglobales q son $_POST y $_SESSION
if(isset($_POST["user"]) && isset($_POST["password"])){
$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
$pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    
$filt = $db->prepare("SELECT * FROM usuarios where user = ?");
$filt->bind_param('s',$user);

$filt->execute();
$res = $filt->get_result();

$vec = $res->fetch_assoc();

if(!password_verify($pass, $vec['pass'])){
    header("Location:vistas/login.php");
    exit;
}else{
    $_SESSION["id"] = $vec["id"];
    $_SESSION["user"] = $vec["user"];
    $_SESSION["name"] = $vec["nombre"];
    $_SESSION["Level"] = $vec["nivel"];
    
    $user = $vec['id'];
    $filt = $db->prepare("UPDATE usuarios set Conectado = 1 where id = ?");
    $filt->bind_param('i', $user);
    $filt->execute();


   /*  if(isset($_POST["recordar"])){
        setcookie("cookieID",$vec["id"], time() + (86400 * 7),"/");
        setcookie("cookieUser",$vec["user"], time() + (86400 * 7),"/");
        setcookie("cookieName",$vec["nombre"], time() + (86400 * 7),"/");
        setcookie("cookieLevel",$vec["nivel"], time() + (86400 * 7),"/");

    } */
}
}else{
    header("Location:vistas/login.php");
}
//}
?>