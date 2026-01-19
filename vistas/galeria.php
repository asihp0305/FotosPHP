<!-- boton subir
 div galeria/ div contenido/ div imagen/div nombrefoto/ div descripcionfoto
 boton borrar -->
 <?php include("../sec/BBDD.php");
 session_start();
 if(!isset($_SESSION["Level"])){
    header("Location:login.php");
}
 ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Galería de Fotos</title>
</head>
 
<body>
    <form action="./Controladores/controladorfotos.php" method="post" enctype="multipart/form-data">
        <div id="uploader">
            <input type="file" id="botonSubir" name="upload[]" accept="image/*" multiple>
        </div>
 
 
        <input type="submit" id="uploadFile">
        <input name="opt" type="hidden" value="0">
    </form>
 <button id="Borradas">Recuperar</button> <button id="Normales">Volver</button>
    <div class="galeria" >
         <?php 
         //las q estan en favoritos
        $filt = $db->prepare("SELECT * from fotos_".$_SESSION["user"]." where Estado = 2;");
        $filt->execute();
        $res = $filt->get_result();


        for($i = 1; $i <= $res->num_rows; $i++){
            $vec = $res->fetch_assoc();
        ?>
        <div class="contenido">
            <div class="imagen">
                <img  src="../fotos/fotos_<?php echo $_SESSION["user"]."/".$vec["NOMBRE_DISCO"] ?>" alt="Foto de ejemplo">
            </div>
            <button class="botEdit" ide="<?php echo $vec["id"]?>">Editar</button>
            <div class="nombrefoto" value = "<?php echo $vec["NOMBRE_FOTO"]?>"><?php echo $vec["NOMBRE_FOTO"]?></div>
            <div class="descripcionfoto" value = "<?php echo $vec["DESCRIPCION"]?>">Descripción de la foto:<?php echo $vec["DESCRIPCION"]?></div>
            <button class="botBorrar" laid="<?php echo $vec["id"]?>">Borrar</button> <button class="QuitFav" LAID="<?php echo $vec["id"]?>">Quitar Favoritos</button>
        </div>
        <?php }?>
        
        <?php 
        //las q no estan en favoritos
        $filt = $db->prepare("SELECT * from fotos_".$_SESSION["user"]." where Estado = 0;");
        $filt->execute();
        $res = $filt->get_result();


        for($i = 1; $i <= $res->num_rows; $i++){
            $vec = $res->fetch_assoc();
        ?>
        <div class="contenido">
            <div class="imagen">
                <img width="200px" src="../fotos/fotos_<?php echo $_SESSION["user"]."/".$vec["NOMBRE_DISCO"] ?>" alt="Foto de ejemplo">
            </div>
            <button class="botEdit" ide="<?php echo $vec["id"]?>">Editar</button>
            <div class="nombrefoto" value = "<?php echo $vec["NOMBRE_FOTO"]?>"><?php echo $vec["NOMBRE_FOTO"]?></div>
            <div class="descripcionfoto" value = "<?php echo $vec["DESCRIPCION"]?>">Descripción de la foto:<?php echo $vec["DESCRIPCION"]?></div>
            <button class="botBorrar" laid="<?php echo $vec["id"]?>">Borrar</button> <button class="AddFav" LAIDadd="<?php echo $vec["id"]?>">Añadir a Favoritos</button>
        </div>
        <?php }?>
    </div>

    <div class="Recuperar">
        <?php 
            $filt = $db->prepare("SELECT * FROM fotos_".$_SESSION["user"]." where Estado = 1");
            $filt->execute();
            $res = $filt->get_result();

            for($i = 0; $i < $res->num_rows; $i++){
                $vec = $res->fetch_assoc();
        ?>

        <div class="contenido">
            <div class="imagen">
                <img width="200px" src="../fotos/fotos_<?php echo $_SESSION["user"]."/".$vec["NOMBRE_DISCO"] ?>" alt="Foto de ejemplo">
            </div>
            <div class="nombrefoto" value = "<?php echo $vec["NOMBRE_FOTO"]?>"><?php echo $vec["NOMBRE_FOTO"]?></div>
            <div class="descripcionfoto" value = "<?php echo $vec["DESCRIPCION"]?>">Descripción de la foto:<?php echo $vec["DESCRIPCION"]?></div>
            <button class="botRecuperar" laid="<?php echo $vec["id"]?>">Recuperar imagen</button> <button class="Destruir" LAIDdes="<?php echo $vec["id"]?>">Destruir imagen</button>
        </div>

        <?php }?>
    </div>
