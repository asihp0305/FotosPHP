<?php
class usuario{
    /**
     * PRE:{Entra la id del usuario y su nivel}
     * POST:{elimina al usuario al q pertenece la id mientras q no sea el unico admin} 
     */
    public function BorrarUsuario($id, $level){
         
        $fp = fopen("../sec/users.txt","r");
        
        if($level == 0){
        $admins = 0;

        while(($line=fgets($fp))){
            $vec = explode("#",$line);
            if($vec[4] == 0){
                $admins++;
            }
        }
        if($admins > 1){
            rewind($fp);
            $flag = 0; 
            while(($flag == 0) && ($line=fgets($fp))){
                $vec = explode("#",$line);
                if($vec[0] == $id){
                    $flag = 1;
                }
            }
            fclose($fp);
            $searchString = $line; // el fragmento de texto a borrar o actualizar (en nuestro caso el fgets o la cadena entera que hemos enviado por la red)

            $string = file_get_contents("../sec/users.txt");// volvamos sobre la variable el archivo completo

            $offset = strpos($string, $searchString);// pillamos la posicion de lo que vamos a borrar o actualizar

            $part1 = substr($string, 0, $offset);//pillamos la parte previa a donde empieza lo que queremos borrar( es decir hasta donde empieza)

            $part2 = substr($string, $offset + strlen($searchString));//pillamos la parte posterior a lo que queremos borrar( osea desde donde acaba)

            file_put_contents("../sec/users.txt", $part1 . $part2); //pegamos ambas partes en el archivo
        }
        

    }else{
        $flag = 0; 
        while(($flag == 0) && ($line=fgets($fp))){
            $vec = explode("#",$line);
            if($vec[0] == $id){
                $flag = 1;
            }
        }
        fclose($fp);
        $searchString = $line; // el fragmento de texto a borrar o actualizar (en nuestro caso el fgets o la cadena entera que hemos enviado por la red)

        $string = file_get_contents("../sec/users.txt");// volvamos sobre la variable el archivo completo

        $offset = strpos($string, $searchString);// pillamos la posicion de lo que vamos a borrar o actualizar

        $part1 = substr($string, 0, $offset);//pillamos la parte previa a donde empieza lo que queremos borrar( es decir hasta donde empieza)

        $part2 = substr($string, $offset + strlen($searchString));//pillamos la parte posterior a lo que queremos borrar( osea desde donde acaba)

        file_put_contents("../sec/users.txt", $part1 . $part2); //pegamos ambas partes en el archivo
    }

        
    }

    /**
     * PRE:{Entran la id la contraseña el nombre y el nivel del usuario a modificar}
     * POST:{Modifica los datos q han entrado a no ser q el 
     * usuario sea un admin en cuyo caso no se modifica el nivel}
     */
    public function UpdateUsuario($ID,$Pass,$Name,$Level){
             $fp = fopen("../sec/users.txt","r+");
        $flag = 0;

        while( ($flag == 0) && ($line = fgets($fp)) ){
            $vec = explode("#",$line);
            if( $vec[0] == $ID){
                
                $vec[2] = $Pass;
                $vec[3] = $Name;
                if($vec[4] == 0){
                    $act = implode("#",$vec).PHP_EOL;
                }else{
                    $vec[4] = $Level;
                    $act = implode("#",$vec).PHP_EOL;
                }
                
                
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
    }

    public function AddUsuario($nombreUsr,$Pass,$Name,$Level){
       
            $fp = fopen("../sec/users.txt","r");//abre el users.txt en modo lectura
            $bandera = 0;

            while(($line = fgets($fp) ) && ($bandera != 1)){//en este bucle se recorre el users.txt 
                $vec = explode("#",$line);//almacena en vec un array con el contenido de la linea [id,user,pass,nombre,1]
            
                if($vec[1]==$user){
                    $bandera=1;
                }else{
                    $aux = $vec[0];//almacena en aux la posición 0 q coincide con el id
                }
            
            }

            if($bandera == 0){

            $id= $aux+1;//suma 1 al contador ya q es el siguiente al último
            $temp = $id."#".$nombreUsr."#". $Pass."#".$Name."#".$Level;//concatenacion de toda la informacion adquirida y el valor 1 ya q es el numero para no admins 
            fclose($fp);//cerramos el users.txt

            $fp = fopen("../sec/users.txt","a");//abrimos el users.txt en modo escritura(con w sobreescribe)
            fwrite($fp,"\r\n".$temp);//se hace un salto de linea y se introduce la concatenación antes hecha
            fclose($fp);//se cierra el users.txt
            }
        } 
}

?>