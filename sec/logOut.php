<?php
session_start();
include("BBDD.php");

$user = $_SESSION['id'];

$filt = $db->prepare("UPDATE usuarios set Conectado = 0 where id = ?");
$filt->bind_param('i', $user);
$filt->execute();

$_SESSION=array();

session_destroy();

header("Location:../vistas/login.php");
exit;
?>