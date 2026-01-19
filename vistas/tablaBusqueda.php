    <?php
include("../sec/BBDD.php");
    /* hacer q si no esta la sesion iniciada vaya al login */
    if(isset($_POST["nombreBuscar"])){
    $Nombre = filter_input(INPUT_POST, 'nombreBuscar', FILTER_SANITIZE_SPECIAL_CHARS);
    session_start();
    if(!isset($_SESSION["Level"])){
    header("Location:login.php");
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<table>
    <?php
        $buscamos = '%'.$Nombre.'%';
        $envia = $_SESSION["id"];
        $filt = $db->prepare("SELECT * from usuarios where (user like ? or nombre like ?) and (id != ? and id != 0)");
        $filt->bind_param('ssi',$buscamos,$buscamos,$envia);
        $filt->execute();
        $res = $filt->get_result();

        for($i = 0;$i < $res->num_rows; $i++){
            $vec = $res->fetch_assoc();
            //if($vec['id'] != 0 || $vec["id"] != $_SESSION["id"]){
                $recibe = $vec["id"];
                
                $folt = $db->prepare("SELECT * from bloqueos where (id_solicitante = ? and id_bloqueado = ?) or (id_solicitante = ? and id_bloqueado = ?)");
                $folt->bind_param('iiii',$recibe,$envia, $envia, $recibe);
                $folt->execute();
                $res2 = $folt->get_result();
                if($res2->num_rows == 0){
    ?>
    <tr>
        <td><?php echo $vec["user"] ?></td>
        <td><?php echo $vec['nombre']?></td>
       <!--  <?php
           
           /*  $felt = $db->prepare("SELECT amigos from usuarios where id = ?");
            $felt->bind_param('i',$envia);
            $felt->execute();
            $res4 = $felt->get_result();

            if($res4->num_rows > 0){
            $amigos = $res4->fetch_assoc();
            $stringAmigos = $amigos['amigos'];
            $vecAmigos = explode('#',$stringAmigos);

            if(in_array($recibe, $vecAmigos)){
                    
            }
            }else{

            $flit = $db->prepare("SELECT * from solicitudes where id_sol = ? and id_rec = ?");
            $flit->bind_param("ii",$envia,$recibe);
            $flit->execute();
            $res3 = $flit->get_result();
            

            if($res3->num_rows > 0){ */
        ?>
        <td>SOLICITADO</td>
        <?/*php/*  }else{ */?>
        <td><button class="solicitar" laid="<?php /* echo $vec["id"] */?>">solicitar</button></td>
        <?php /* }} */?> -->

    <?php 
        $felt = $db->prepare("SELECT amigos from usuarios where id = ?");
        $felt->bind_param('i',$envia);
        $felt->execute();
        $res4 = $felt->get_result();
        $tipo = "nada";
        if($res4->num_rows > 0){
            $amigos = $res4->fetch_assoc();
            $stringAmigos = $amigos['amigos'];
            $vecAmigos = explode('#',$stringAmigos);

            /* if(in_array($recibe, $vecAmigos)){
                 $tipo = "amigos";   
            } */

            $recibe = $vec["id"];

           for($i=0;$i < count($vecAmigos);$i++){
                if($vecAmigos[$i] == $recibe){
                    $tipo = "amigos";
                }
           }
        }
        if($tipo == "nada"){
            $flit = $db->prepare("SELECT * from solicitudes where id_sol = ? and id_rec = ?");
            $flit->bind_param("ii",$envia,$recibe);
            $flit->execute();
            $res3 = $flit->get_result();
            

            if($res3->num_rows > 0){
                $tipo = "solicitado";
            }

            $flet = $db->prepare("SELECT * from solicitudes where id_sol = ? and id_rec = ?");
            $flet->bind_param("ii",$recibe,$envia);
            $flet->execute();
            $res5 = $flet->get_result();
            if($res5->num_rows > 0){
                $tipo = "teSolicita";
            }
            }

        switch($tipo){
            case "amigos":
    ?>

        <td>AMIGOS</td>
        <?php
        break;

        case "teSolicita":
        ?>
        <td>Tienes una solicitud</td>
        <?php 
        break;

        case "solicitado":
        ?>
        <td>SOLICITADO</td>
        <?php
        break;

        default:
        ?>
        <td><button class="solicitar" laid="<?php echo $vec["id"] ?>">solicitar</button></td>
<?php 
                break; 
            } // Fin Switch
            ?>
    </tr>
<?php 
        } // Fin if bloqueos
    //} // Fin if validación usuario
} // Fin For
?>
</table>

</body>

<script>
    $(".solicitar").click(function(){
        let solicitado = $(this).attr("laid");
        console.log(solicitado);
        $.ajax({
            type: "POST",
             url: "controladores/controladorSolicitudes.php",
             data:{
                opt: 1,
                idSolicitado: solicitado
             },
             success:function(data){
                location.reload();
             }
        })
    })

</script>
    
</html>
<?php }?>