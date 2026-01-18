<?php
include_once "../modelos/ClaseSolicitud.php";

session_start();

$sol = new Solicitud();
$opt = filter_input(INPUT_POST, 'opt', FILTER_SANITIZE_NUMBER_INT);

switch($opt){
    case 1://solicitar
        
        $idSoli = filter_input(INPUT_POST,'idSolicitado',FILTER_SANITIZE_NUMBER_INT);

        $sol->Solicitar($idSoli);

        break;
        
    case 2: //aceptar
        $idAceptado = filter_input(INPUT_POST,'idA',FILTER_SANITIZE_NUMBER_INT);

        $sol->Aceptar($idAceptado);
        break;

    case 3: //rechazar
        $idRechazado = filter_input(INPUT_POST, 'idR', FILTER_SANITIZE_NUMBER_INT);

        $sol->Rechazar($idRechazado);
        break;

    case 4: //bloquear
        $idBloqueada = filter_input(INPUT_POST, 'idB', FILTER_SANITIZE_NUMBER_INT);

        $sol->Bloquear($idBloqueada);
        break;

    default:
        break;


}




?>