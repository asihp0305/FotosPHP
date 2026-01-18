<?php
    $ID = $_POST["id"];
    $Pass = $_POST["pass"];
    $Name = $_POST["name"];
    $Level = $_POST["level"];

    $fp = fopen("../sec/users.txt","r+");
    $flag = 0;

    while( ($flag == 0) && ($line = fgets($fp)) ){
        $vec = explode("#",$line);
        if( $vec[0] == $ID){
            
            $vec[2] = $Pass;
            $vec[3] = $Name;
            $vec[4] = $Level;
            $act = implode("#",$vec).PHP_EOL;
            
            //$temp = $vec[0]."#".$vec[1]."#".$Pass."#".$Name."#".$Level.PHP_EOL;

            $flag = 1;
        }
    }

    fclose($fp);

    $searchString = $line; // el fragmento de texto a borrar o actualizar (en nuestro caso el fgets o la cadena entera que hemos enviado por la red)

    $string = file_get_contents("../sec/users.txt");// volvamos sobre la variable el archivo completo

    $offset = strpos($string, $searchString);// pillamos la posicion de lo que vamos a borrar o actualizar

    $part1 = substr($string, 0, $offset);//pillamos la parte previa a donde empieza lo que queremos borrar( es decir hasta donde empieza)

    $part2 = substr($string, $offset + strlen($searchString));//pillamos la parte posterior a lo que queremos borrar( osea desde donde acaba)

    file_put_contents("../sec/users.txt", $part1 .$act .$part2); //pegamos ambas partes en el archivo
    
?>