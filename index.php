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

        let ListaMostrada = false;
        let SolicitudesMostradas = false;
        let buscarMostrado = false;


        $.ajax({
            type: "POST",
            url: "vistas/buscarAmigos.php",
            success: function(data){
                $("#buscar").html(data);
            }
        })

        $.ajax({
            type: "POST",
            url: "vistas/soliRecibidas.php",
            success: function(data){
                $("#Solicitudes").html(data);
            }
        })

        $.ajax({
            type:'POST',
            url:"vistas/listaAmigos.php",
            success:function(data){
                $('#ListaAmigos').html(data);
            }
        })  
        
        $("#buscar").hide();
        $("#Solicitudes").hide();
        $("#ListaAmigos").hide(); 

        $.ajax({
            type:"post",
            url:"vistas/tablaAdmin.php",
        success:function(data){
            $("#TablaAdmin").html(data);
        }
        });

        $.ajax({
            type:"post",
            url:"vistas/galeria.php",
        success:function(data){
            $("#Galeria").html(data);
        }
        });
    
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
    
        $("#btnBusc").click(function(){
          if(buscarMostrado == true){
            $('#buscar').hide();
            buscarMostrado = false;
          }else{
                $('#Solicitudes').hide();
                $('#ListaAmigos').hide();
                $("#buscar").toggle();
                buscarMostrado = true;
            }
               
            
        });
    
        $("#btnSolicitud").click(function(){
            if(SolicitudesMostradas == true){
                $('#Solicitudes').hide();
                SolicitudesMostradas=false;
            }else{
                $('#buscar').hide();
                $('#ListaAmigos').hide();
                $("#Solicitudes").toggle();
                SolicitudesMostradas = true;
            }   
        });
    
        $('#btnListaAmigos').click(function(){
            if(ListaMostrada == true){
                $("ListaAmigos").hide();
                ListaMostrada = false;
            }else{
                $('#buscar').hide();
                $('#Solicitudes').hide();
                $("#ListaAmigos").toggle();
                ListaMostrada = true;
            }            
        })

         function ListaAmigosAJAX(){
                $.ajax({
                    type:'POST',
                    url:"vistas/listaAmigos.php",
                    success:function(data){
                        $('#ListaAmigos').html(data);
                    }
                })
            }
        $(document).ready(function(){       
            setInterval(ListaAmigosAJAX,5000);
        }); 

    </script>

   
</html>