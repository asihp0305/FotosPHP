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
            <th>ESTADO</th>
            <th>USUARIO</th>
            <th>NOMBRE</th>
            <th>ACCION</th>
        </tr>

        <?php
            $filt = $db->prepare("SELECT amigos from usuarios where id = ?");
            $filt->bind_param('i',$_SESSION['id']);
            $filt->execute();
            $res = $filt->get_result();
            
            if($res->num_rows > 0){
                $Amigos = $res->fetch_assoc();
                $stringAmigos = $Amigos['amigos'];
                $vecAmigos = explode('#',$stringAmigos);
                //limpiamos el array para que cuando esta vacio no meta ningun 0
                $vecAmigos = array_diff(array_filter($vecAmigos),array('0'));
                foreach($vecAmigos as $amigo){
                    
                    $felt = $db->prepare('SELECT * from usuarios where id = ?');
                    $felt->bind_param('i', $amigo);
                    $felt->execute();
                    $res = $felt->get_result();
                    $vec = $res->fetch_assoc();
                    
        ?>
        <tr>
            <?php 
                if($vec["Conectado"] == 1){
            ?>
            <td>🟢</td>
            <?php }else{?>
            <td>🔴</td>
            <?php }?>
            <td><?php echo $vec['user'] ?></td>
            <td> <?php echo $vec['nombre'] ?> </td>
            <td> <button class="btnMensaje" laid="<?php echo $vec['id']?>">Enviar mensaje</button> <button class="btnCompartir" laid="<?php echo $vec['id']?>">Compartir</button> <button class="btnElim" laid="<?php echo $vec['id']?>"> Eliminar Amigo</button> </td>
        </tr>



        <?php }}?>
    </table>
</body>

<script>
    $(".btnElim").click(function(){
        let idElim = $(this).attr("laid");

        $.ajax({
            type: "post",
            url: 'controladores/controladorSolicitudes.php',
            data:{
                opt: 5,
                idElim: idElim
            },

            success:function(data){
                location.reload();
            }
        });
    })
</script>

</html>