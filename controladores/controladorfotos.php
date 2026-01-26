<?php
include_once "../modelos/ClaseFotos.php";
session_start();

$fotos = new Fotos();
$opt = filter_input(INPUT_POST, 'opt', FILTER_SANITIZE_NUMBER_INT);
switch($opt){
    case 0:
        $fotos->add($_FILES);
        header("Location: ../index.php");
        break;

    case 1:
        //$idFoto = $_POST["idfoto"];
        $idFoto = filter_input(INPUT_POST, 'idfoto', FILTER_SANITIZE_NUMBER_INT);
        $fotos->borrar($idFoto);
        break;

    case 2:
        //$idFoto = $_POST["id"];
        $idFoto = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        //$nombre = $_POST["nombre"];
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);

        $fotos->editarNomImg($idFoto,$nombre);
        break;
    case 3:
        //$idFoto = $_POST["id"];
        $idFoto = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        //$desc = $_POST["descripcion"];
        $desc = filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_SPECIAL_CHARS);

        $fotos->editarDescImg($idFoto,$desc);
        break;

    case 4:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);

        $fotos->editarNomDes($id,$nombre,$descripcion);
        break;

    case 5:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $fotos->QuitFavs($id);
        break;

    case 6:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $fotos->addFavs($id);
        break;

    case 7:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $fotos->recuperar($id);
        break;

    case 8:
       $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $fotos->eliminar($id);
        break;

    case 9:
        $Recibe = filter_input(INPUT_POST,'Recibe',FILTER_SANITIZE_NUMBER_INT);
        $imagenes = filter_input(INPUT_POST,'fotosEnviadas',FILTER_SANITIZE_STRING,FILTER_REQUIRE_ARRAY);
        foreach($imagenes as $imagen){
            $fotos->compartir($Recibe,$imagen);
        }
        break;

    default:
        break;
}
?>