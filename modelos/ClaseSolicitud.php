<?php

class Solicitud{
    public function Solicitar($idSolicitado){
        include "../sec/BBDD.php";
        $fecha = time();
        $idSolicitante = $_SESSION["id"];

        $filt = $db->prepare("INSERT INTO solicitudes(id_sol,id_rec,Fecha) values(?,?,?)");
        $filt->bind_param("iii",$idSolicitante,$idSolicitado,$fecha);//time() es la fecha actual en formato epoch en segundos
        $filt->execute();
    }

    public function Aceptar($idAceptado) {
        include "../sec/BBDD.php";
        $idAceptador = $_SESSION["id"];
        $filt = $db->prepare("SELECT * from usuarios where id = ?");
        $filt->bind_param('i', $idAceptador);
        $filt->execute();
        $res = $filt->get_result();

        $vec = $res->fetch_assoc();
        
        $amigos = $vec['amigos'];
        if($amigos != null){
            $amigos = $amigos.'#'.$idAceptado;
        }else{
            $amigos = $idAceptado;
        }

        $filt = $db->prepare("UPDATE usuarios set amigos = ? where id = ?");
        $filt->bind_param('si', $amigos, $idAceptador);
        $filt->execute();

        //ahora se añade al usuario de la session en el usuario q se acepta
        $filt = $db->prepare("SELECT * from usuarios where id = ?");
        $filt->bind_param('i', $idAceptado);
        $filt->execute();
        $res = $filt->get_result();

        $vec = $res->fetch_assoc();
        
        $amigos = $vec['amigos'];
        if($amigos != null){
            $amigos = $amigos.'#'.$idAceptador;
        }else{
            $amigos = $idAceptador;
        }

        $filt = $db->prepare("UPDATE usuarios set amigos = ? where id = ?");
        $filt->bind_param('si', $amigos, $idAceptado);
        $filt->execute();


        //Eliminamos la solicitud
        $filt = $db->prepare("DELETE FROM solicitudes WHERE id_sol = ? AND id_rec = ?");
        $filt->bind_param('ii', $idAceptado, $idAceptador);
        $filt->execute();
        }


    public function Rechazar($idRechazada){
        include('../sec/BBDD.php');
        $idRechazador = $_SESSION['id'];
        $filt = $db->prepare("DELETE FROM solicitudes WHERE id_sol = ? AND id_rec = ?");
        $filt->bind_param('ii', $idRechazada, $idRechazador);
        $filt->execute();
    } 

    public function Bloquear($idBloquear){
        include('../sec/BBDD.php');
        $idSolicitante = $_SESSION['id'];

        $filt = $db->prepare("INSERT INTO bloqueos (id_solicitante,id_bloqueado) values(?,?)");
        $filt->bind_param('ii', $idSolicitante, $idBloquear);
        $filt->execute();

        $filt = $db->prepare("DELETE FROM solicitudes WHERE id_sol = ? AND id_rec = ?");
        $filt->bind_param('ii', $idBloquear, $idSolicitante);
        $filt->execute();
    }
}


?>