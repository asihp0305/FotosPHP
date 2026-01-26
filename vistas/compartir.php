<?php include("../sec/BBDD.php");
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
        <tr>
            <th>USUARIO</th>
            <th>NOMBRE</th>
            <th>COMPARTIR</th>
        </tr>
        <?php
            $envia = $_SESSION["id"];
            $filt = $db->prepare("SELECT amigos from usuarios where id = ?");
            $filt->bind_param('i',$envia);
            $filt->execute();
            $res = $filt->get_result();
            $vec = $res->fetch_assoc();
            $strAmigos = $vec["amigos"];

            $vecAmigos = explode('#',$strAmigos);
            foreach($vecAmigos as $amigo){
                $felt = $db->prepare("SELECT * from usuarios where id = ?");
                $felt->bind_param('i',$amigo);
                $felt->execute();
                $res2 = $felt->get_result();
                $vec2 = $res2->fetch_assoc();
            ?>
            <tr>
                <td> <?php echo $vec2["user"]?> </td>
                <td> <?php echo $vec2["nombre"]?> </td>
                <td><button class="btnCompartirIMG" idRecibe="<?php echo $amigo ?>">Compartir</button></td>
            </tr>
            <?php }?>
    </table>
</body>

<script>
    $(document).off("click",".btnCompartirIMG").on("click",".btnCompartirIMG",function(){
        console.log("click hace")
        let idRecibe = $(this).attr("idRecibe");
        let fotos = <?php echo json_encode( $_POST["fotosEnviadas"] )?>;
        console.log(fotos);

        $.ajax({
            type:"post",
            url:"controladores/controladorfotos.php",
            data:{
                opt: 9,
                Recibe:idRecibe,
                fotosEnviadas:fotos
            },
            success:function(data){
                console.log('funca');
            }
        })
    })
</script>

</html>