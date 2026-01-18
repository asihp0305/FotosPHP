<!-- hacer q si no esta iniciada la sesion vaya al login -->
 <?php
 session_start();
 if(!isset($_SESSION["Level"])){
    header("Location:login.php");
 }

 ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div id="buscar">
        <div id="buscador">
            <input type="text" id="nombreusr" name="nombreusr">
            <button id="btnBuscar">🔎</button>
        </div>
        <div id="resultados">
            <!-- bucle con los resultados e icono para enviar solicitud-->
        </div>
    </div>    
</body>
<script>
    $("#btnBuscar").click(function() {
        console.log("le has dado");
        let nombre = $("#nombreusr").val();
        
        if(nombre.length > 0){
            console.log(nombre)
            $.ajax({
                type: "POST",
                url: "vistas/tablaBusqueda.php",
                data:{
                    nombreBuscar: nombre
                },
                success:function(data){
                    $("#resultados").html(data);
                }
            })
        }
    });
</script>
</html>