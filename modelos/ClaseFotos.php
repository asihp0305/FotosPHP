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
               
            $filt = $db->prepare("INSERT INTO FOTOS_" . $_SESSION["user"] . " (NOMBRE_FOTO, DESCRIPCION, NOMBRE_DISCO, Estado, Comparticion, UsuarioOriginal, UsuarioCompartidos) VALUES (?, 'Sin descripcion', ?, 0, 0, 0, ?)");
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

    public function compartir($recibe,$foto){
        include ("../sec/BBDD.php");
        session_start();

        $usrOriginal = $_SESSION["user"];
        $idOriginal = $_SESSION['id'];
        
        $filt = $db->prepare("SELECT * from usuarios where id = ?");
        $filt->bind_param("i",$recibe);
        $filt->execute();
        $res = $filt->get_result();
        $vec = $res->fetch_assoc();

        $usrRecibe = $vec["user"];

        if(file_exists("../fotos/fotos_".$usrRecibe)==0){
            mkdir("../fotos/fotos_".$usrRecibe,0777,true);
        }

        /* insertar la imagen cuyo id nos han pasado en la base de datos y luego intentar hacer el copy  */

        //obtenemos el nombre y la descripcion de la imagen original
        $filt = $db->prepare("SELECT * from FOTOS_".$usrOriginal." where id = ?");
        $filt->bind_param('i',$foto);
        $filt->execute();
        $res2 = $filt->get_result();
        $vec2 = $res2->fetch_assoc();

        $des = $vec2["DESCRIPCION"];
        $nombreOriginal = $vec2["NOMBRE_FOTO"]; 
        $nomDiskOriginal = $vec2["NOMBRE_DISCO"];


        $extension = pathinfo($nombreOriginal,PATHINFO_EXTENSION);
        $nombreDiscoTMP = "USR_". $usrRecibe ."_for_idFoto_temp_shared_from_". $usrOriginal . $extension;

        //insertamos la foto en el usuarioq la recibe pero el nombre en disco no es el final
        $felt = $db->prepare("INSERT INTO FOTOS_" . $usrRecibe . " (NOMBRE_FOTO, DESCRIPCION, NOMBRE_DISCO, Estado, Comparticion, UsuarioOriginal, UsuarioCompartidos) VALUES (?,?, ?, 0, 1, ?, '')");
        $felt->bind_param('sssi',$nombreOriginal,$des,$nombreDiscoTMP,$idOriginal);
        $felt->execute();

        //obtenemos el id de la foto una vez insertada para asi poder poner el nombre en el disco bien
        $flit = $db->prepare("SELECT id from FOTOS_". $usrRecibe ." where NOMBRE_DISCO = ? ");
        $flit->bind_param('s',$nombreDiscoTMP);
        $flit->execute();
        $res3 = $flit->get_result();
        $vec3 = $res3->fetch_assoc();

        $idFoto = $vec3['id'];

        $nombreDisco = "USR_". $usrRecibe ."_for_idFoto_". $idFoto ."_shared_from_". $usrOriginal .".". $extension;

        //cambiamos en la base de datos el nombre en disco
        $flet = $db->prepare("UPDATE FOTOS_". $usrRecibe ." set NOMBRE_DISCO = ? where id = ?");
        $flet->bind_param('si',$nombreDisco, $idFoto);
        $flet->execute();

        //hacemos un copy para añadir la imagen q ya teniamos en la carpeta destinataria
        $carpetaDestino = __DIR__ . '/../fotos/fotos_'.$usrRecibe.'/';
        $DestinoFinal = $carpetaDestino . $nombreDisco;
        
        $carpetaOrigen = __DIR__.'/../fotos/fotos_'.$usrOriginal.'/';
        $origen = $carpetaOrigen.$nomDiskOriginal;

        copy($origen,$DestinoFinal);

        /* $origen = $_SESSION['id'];
        $filt= $db->prepare("SELECT * from usuarios where id=?");
        $filt->bind_param('i',$recibe);
        $filt->execute();        
        $res = $filt->get_result();
        $vec = $res->fetch_assoc();

        $usuario = $vec["user"];
        $idUsusario = $vec["id"];

        if(file_exists("../fotos/fotos_".$usuario) == 0){
            mkdir("../fotos/fotos_".$usuario,0777,true);
        }

        foreach($fotos as $foto){
            $filt = $db->prepare("SELECT * from fotos_". $_SESSION['user'] ." where id = ?");
            $filt->bind_param('s',$foto);
            $filt->execute();
            $res = $filt->get_result();
            $vec = $res->fetch_assoc();
            
           $nombreOriginal = $vec["NOMBRE_FOTO"];
            $nomDisk = $vec["NOBRE_DISCO"];
            $des = $vec["DESCRIPCION"];
            $userShare = "";

            $extension = pathinfo($nombreOriginal,PATHINFO_EXTENSION);
            $nombreDiscoTemp = "TEMP.".$extension;
            $nombreFoto = "Compartida_por_".$_SESSION["user"];

            // Comparticion = 1 (Indica que es compartida)
            // UsuarioOriginal = ID del que la envió
            $felt = $db->prepare('INSERT INTO  fotos_'. $usuario .' (NOMBRE_FOTO, DESCRIPCION, NOMBRE_DISCO, Estado, Comparticion, UsuarioOriginal, UsuarioCompartidos) VALUES (?, ?, ?, 0, 1, ?, ?)');
            $felt->bind_param("sssii",$nombreFoto,$des,$nombreDiscoTemp,$origen,$userShare);
            $felt->execute();

            $felt = $db->prepare("SELECT id from fotos_". $usuario ." where NOMBRE_FOTO = ". $nombreDiscoTemp ." ");
            $felt->execute();
            $res = $felt->get_result();
            $vec = $res->fetch_assoc();
            $idIMG = $vec["id"];

            $nombreDisco = "USR_" . $_SESSION["user"] . "_for_" . "idFoto_" . $idIMG . "from_". $_SESSION["user"] ."." . $extension;
            

            $felt = $db->prepare("UPDATE fotos_".$usuario." set NOMBRE_DISCO ='". $nombreDisco ."' where id = ". $idIMG .";");
            $felt->execute();


            $original = __DIR__."/fotos" .$_SESSION["user"] . "/".$nomDisk;

            if ($original != "") {  // Ruta donde quieres guardar la imagen
                $userFolder = __DIR__."/fotos/fotos_" . $usuario;
                $newFilePath = $userFolder . "/" . $nombreDisco; 
                copy($original,$newFilePath);
            }
        } */
    }
}
?>