</body>


<script>
    $(document).ready(function() {
        // 1. Al cargar la página, desactivamos el botón de enviar
        $("#uploadFile").prop("disabled", true);

        // 2. Escuchamos cambios en el input de subir fotos
        $("#botonSubir").change(function() {
            // Si hay archivos seleccionados (length > 0)
            if(this.files.length > 0) {
                // Activamos el botón
                $("#uploadFile").prop("disabled", false);
            } else {
                // Si no (o si el usuario cancela la selección), lo volvemos a desactivar
                $("#uploadFile").prop("disabled", true);
            }
        });
    });
</script>


 <script>
    $(".botBorrar").click(function() {
        let idFoto = $(this).attr("laid");

        $.ajax({
            type: "POST",
            url: "controladores/controladorfotos.php",
            data:{
                idfoto: idFoto,
                opt: 1
            },
            success: function(){
                location.reload();
            }
        });
    });
 </script>

 <script>
    $(".botEdit").click(function(){
        let idFoto = $(this).attr("ide");
        let elegir = parseFloat(prompt("Que deseas editar el nombre de la imagen(1), la descripción(2) o ambas(3)"));

        switch(elegir){
            case 1:
                let nombreNuevo = prompt("Introduce el nuevo nombre de la foto");

                if(nombreNuevo != "" && nombreNuevo != null){
                    $.ajax({
                        type: "POST",
                        url: "controladores/controladorfotos.php",
                        data:{
                            opt: 2,
                            nombre: nombreNuevo,
                            id: idFoto
                        },
                        success: function(){
                            location.reload();
                        }
                    });
                }
                break;
            
            case 2:
                let des = prompt("Introduce la descripcion que deseas para la imagen");
                if (des != "" && des != null){
                    $.ajax({
                        type: "POST",
                        url: "controladores/controladorfotos.php",
                        data:{
                            opt: 3,
                            descripcion: des,
                            id: idFoto
                        },
                        success: function(){
                            location.reload();
                        }
                    });
                } 

                break;

            case 3:
                let nombreNuevoA = prompt("Introduce el nuevo nombre de la foto");
                let desA = prompt("Introduce la descripcion que deseas para la imagen");
                if((nombreNuevo != "" && nombreNuevo != null) && (des != "" && des != null) ){
                    $.ajax({
                        type: "POST",
                        url: "controladores/controladorfotos.php",
                        data:{
                            opt: 4,
                            nombre: nombreNuevoA,
                            descripcion: desA,
                            id: idFoto
                        },
                        success: function(){
                            location.reload();
                        }
                    });
                }
                break;

            default:
                break;
        }
    });
 </script>

 <script>
    $(".QuitFav").click(function(){
        let LAid = $(this).attr("LAID");
        $.ajax({
            type: "POST",
            url: "controladores/controladorfotos.php",
            data:{
                opt: 5,
                id: LAid
            },
            success:function(){
                location.reload();
            }
        })
    });
 </script>

 <script>
    $(".AddFav").click(function(){
        let LAid = $(this).attr("LAIDadd");
        $.ajax({
            type: "POST",
            url: "controladores/controladorfotos.php",
            data:{
                opt: 6,
                id: LAid
            },
            success:function(){
                location.reload();
            }
        })
    });
 </script>

 <script>
    $("#Normales").hide();
    $(".Recuperar").hide();
    $("#Borradas").click(function(){
        $("#Borradas").hide();
        $(".galeria").hide();
        $("#Normales").show();
        $(".Recuperar").show();
    });

     $("#Normales").click(function(){
        $("#Normales").hide();
        $(".Recuperar").hide();
        $("#Borradas").show();
        $(".galeria").show();
    });
 </script>

 <script>
    $(".botRecuperar").click(function(){
        let IDE = $(this).attr("laid");
        $.ajax({
            type: "POST",
            url: "controladores/controladorfotos.php",
            data:{
                opt: 7,
                id: IDE
            },
            success:function(){
                location.reload();
            }
        })
    });
 </script>


 <script>
    $(".Destruir").click(function(){
        let IDD = $(this).attr("LAIDdes");
        $.ajax({
            type: "POST",
            url: "controladores/controladorfotos.php",
            data:{
                opt: 8,
                id: IDD
            },
            success:function(){
                location.reload();
            }
        })
    });
 </script>
</html>
