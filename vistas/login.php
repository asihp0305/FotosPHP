<?php
if(!isset($_COOKIE["cookieIdioma"])){
    $idioma = simplexml_load_file("../locales/es.xml");
    setcookie("cookieIdioma","es",time() + (86400 * 7),"/");
}else{
    $idioma = simplexml_load_file("../locales/".$_COOKIE["cookieIdioma"].".xml");
}

if(isset($_COOKIE["cookieUser"])){
    header("Location:../index.php");
}else{
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssVistas/cssBlackClover.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="LogIn" id="log">
        <form method="post" action="../index.php"><!--Con el get se ve lo enviado en la url, con el post habria q verlo en network-->
            <h2>Introduce tus credenciales</h2>

            <label for="user">Usuario o email:</label>
            <input type="text" id="user" name="user"><br>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password"><br>
            
            <select name="idioma" id="idiom">
                <option value="<?php echo $_COOKIE["cookieIdioma"]?>">Selecciona un idioma</option>
                <option value="en">Ingles</option>
                <option value="es">Español</option>
                <option value="pt">Portugues</option>
            </select>

            <button type="submit"><?php echo $idioma->botones->send;?></button>
            <label>
                Recuerdame <input type="checkbox" name="recordar" value="1">
            </label>
        </form>
       <button type="button" id="botReg">Registro</button>
    </div>
    
    <div class="Registro" id="Registro">
        <form method="post" action="../controladores/ControladorUsuarios.php">
            <h2>Crea una cuenta fácil</h2>
            
            <label for="nombre">Introduce tu nombre:</label>
            <input type="text" id="name" name="name">
            
            <label for="newUser">Introduce el nombre que deseas:</label>
            <input type="text" id="usr" name="usr"><br> 
            
            <label for="newUser">Introduce tu correo electronico:</label>
            <input type="text" id="email" name="email"><br>
            
            <label for="password">Introduce una contraseña que recuerdes:</label>
            <input type="password" id="pass" name="pass"><br>
            
            <button type="submit" id="enviarReg" name="option" value="3">Enviar</button>
        </form>
        <button type="button" id="botLog">Log</button>
    </div>
    <script>
        $("#Registro").hide();//asi se oculta un elemento
        $("#botReg").click(
            function(){
                $("#log").hide();
                $("#Registro").show();
            });

        $("#botLog").click(
            function(){
                $("#Registro").hide();
                $("#log").show();
            });
    </script>
<!-- 
    <script>
        $("#enviarReg").click.function() {
            location.reload();
        }
    </script> -->

    <script>
        $("#idiom").change(function(){
            let newIdioma = $(this).val();
            
            $.ajax({
                type: "POST",
                url: "../sec/cambio_idioma.php",
                data:{
                    idioma: newIdioma
                },
                success: function(){
                    location.reload();
                }
            })
        });
    </script>
<?php } ?>
</body>

</html>