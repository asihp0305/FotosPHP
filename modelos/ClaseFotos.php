<?php

class Fotos{

    public function add($files){
        session_start();

        if(file_exists("../fotos/fotos_".$_SESSION["user"]) == 0){
            mkdir("../fotos/fotos_".$_SESSION["user"],0777,true);
        }
        /*
        echo "<pre>";
        var_dump($files);
        echo "</pre>";
*/

        for($i=0; $i < count($files["upload"]["name"]);$i++){
            $tmpFilePath = $files["upload"]["tmp_name"][$i];
            $fileName = $files["upload"]["name"][$i];
            $extension = pathinfo($fileName,PATHINFO_EXTENSION);//extension del archivo
            

            include("../sec/BBDD.php");
            $tabla = "fotos_" . $_SESSION["user"];//en esta variable guardamos el nombre de la tabla

           
            $userShare = "";
            $disc_name = "USR_" . $_SESSION["user"] . "_for_" . "idFoto"  . "." . $extension;
               
            $filt = $db->prepare("INSERT INTO FOTOS_" . $_SESSION["user"] . " (NOMBRE_FOTO, DESCRIPCION, NOMBRE_DISCO, Estado, Comparticion, UsuarioOriginal, UsuarioCompartidos) VALUES (?, '', ?, 0, 0, 0, ?)");
            $filt->bind_param("sss", $fileName, $disc_name, $userShare);
            $filt->execute();
         
            
            $filt = $db->prepare("SELECT id from fotos_" .$_SESSION["user"]. " where NOMBRE_DISCO = '". $disc_name ."';");
            $filt->execute();
            $res = $filt->get_result();
            $fila = $res->fetch_assoc();
            $contar = $fila["id"];
            echo $contar;

            $newDiskName = "USR_" . $_SESSION["user"] . "_for_" . "idFoto_" . $contar . "." . $extension;

            $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set NOMBRE_DISCO ='". $newDiskName ."' where id = ". $contar ."; ");
            $filt->execute();

            if ($tmpFilePath != "") {  // Ruta donde quieres guardar la imagen
                $userFolder = "../fotos/fotos_" . $_SESSION["user"];
                $newFilePath = $userFolder . "/" . "USR_" . $_SESSION["user"] . "_for_" . "idFoto_" . $contar . "." . $extension; // Mover el archivo a la carpeta de destino   
                move_uploaded_file($tmpFilePath, $newFilePath);      
            }
        }
    }

    public function borrar($id){
        include("../sec/BBDD.php");
        session_start();

        //marcar la imagen como eliminada
        $filt= $db->prepare("UPDATE fotos_".$_SESSION["user"]." set Estado = 1 where id = ?");
        $filt->bind_param("i",$id);
        $filt->execute();

/*
        //ELiminar la imagen por completo
        $filt = $db->prepare("SELECT NOMBRE_DISCO FROM fotos_".$_SESSION["user"]." where id = '".$id."' ");
        $filt->execute();
        $res = $filt->get_result();
        $vec = $res->fetch_assoc();
        $imagen = $vec["NOMBRE_DISCO"];

        $archivo = "../fotos/fotos_".$_SESSION["user"]."/".$imagen."";
        unlink($archivo);


        //Eliminar la imagen de la base de datos
        $filt = $db->prepare("DELETE FROM fotos_".$_SESSION["user"]." where id = '".$id."' ");
        $filt->execute(); */


    }

    public function editarDescImg($id,$des){
        include("../sec/BBDD.php");
        session_start();

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set DESCRIPCION = ? WHERE id = ?");
        $filt->bind_param("si", $des,$id);
        $filt->execute();
    }

    public function editarNomImg($id,$nombre){
        include("../sec/BBDD.php");
        session_start();

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set NOMBRE_FOTO = ? WHERE id = ?");
        $filt->bind_param("si", $nombre,$id);
        $filt->execute();

    }

    public function editarNomDes($id,$nom,$des){
        include("../sec/BBDD.php");
        session_start();

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set NOMBRE_FOTO = ? , DESCRIPCION = ? WHERE id = ?");
        $filt->bind_param("ssi", $nombre,$des,$id);
        $filt->execute();

    }

    public function QuitFavs($id){
        session_start();
        include("../sec/BBDD.php");
        

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set Estado = 0 where id = ?");
        $filt->bind_param("i",$id);
        $filt->execute();
    }
    public function addFavs($id){
        session_start();
        include("../sec/BBDD.php");

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set Estado = 2 where id = ?");
        $filt->bind_param("i",$id);
        $filt->execute();
    }

    public function recuperar($id){
        session_start();
        include("../sec/BBDD.php");

        $filt = $db->prepare("UPDATE fotos_".$_SESSION["user"]." set Estado = 0 where id = ?");
        $filt->bind_param("i",$id);
        $filt->execute();
    }

    public function eliminar($id){
        session_start();
        include("../sec/BBDD.php");
        //ELiminar la imagen por completo
        $filt = $db->prepare("SELECT * FROM fotos_".$_SESSION["user"]." where id = ? ");
        $filt->bind_param("i",$id);
        $filt->execute();
        $res = $filt->get_result();
        $vec = $res->fetch_assoc();
        $imagen = $vec["NOMBRE_DISCO"];

        $archivo = "../fotos/fotos_".$_SESSION["user"]."/".$imagen;
        unlink($archivo);


        //Eliminar la imagen de la base de datos
        $filt = $db->prepare("DELETE FROM fotos_".$_SESSION["user"]." where id = ? ");
        $filt->bind_param("i",$id);
        $filt->execute(); 
    }
}
?>