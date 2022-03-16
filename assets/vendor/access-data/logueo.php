<?php
    error_reporting(0);
    session_start();

    if(file_exists('../config/dbconnection.php')){
      include ('../config/dbconnection.php' );
      echo 1;
    }else{
      die();
    }

    function AccessLogin(){
        $pdo=cnx_db_restaurant();
        $user=htmlentities(addslashes($_POST['username']));
        $password=htmlentities(addslashes($_POST['password']));
        $contador=0;
        $sql="SELECT e.id_employee, e.password_employee, e.name_employee, e.lastname_employee, e.id_position, p.name_position, e.status_employee, e.email_employee FROM employee e join position_employee p on e.id_position=p.id_position WHERE e.username_employee=:user";
        $result=$pdo->prepare($sql);
        $result->execute(array(":user"=>$user));
        while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
            if($row['status_employee']==1){
                if(password_verify($password,$row['password_employee'])){
                    $contador++;
                    $_SESSION['timesession']=time();
                    $_SESSION['login']=true;
                    $_SESSION['user']=$row['id_employee'];
                    $_SESSION['username']=$user;
                    $_SESSION['name']=$row['name_employee'];
                    $_SESSION['lastname']=$row['lastname_employee'];
                    $_SESSION['position']=$row['id_position'];
                    $_SESSION['postname']=$row['name_position'];
                    $_SESSION['emailuser']=$row['email_employee'];
                }
            }            
        }
        if($contador>0){
            header('location:../../../home.php');
        }else{
            header('location:../../../index.php');
        }
        $result->closeCursor();
        $pdo=null;
    }

    function LogOut(){
        error_reporting(0);
        session_start();
        session_destroy();
        header('location:../../../index.php');
        die();
    }

    $action=htmlentities(addslashes($_POST['action']));
    switch ($action) {
        case "login": echo AccessLogin(); break;
        case "logout": echo LogOut(); break;
    }
?>