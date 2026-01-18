<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    /*
    //uso del for
    $a = 1;// 0 y 1 toman el valor de false y true
    if($a == true){
        echo "es true <br>";
    }else{
        echo "es false <br>";
    }

    if($a == 1){
    echo "<img src='img/1.JPG'/>";
    }else{
        echo "<img src='img/0.JPG'/> ";
    }

    switch($a){
        case 0:
            echo "<img src='img/0.JPG'/>";
            break;
        case 1:
            echo "<img src='img/1.JPG'/>";
            break;

        default:
        break;
    }
    */
    /*
    for($i=0; $i<10; $i++){
        echo $i." ";
    }
    echo "<br>";
    for($i=10; $i>0; $i--){
        echo $i." ";
    }

    for($i=0; $i<3; $i++){
        echo "<img src=' img/".$i.".JPG ' />";
    }
       */

    //uso del while
    $i = 0;
    while($i <= 10){
        echo $i;
        $i++;
    }
    echo "<br>";
    //do while
    $i = 0;
    do{
        echo $i;
        $i++;
    }while($i <= 10);

    //for each
    echo "<br>";
    $ar = array("a","b","c","d","e","f");
    foreach($ar as $var){
        echo $var;
    }

    //get y post
    ?>
    <!--
  <img src=" <?php if($a == 1){
        echo 'img/'.'0.JPG' ; 
        } ?>">
  <img src="<?php echo 'img/'.$a.'.JPG'; ?>"/>
    -->
  <!--Mejor opcion para usar un for
  <?php for($i=0; $i<3; $i++){ ?>
  <img src= '<?php echo "img/$i.JPG"; ?> '/>
  <?php }?>-->
  <form method="get">
    <label for="user">Usuario:</label>
    <input type="text" id="user" name="user">
    <label for="password">Contraseña:</label>
    <input type="text" id="password" name="password">
    <button type="submit">Enviar</button>
  
  

  </form>
</body> 
</html>