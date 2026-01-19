<?php
    include("sec/security.php");
    //include("locales/".$_POST["Idioma"].".xml");
    $idioma = simplexml_load_file("locales/".$_COOKIE["cookieIdioma"].".xml");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="vistas/cssVistas/cssBlackClover.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    
    <h1> <?php echo $idioma->palabras->bienve." ".$_SESSION["name"]; ?></h1>
    <select name="idioma" id="cambioIdioma">
        <option value="<?php echo $_COOKIE["cookieIdioma"]?>">Cambio de idioma</option>
        <option value="es">Español</option>
        <option value="en">Ingles</option>
        <option value="pt">Portugues</option>
    </select>
    <button class="logOut" ><a href="sec/logOut.php">Log Out</a></button>
    <button id="btnBusc">Buscar usuario</button>
    <button id="btnSolicitud">Solicitudes</button>
    <button id="btnListaAmigos">Lista amigos</button>
    
    <div id="ListaAmigos"></div>
    <div id="Solicitudes"></div>
    <div id="buscar"></div>
    <div id="TablaAdmin"></div>
    <div id="Galeria"></div>
</body>
  <script>
        $.ajax({
            type:"post",
            url:"vistas/tablaAdmin.php",
        success:function(data){
            $("#TablaAdmin").html(data);
        }
        });
    </script>
    <script>
        $.ajax({
            type:"post",
            url:"vistas/galeria.php",
        success:function(data){
            $("#Galeria").html(data);
        }
        });
    </script>

    <script>
        $("#cambioIdioma").change(function(){
            let newIdioma = $(this).val();
            
            $.ajax({
                type: "POST",
                url: "sec/cambio_idioma.php",
                data:{
                    idioma: newIdioma
                },
                success: function(){
                    location.reload();
                }
            })
        });
    </script>
    <script>
        $("#btnBusc").click(function(){
            if ($.trim($("#buscar").html()) !== "") {
                $("#buscar").html(''); 
            } else {
                $('#Solicitudes').html('');
                $('#ListaAmigos').html('');

                $.ajax({
                    type: "POST",
                    url: "vistas/buscarAmigos.php",
                    success: function(data){
                        $("#buscar").html(data);
                    }
                })
            }
        });
    </script>
    <script>
        $("#btnSolicitud").click(function(){
            if ($.trim($("#Solicitudes").html()) !== "") {
                $("#Solicitudes").html(''); 
            } else {
                $('#buscar').html('');
                $('#ListaAmigos').html('');

                $.ajax({
                    type: "POST",
                    url: "vistas/soliRecibidas.php",
                    success: function(data){
                        $("#Solicitudes").html(data);
                    }
                })
            }
        });
    </script>

    <script>
        $('#btnListaAmigos').click(function(){
            if ($.trim($("#ListaAmigos").html()) !== "") {
                $("#ListaAmigos").html(''); 
            } else {
                $('#buscar').html('');
                $('#Solicitudes').html('');
                
                $.ajax({
                    type:'POST',
                    url:"vistas/listaAmigos.php",
                    success:function(data){
                        console.log('algo hace')
                        $('#ListaAmigos').html(data);
                    }
                })
            }
        })
    </script>
</html>