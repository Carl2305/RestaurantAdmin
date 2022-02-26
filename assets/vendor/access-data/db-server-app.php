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

   
    function AllOrders(){
        $pdo=cnx_db_restaurant();
        $date=date("Y-m-d");
        $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order, o.status_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE DATE(datetime_order)='$date' ORDER BY o.status_order ASC";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        return $result;
        $pdo=null;
    }

    function SalesForTypeTime($type){
        $date=date("Y-m-d");
        $query="";
        if($type!=null||$type!=""){
            if($type=="today"){
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE o.status_order=1 and DATE(datetime_order)='$date'";
            }else if($type=="month"){
                $date=date("m");
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE o.status_order=1 and MONTH(datetime_order)=$date";
            }else if($type=="year"){
                $date=date("Y");
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE o.status_order=1 and YEAR(datetime_order)=$date";
            }else{
                $date=$type;
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE o.status_order=1 and DATE(datetime_order)='$date'";
            }
            $pdo=cnx_db_restaurant();
            $resul=$pdo->prepare($query);
            $resul->execute();
            $data=$resul->fetchAll(PDO::FETCH_ASSOC);
            $result= json_encode($data, JSON_UNESCAPED_UNICODE);
            return $result;
            $pdo=null;
        }else{
            return null;
        }
    }

    function OrdersForTypeTime($type){
        $date=date("Y-m-d");
        $query="";
        if($type!=null||$type!=""){
            if($type=="today"){
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order, o.status_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE DATE(datetime_order)='$date'";
            }else if($type=="month"){
                $date=date("m");
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order, o.status_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE MONTH(datetime_order)=$date";
            }else if($type=="year"){
                $date=date("Y");
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order, o.status_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE YEAR(datetime_order)=$date";
            }else{
                $date=$type;
                $query="SELECT o.id_order, c.name_client, c.phone_client, c.address_client, c.reference_address_client, o.datetime_order, o.total_order, o.status_order FROM order_restaurant o JOIN client_restaurant c on o.id_client=c.id_client WHERE DATE(datetime_order)='$date'";
            }
            $pdo=cnx_db_restaurant();
            $resul=$pdo->prepare($query);
            $resul->execute();
            $data=$resul->fetchAll(PDO::FETCH_ASSOC);
            $result= json_encode($data, JSON_UNESCAPED_UNICODE);
            return $result;
            $pdo=null;
        }else{
            return null;
        }
    }
    
    function SearchUser($code){
        $pdo=cnx_db_restaurant();
        $query="SELECT name_employee, lastname_employee, email_employee, id_position  FROM employee_restaurant WHERE id_employee = $code";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        return $result;
        $pdo=null;
    }

    $typeAction=$_POST['type'];
    $typeTime="today";
    if($_POST['timer']!=null){
        $typeTime=$_POST['timer'];
    }
    switch ($typeAction) {
        case "home": echo AllOrders(); break;
        case "sale": echo SalesForTypeTime($typeTime); break;
        case "order": echo OrdersForTypeTime($typeTime); break;
        case "search": echo SearchUser(intval($typeTime)); break;
    }

?>