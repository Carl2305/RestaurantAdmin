<?php
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

   
    function ShowDetailOrder($code){
        $pdo=cnx_db_restaurant();
        $query="SELECT code_dish, name_dish, description_dish, url_image_dish, price_dish, amount_dish, comment_dish FROM order_detail_restaurant WHERE id_order=$code";
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