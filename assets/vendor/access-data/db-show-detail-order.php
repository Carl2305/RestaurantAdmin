<?php
    error_reporting(0);
    session_start();
    $varsesion=$_SESSION['login'];
    if($varsesion==null||$varsesion==''||$varsesion!=true){
      header('location:../../../index.php');
      die();
    }
    if(isset($_SESSION['timesession'])){
        $disable=900;
        $life_session = time() - $_SESSION['timesession'];
        if($life_session>$disable){
          session_destroy();
          die();
        }
    }else{
        $_SESSION['timesession']=time();
    }
    header('Content-type: application/json; charset=utf-8');
    
    if(file_exists('../config/dbconnection.php')){
        include ('../config/dbconnection.php' );
      }else{
        die();
      }
   
    function ShowDetailOrder($code){
        $pdo=cnx_db_restaurant();
        $query="SELECT d.code_dish, d.name_dish, d.description_dish, d.url_image_dish, d.price_dish, o.amount_dish, o.comment_dish FROM order_detail o join dish d on o.id_dish=d.id_dish WHERE o.id_order=$code";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        return $result;
        $pdo=null;
    }

    $code_order=$_POST['code_order'];
    echo ShowDetailOrder($code_order);

?>