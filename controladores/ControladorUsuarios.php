<?php
include_once "../modelos/ClaseUsuario.php";
include("../sec/BBDD.php");
session_start();

$user = new usuario();
$option = filter_input(INPUT_POST, 'option', FILTER_SANITIZE_NUMBER_INT);

switch ($option) {
    case 1: 
        if((isset($_POST["laid"])&&(isset($_POST["level"])))){
           // $id = $_POST["laid"];
            $id = filter_input(INPUT_POST, 'laid', FILTER_SANITIZE_NUMBER_INT);
            //$level = $_POST["level"];
            $level = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);
            $miID = $_SESSION["id"];

            if($id==0){
                echo "No puedes borrar al superAdmin";
                exit;
            }

            if($miID == $id){
                echo "No puedes borrarte a ti mismo, inutil";
                exit;
            }
            if((!empty($id)&&(($level==0)||($level==1)))){
                $user->BorrarUsuario($id);
            }else{
                echo "No estas borrando nada";
            }
            
        }
        break;
    case 2: 
    if((isset($_POST["id"]))&&(isset($_POST["pass"]))&&(isset($_POST["name"]))&&(isset($_POST["level"]))){

            //$ID = $_POST["id"];
            $ID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); 
            //$Pass = $_POST["pass"];
            $Pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
            //$Name = $_POST["name"];
            $Name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            //$Level = $_POST["level"];
            filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);

            if((!empty($ID))&&(!empty($Pass))&&(!empty($Name))&&(($Level==0)||($Level==1))){
                if($ID == 0){
                    echo "No se puede modificar al super usuario";
                    exit;
                }
                $user->UpdateUsuario($ID,$Pass,$Name,$Level);
        }
    }
        break;
    
    case 3:
    if((isset($_POST["usr"]))&&(isset($_POST["pass"]))&&(isset($_POST["name"]))){

        //$usuario = $_POST["usr"];
        $usuario = filter_input(INPUT_POST, 'usr', FILTER_SANITIZE_SPECIAL_CHARS);
        //$Pass = $_POST["pass"];
        $Pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        //$name = $_POST["name"];
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

        if((!empty($usuario))&&(!empty($Pass))&&(!empty($name))){

            $user->AddUsuarioLogin($usuario,$Pass,$name);
            
            $filt = $db->prepare("SELECT * from usuarios where user = ?");
            $filt->bind_param("s",$usuario);
            $filt->execute();
            $res = $filt->get_result();
            $vec = $res->fetch_assoc();

            $_SESSION["id"] = $vec["id"];
            $_SESSION["user"] = $vec["user"];
            $_SESSION["name"] = $vec["nombre"];
            $_SESSION["Level"] = $vec["nivel"];
            header("Location:../index.php");
        }
    
        
    }
        break;


    case 4:
    if((isset($_POST["usr"]))&&(isset($_POST["pass"]))&&(isset($_POST["name"]))){

        //$usuario = $_POST["usr"];
        $usuario = filter_input(INPUT_POST, 'usr', FILTER_SANITIZE_SPECIAL_CHARS);
        //$Pass = $_POST["pass"];
        $Pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        //$name = $_POST["name"];
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        if(isset($_POST["level"])){
            $level = $_POST["level"];

            if((!empty($usuario))&&(!empty($Pass))&&(!empty($name))&&(($level==0)||($level==1))){

                $user->AddUsuario($usuario,$Pass,$name,$level);
            }
        }
    
        
    }


        break;

    default: break;

}


?>