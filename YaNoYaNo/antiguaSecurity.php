<?php
session_start();

if(isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !empty($_POST["user"]) && !empty($_POST["password"])){//comprueba q se ha metido un nombre o no para saber si es el LogIn o el registro

    //esto es para el registro

$nombre = $_POST["nombre"];
$user = $_POST["user"];
$pass = $_POST["password"];

$fp = fopen("sec/users.txt","r");//abre el users.txt en modo lectura
$bandera = 0;

while(($line = fgets($fp) ) && ($bandera != 1)){//en este bucle se recorre el users.txt 
    $vec = explode("#",$line);//almacena en vec un array con el contenido de la linea [id,user,pass,nombre,1]
   
    if($vec[1]==$user){
        $bandera=1;
    }else{
         $aux = $vec[0];//almacena en aux la posición 0 q coincide con el id
    }
  
}

if($bandera == 0){

$id= $aux+1;//suma 1 al contador ya q es el siguiente al último
$temp = $id."#".$user."#". $pass."#".$nombre."#"."1";//concatenacion de toda la informacion adquirida y el valor 1 ya q es el numero para no admins 
fclose($fp);//cerramos el users.txt

$fp = fopen("sec/users.txt","a");//abrimos el users.txt en modo escritura(con w sobreescribe)
fwrite($fp,"\r\n".$temp);//se hace un salto de linea y se introduce la concatenación antes hecha
fclose($fp);//se cierra el users.txt
}
header("Location:vistas/login.php");

}else{
//esto es para el login
    //vamos a usar variables superglobales q son $_POST y $_SESSION
    $user = $_POST["user"];
    $pass = $_POST["password"];
    
    $fp = fopen("sec/users.txt","r");//abre el txt y lo guarda en $fp
    $flag=0;
    while (($line = fgets($fp)) != false && $flag != 1) {//cuando fgets pilla el final devuelve false
        $vec = explode("#", $line);//explode guarda la linea como si fuera un array: [id,usuario,contraseña]
        if(($user == $vec[1]) && ($pass == $vec[2]) ){
           $flag = 1;
            $_SESSION["id"] = $vec[0];
            $_SESSION["user"] = $vec[1];
            $_SESSION["name"] = $vec[3];
            $_SESSION["Level"]= $vec[4];
            $_SESSION["idiom"] =  $_POST["idioma"];
        }
    }
    
   
    

    fclose($fp);
    if($flag == 0) {
        header("Location:vistas/login.php");
    }
}
     
?>