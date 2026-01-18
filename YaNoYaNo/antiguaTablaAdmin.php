<?php 
session_start(); 
if(empty($_SESSION["Level"])){
    header("Location:login.php");
}


    if($_SESSION["Level"]==0){
     $idioma = simplexml_load_file("../locales/".$_SESSION["idiom"].".xml");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    

    <?php
        $fp = fopen("../sec/users.txt","r");
    ?>
    
<div id="Tabla">
    <table border="1px solid black" align="center">
     <tr>
        <th><?php echo $idioma->palabras->seleccion ?></th>
        <th>id</th>
        <th><?php echo $idioma->palabras->usuario ?></th>
        <th><?php echo $idioma->palabras->contrasena ?></th>
        <th><?php echo $idioma->palabras->nombre ?></th>
        <th><?php echo $idioma->palabras->nivel ?></th>
     </tr>
     <?php 
     $vec;
     while($line = fgets($fp)){ 
        $vec = explode("#",$line);
     ?>
     <tr>
        <td ><input type="checkbox" laid="<?php echo $vec[0]?>" class="checker"></td>
        <td class="LAid"><?php echo $vec[0] ?></td>
        <td><?php echo $vec[1] ?></td>
        <td><input type="text" value= "<?php echo $vec[2] ?>" id="pass_<?php echo $vec[0]?>" > </td>
        <td><input type="text" value= "<?php echo $vec[3] ?>" id="nombre_<?php echo $vec[0]?>" > </td>
        <td><input type="text" value= "<?php echo $vec[4] ?>" id="level_<?php echo $vec[0]?>"> </td>
        <td><button type="button" class="BotUP" idup="<?php echo $vec[0] ?>"> <?php echo $idioma->botones->act ?> </button></td>
     </tr>
    <?php } fclose($fp)?>
    <tr>
        <td></td>
        <td></td>
        <td><input type="text" id="usr_nuevo"></td>
        <td><input type="text" id="pass_nuevo"></td>
        <td><input type="text" id="nombre_nuevo"></td>
        <td><input type="text" id="nivel_nuevo"></td>
        <td><button type="button" id="BotAnadir"> <?php echo $idioma->botones->anadir ?> </button></td>
    </tr>
    </table>
    <button type="submit" id="BotBorrar"> <?php echo $idioma->botones->borrar ?> </button>
    <!-- <button type="submit" id="BotAnadir">Añadir</button>-->
</div>
</body>
<script>
    $("#BotBorrar").click(function(){
        let lasids =[];
        $(".checker").each(function(){
            if($(this).is(":checked")){
                lasids.push($(this).attr("laid"))
            }
        });
        
            for(let i=0; i< lasids.length; i++){
                $.ajax({
                    type :"POST",
                    //url : "modelos/users.php",
                    url : "controladores/ControladorUsuarios.php",
                    data: { laid:lasids[i],
                            level: $("#level_"+lasids[i]).val(),
                            option:1},
                    success: function(){
                        location.reload();
                    }
                });
            }
        
    });
</script>
<script>
    $(".BotUP").click(function(){
        let laid = $(this).attr("idup");
        let nombre = $("#nombre_"+laid).val() ;
        let pass= $("#pass_"+laid).val() ;
        let level=$("#level_"+laid).val();

         if(level == "" || pass == "" || nombre == ""){
            alert("NO PUEDES METER ATRIBUTOS VACIOS");
        }else{

            $.ajax({
                type : "POST",
                //url : "modelos/update.php", 
                url : "controladores/ControladorUsuarios.php",
                data: { id: laid , 
                        pass: pass ,
                        name: nombre ,
                        level: level,
                        option: 2}
            })
        }
    });
</script>


<script>
    $("#BotAnadir").click(function(){
        /* para hacerlo pidiendolo directamente pero lo vamos a hacer con la tabla
        let nombreUsuario =  prompt("Introduce el nuevo usuario:");
        let pass =  prompt("Introduce la contraseña del nuevo usuario:");
        let nombre =  prompt("Introduce el nombre del nuevo usuario:");
        */
       let nombreUsuario = $("#usr_nuevo").val();
       let pass = $("#pass_nuevo").val();
       let nombre = $("#nombre_nuevo").val();
       let nivel = $("#nivel_nuevo").val();
         if(nombreUsuario == "" || pass == "" || nombre == ""||nivel == ""){
            alert("NO PUEDES METER ATRIBUTOS VACIOS");
        }else{

            if( (nombreUsuario.length < 19) || (pass.length <19) || (nombre.length < 31) || (nivel == 0 || nivel == 1  ) ){

                $.ajax({
                    type : "POST",
                    url : "controladores/ControladorUsuarios.php",
                    data: { pass: pass ,
                            name: nombre ,
                            usr: nombreUsuario,
                            level: nivel,
                            option: 3},
                    succes: function(){
                        location.reload();
                    }
                        
                })
            }
        }
    });
</script>
<?php } ?>