<?php

function cnx_db_restaurant(){
    $servidor='mysql:host=localhost;dbname=restaurant';
    $user='root';
    $password='';

    try{
        $pdo=new PDO($servidor, $user, $password);	
        return $pdo;
    }catch(PDOException $e){
        print '¡Error!: ' . $e->getMessage() . "<br/>";
        die();
    }
}

?>