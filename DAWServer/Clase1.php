<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $_nombre = "Asier";//declarar una variable o constante(en las constantes usar _ para distinguir)
        $_Apellido = "Hernández Parra";
        echo "Bienvenido ".$_nombre."<br>";//mostrar por pantalla(el punto sirve para concatenar) el br es el salto de linea
        echo "Hola ".$_nombre." ".$_Apellido." q tal estas<br>";
        echo "Hola $_nombre<br>";
        
        
        $cole = array(//Así se declara un array numerado, tamnien se puede en vez de poner array() solo []
            0=>"Aula1",
            1=>"Biblio",
            2=>"Secretaria",
            3=>"Director"
        );//Así se declara un array

        echo "Estamos en ".$cole[1]."<br>";
        $coches = ["Audi","Lexus","BMW","Mercedes"];//mejor pa declarar un array
        echo "Me he comprado un ".$coches[1]."<br>";

        //Declaracion de un array en clave-valor
        $cesur = [
            "Persona pesada"=> "Marina",
            "Profe despistado"=> "Carlos",
            2=>"Rosa",
        ];
        echo $cesur["Persona pesada"]."<br>";
        echo $cesur[2]."<br>";
       

        $cole[]="carcel"; //Añadir un valor al array
        echo $cole[4]."<br>";
        
        $cesur[] = "Patri";
        echo $cesur[3]."<br>";

        var_dump($cesur);//mostrar  el array
        echo "<br>";

        unset($cesur[3]);
        var_dump($cesur);
        echo "<br>";

        $aulas = [
            "Planta1"=>["aula1","aula2","aula3"],
            "Planta2"=>["Clase1","Clase2"],
            "Planta3"=>["Direccion","Biblioteca","Secretaria"]
        ];

        var_dump($aulas);
        echo "<br>";

        echo $aulas["Planta1"][0]."<br>";
        
    ?>
</body>
</html>