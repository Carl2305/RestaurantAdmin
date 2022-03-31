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
   
    function AllOrders(){
        $pdo=cnx_db_restaurant();
        $date=date("Y-m-d");
        $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.status_order, o.type_order  FROM orders o JOIN client c on o.id_client=c.id_client WHERE DATE(o.datetime_order)='$date' ORDER BY o.status_order ASC";
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
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE o.status_order=1 and DATE(o.datetime_order)='$date'";
            }else if($type=="month"){
                $date=date("m");
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE o.status_order=1 and MONTH(o.datetime_order)=$date";
            }else if($type=="year"){
                $date=date("Y");
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE o.status_order=1 and YEAR(o.datetime_order)=$date";
            }else{
                $date=$type;
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE o.status_order=1 and DATE(o.datetime_order)='$date'";
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
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.status_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE DATE(o.datetime_order)='$date'";
            }else if($type=="month"){
                $date=date("m");
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.status_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE MONTH(o.datetime_order)=$date";
            }else if($type=="year"){
                $date=date("Y");
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.status_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE YEAR(o.datetime_order)=$date";
            }else{
                $date=$type;
                $query="SELECT o.id_order, c.name_client, c.phone_client, o.address_order, o.reference_address_order, o.datetime_order, o.total_order, o.status_order, o.type_order FROM orders o JOIN client c on o.id_client=c.id_client WHERE DATE(o.datetime_order)='$date'";
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
        $query="SELECT name_employee, lastname_employee, email_employee, id_position  FROM employee WHERE id_employee = $code";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        return $result;
        $pdo=null;
    }

    function UpdateUser(){
        $nam=$_POST['firstName'];
        $last=$_POST['lastName'];
        $mail=$_POST['email'];
        $code=$_SESSION['user'];
        $flag=0;
        $pdo=cnx_db_restaurant();
        $sql="update employee set name_employee='$nam', lastname_employee='$last', email_employee='$mail' where id_employee=$code";
        $result=$pdo->prepare($sql);
        $flag=$result->execute();
        if($flag==1){
            $pdo=null;
            $pdo=cnx_db_restaurant();
            $contador=0;
            $sql="SELECT name_employee, lastname_employee, email_employee FROM employee WHERE id_employee=:user";
            $result=$pdo->prepare($sql);
            $result->execute(array(":user"=>$code));
            while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['name']=$row['name_employee'];
                $_SESSION['lastname']=$row['lastname_employee'];
                $_SESSION['emailuser']=$row['email_employee'];
            }
            return 1;
        }else{
            return 0;
        }
    }

    function ValidatePassword($user){
        $pdo=cnx_db_restaurant();
        $contador=0;
        $hash="";
        $sql="SELECT password_employee FROM employee WHERE id_employee=:user";
        $result=$pdo->prepare($sql);
        $result->execute(array(":user"=>$user));
        while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
            $contador++;
            $hash=$row['password_employee'];
        }
        $result->closeCursor();
        $pdo=null;
        if($contador>0){
            return $hash;
        }else{
            return null;
        }
    }

    function UpdatePassword(){
        $password=$_POST['pass'];
        $newpassword=$_POST['newpass'];
        $renewpassword=$_POST['renewpass'];
        $user=$_SESSION['user'];
        if(($password!=null||$password!="")&&($newpassword!=null||$newpassword!="")&&($renewpassword!=null||$renewpassword!="")){
            if(password_verify($password,ValidatePassword($user))){
                if($newpassword==$renewpassword){
                    $nam=password_hash($newpassword,PASSWORD_DEFAULT);
                    $flag=0;
                    $pdo=cnx_db_restaurant();
                    $sql="update employee set password_employee='$nam' where id_employee=$user";
                    $result=$pdo->prepare($sql);
                    $flag=$result->execute();
                    if($flag==1){
                        session_start();
                        session_destroy();
                        return 4;
                        die();
                    }else{
                        return 3;
                    }
                }else{
                    return 2;
                }
            }else{
                return 1;
            }
        }else{
            return 0;
        }
    }

    function searchDishForOrder(){
        $pdo=cnx_db_restaurant();
        $key=$_POST['textsearch'];
        $query="SELECT d.id_dish, d.code_dish, d.name_dish, d.description_dish, d.url_image_dish, d.price_dish, c.name_category 
        FROM dish d join category_dish c on d.id_category=c.id_category WHERE d.status_dish=1 and (d.name_dish like('%$key%') or d.description_dish like('%$key%') or c.name_category like('%$key%') or c.description_category like('%$key%'))";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        $pdo=null;
        //$_SESSION['timesession']=time();
        return $result;
    }

    function AllBoards(){
        $pdo=cnx_db_restaurant();
        $query="SELECT id_board, code_board, capacity_board, status_board FROM board";
        $resul=$pdo->prepare($query);
        $resul->execute();
        $data=$resul->fetchAll(PDO::FETCH_ASSOC);
        $result= json_encode($data, JSON_UNESCAPED_UNICODE);
        $pdo=null;
        return $result;
    }

    function sum_total_order($data){
        $total=0.0;
        foreach ($data as $value){
           $precio=floatval($value->precio);
           $total+=$precio;
        }
        return $total;
    }

    function changeStatusBoard($id_board){
        $pdo=cnx_db_restaurant();
        $sql="update board set status_board=1 where id_board=$id_board";
        $result=$pdo->prepare($sql);
        $result->execute();
        $pdo=null;
    }

    function saveOrderLocal(){
        $data_order=$_POST['dataorder'];
        $id_board=$_POST['board'];
        $code_board=$_POST['codboard'];
        if($data_order!=null||$data_order!=""){
            $data_detail_order=json_decode($data_order);
            $pdo=cnx_db_restaurant();
            $date=date("Y/m/d H:i:s");
            $total=sum_total_order($data_detail_order);
            $sql="INSERT INTO orders (id_order, id_client, id_employee, datetime_order, total_order, address_order, reference_address_order, status_order, type_order, id_board) 
                VALUES (null, 1, null, '$date', $total, '', '', '0', '0', (SELECT id_board FROM board WHERE code_board='$code_board' AND id_board=$id_board))";
            $result=$pdo->prepare($sql);
            $flag=$result->execute();
            if($flag==1){
               $id_order = $pdo->lastInsertId();
               changeStatusBoard($id_board);
               return insert_Order_Detail($id_order,$data_detail_order);
            }else{
               return 0;
            }
            $pdo=null;
        }else{
            return 0;
        }        
    }

    function insert_Order_Detail($id_order,$data_order){
        if($data_order!=null||$data_order!=""){
           $pdo=null;
           $pdo=cnx_db_restaurant();
           $flag=0;
           foreach ($data_order as $value){
              $precio=floatval($value->precio);
              $sql="INSERT INTO order_detail (id_order_detail, id_order, id_dish, price_dish, amount_dish, comment_dish) 
              VALUES (null, '$id_order', (select d.id_dish from dish d where d.code_dish='$value->codigo' AND d.id_dish=$value->id), '$precio', 1, '$value->comentario'); ";
              $result=$pdo->prepare($sql);
              $flag+=$result->execute();
           }
           if($flag>0){
              return 1;
           }else{
              return 0;
           }
           $pdo=null;
        }
        return 0;
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
        case "update": echo UpdateUser(); break;
        case "uptpass": echo UpdatePassword(); break;
        case "searchdishlocal": echo searchDishForOrder(); break;
        case "listboards": echo AllBoards(); break;
        case "savelocalorder": echo saveOrderLocal(); break;
    }

?>