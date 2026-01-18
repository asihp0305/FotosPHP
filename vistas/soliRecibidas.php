<?php
session_start();
include("../sec/BBDD.php");

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
        <tr>
            <th>USUARIO</th>
            <th>NOMBRE</th>
            <th>¿QUE DESEAS HACER?</th>
        </tr>
        <?php 
            $usuarioActual = $_SESSION['id'];
            $filt = $db->prepare("SELECT * from solicitudes where id_rec = ?");
            $filt->bind_param('i', $usuarioActual );
            $filt->execute();
            $res = $filt->get_result();

            for($i=0; $i < $res->num_rows; $i++){
                $vec = $res->fetch_assoc();

                $IDusr = $vec['id_sol'];
                $felt = $db->prepare("SELECT * from usuarios where id = ?");
                $felt->bind_param('i', $IDusr);
                $felt->execute();
                $res2 = $felt->get_result();
                if($res2->num_rows > 0){
                $vec2 = $res2->fetch_assoc();
        ?>
        <tr>
            <td><?php echo $vec2['user'] ?></td>
            <td><?php echo $vec2['nombre'] ?></td>
            <td> <button class="btnAceptar" laid=<?php echo $vec2['id']?>>ACEPTAR</button> <button class="btnRechazar" laid=<?php echo $vec2['id']?>>RECHAZAR</button> <button class="btnBloquear" laid=<?php echo $vec2['id']?>>BLOQUEAR</button> </td>
        </tr>
        <?php } }?>
    </table>
</body>
<script>
    $(".btnAceptar").click(function(){
        let idAceptado = $(this).attr("laid");
        
        $.ajax({
            type: "post",
            url: "controladores/controladorSolicitudes.php",
            data:{
                opt: 2,
                idA: idAceptado
            },

            success:function(data){
                location.reload();
            }
        });
    })
</script>

<script>
    $(".btnRechazar").click(function(){
        let idRechazado = $(this).attr("laid");

        $.ajax({
            type: "POST",
            url: "controladores/controladorSolicitudes.php",
            data:{
                opt: 3,
                idR: idRechazado
            },

            success:function(data){
                location.reload();
            }
        });
    })
</script>

<script>
    $(".btnBloquear").click(function(){
        let idBloqueado = $(this).attr("laid");

        $.ajax({
            type: "POST",
            url: "controladores/controladorSolicitudes.php",
            data:{
                opt: 4,
                idB: idBloqueado
            },

            success:function(data){
                location.reload();
            }
        });
    })
</script>
</html>