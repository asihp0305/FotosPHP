<?php

use Dom\Document;

class usuario{
    /**
     * PRE:{Entra la id del usuario y su nivel}
     * POST:{elimina al usuario al q pertenece la id mientras q no sea el unico admin} 
     */
    public function BorrarUsuario($id){
        include("../sec/BBDD.php");

        $filt = $db->prepare("SELECT user from usuarios where id=?");
        $filt->bind_param("i",$id);
        $filt->execute();
        $user = $filt->get_result();
        $vec = $user->fetch_assoc();
        
        //eliminar las imagenes y la carpeta del usuario del disco
        $filt = $db->prepare("SELECT * from fotos_".$vec["user"]."");
        $filt->execute();
        $res = $filt->get_result();

        for($i = 0; $i < $res->num_rows; $i++){
            $v = $res->fetch_assoc();
            $img = $v["NOMBRE_DISCO"];
            $archivo = "../fotos/fotos_".$vec["user"]."/".$img ;
            unlink($archivo);
        }
        $dir = "../fotos/fotos_".$vec["user"];
         rmdir($dir);

        //eliminar la tabla del usuario de la base de datos
        $filt = $db->prepare("DROP TABLE fotos_" . $vec["user"] . "");
        $filt->execute();

        
        //eliminar al usuario de la base de datos
        $filt = $db->prepare("DELETE  FROM usuarios WHERE id = ?");
        $filt->bind_param("i", $id);
        $filt->execute(); 

    }

    /**
     * PRE:{Entran la id la contraseña el nombre y el nivel del usuario a modificar}
     * POST:{Modifica los datos q han entrado a no ser q el 
     * usuario sea un admin en cuyo caso no se modifica el nivel}
     */
    public function UpdateUsuario($ID,$Pass,$Name,$Level){
        include("../sec/BBDD.php");
            $filt = $db->prepare("UPDATE usuarios set pass = ?, nombre = ?, nivel = ? where id =? ");
            $filt->bind_param("ssii",$Pass,$Name,$Level,$ID);
            $filt->execute();
        
        
    }

    public function AddUsuarioLogin($nombreUsr,$Pass,$Name){
        include("../sec/BBDD.php");
            $has = password_hash($Pass, PASSWORD_DEFAULT);
            $filt = $db->prepare("INSERT INTO usuarios(user,pass,nombre,nivel,Conectado) values(?,?,?,1,1)");
            $filt->bind_param("sss",$nombreUsr,$has,$Name);
            $filt->execute();

            $flit = $db->prepare("CREATE TABLE IF NOT EXISTS fotos_" . $nombreUsr . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                NOMBRE_FOTO VARCHAR(225),
                DESCRIPCION VARCHAR(225),
                NOMBRE_DISCO VARCHAR(225),
                Estado INT,
                Comparticion INT,
                UsuarioOriginal INT,
                UsuarioCompartidos VARCHAR(255),
                Favoritos BOOLEAN NOT NULL DEFAULT 0
                )");
            $flit->execute();
    }

    public function AddUsuarioAdmin($nombreUsr,$Pass,$Name,$Level){
        include("../sec/BBDD.php");
            $has = password_hash($Pass, PASSWORD_DEFAULT);
            $filt = $db->prepare("INSERT INTO usuarios(user,pass,nombre,nivel) values(?,?,?,?)");
            $filt->bind_param("sssi",$nombreUsr,$has,$Name,$Level);
            $filt->execute();

            $flit = $db->prepare("CREATE TABLE IF NOT EXISTS fotos_" . $nombreUsr . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                NOMBRE_FOTO VARCHAR(225),
                DESCRIPCION VARCHAR(225),
                NOMBRE_DISCO VARCHAR(225),
                Estado INT,
                Comparticion INT,
                UsuarioOriginal INT,
                UsuarioCompartidos VARCHAR(255)
                )");
            $flit->execute();
    }
}

?>