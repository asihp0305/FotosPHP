<?php
$db = new mysqli("localhost", "root", "MiguelonyTamara3", "fotosdb", 3306); //esta instruccion es la conexion a la db,
mysqli_query($db,"SET NAMES 'UTF8'");
$base = mysqli_select_db($db,"fotosdb");
?>