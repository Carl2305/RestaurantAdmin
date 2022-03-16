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
    
    if(file_exists('../config/dbconnection.php')){
        include ('../config/dbconnection.php' );
      }else{
        die();
      }

    function ValidatePassword(){
        $pdo=cnx_db_restaurant();
        $user=$_SESSION['user'];
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

    function ValidateUserPassword_UpdateOrder($password, $idOrder, $type){
        if(($password!=null||$password!="")&&($idOrder!=null||$idOrder!="")){
            $idusername=$_SESSION['user'];
            if(password_verify($password,ValidatePassword())){
                if($type=='check'){
                    return updateStatusCheckOrder($idusername,$idOrder);
                }else if ($type=='cancel'){
                    return updateStatusCancelOrder($idusername,$idOrder);
                }
            }else{
                return 2;
            }
        }
    }

    function updateStatusCheckOrder($idUser,$idOrder){
        $pdo=cnx_db_restaurant();
        $query="update orders set id_employee=$idUser, status_order=1 WHERE id_order=$idOrder";
        $resul=$pdo->prepare($query);
        $flag=$resul->execute();
        if($flag==1){
            $pdo=null;
            return 1;
        }else{
            $pdo=null;
            return 0;
        }        
    }

    function updateStatusCancelOrder($idUser,$idOrder){
        $pdo=cnx_db_restaurant();
        $query="update orders set id_employee=$idUser, status_order=2 WHERE id_order=$idOrder";
        $resul=$pdo->prepare($query);
        $flag=$resul->execute();
        if($flag==1){
            $pdo=null;
            return 1;
        }else{
            $pdo=null;
            return 0;
        }        
    }


    $pass=$_POST['pwd'];
    $codeOrder=$_POST['code'];
    $typeAction=$_POST['type'];
    echo ValidateUserPassword_UpdateOrder($pass,$codeOrder,$typeAction);
    

?>