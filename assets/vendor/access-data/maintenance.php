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

    function AllUsers(){
        $cargo_session=$_SESSION['position'];
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $iduser=$_SESSION['user']; // esto tiene que estar en la session
            $query="SELECT e.id_employee, e.name_employee, e.lastname_employee, e.email_employee, p.name_position, e.status_employee from employee_restaurant e JOIN position_restaurant p on e.id_position=p.id_position where id_employee!= $iduser";
            $resul=$pdo->prepare($query);
            $resul->execute();
            $data=$resul->fetchAll(PDO::FETCH_ASSOC);
            $result= json_encode($data, JSON_UNESCAPED_UNICODE);
            return $result;
            $pdo=null;
        }
    }


    function createUserEmployee($nam,$last,$mail,$usern,$nnpass,$cpass,$post){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        if($cargo_session==1){
            if($nnpass==$cpass){
                $npass=password_hash($nnpass,PASSWORD_DEFAULT);
                $flag=0;
                $pdo=cnx_db_restaurant();
                $sql="INSERT INTO employee_restaurant (id_employee, name_employee, lastname_employee, email_employee, username_employee, password_employee, id_position, status_employee) VALUES (NULL, '$nam', '$last', '$mail', '$usern', '$npass', $post, 1)";
                $result=$pdo->prepare($sql);
                $flag=$result->execute();
                if($flag==1){
                    return 4;
                }else{
                    return 3;
                }
            }else{
                return 2;
            }
        }else{
            return 1;
        }
    }

    function updateUserEmployee($code,$nam,$last,$mail,$post){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        if($cargo_session==1){
            $flag=0;
            $pdo=cnx_db_restaurant();
            $sql="update employee_restaurant set name_employee='$nam', lastname_employee='$last', email_employee='$mail', id_position=$post where id_employee=$code";
            $result=$pdo->prepare($sql);
            $flag=$result->execute();
            if($flag==1){
                return 3;
            }else{
                return 2;
            }
        }else{
            return 1;
        }
    }

    function deleteUsers($code){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $query="update employee_restaurant set status_employee=0 WHERE id_employee=$code";
            $resul=$pdo->prepare($query);
            $flag=$resul->execute();
            if($flag==1){
                $pdo=null;
                return 3;
            }else{
                $pdo=null;
                return 2;
            }
        }else{
            return 1;
        }
    }


    $typeAction="";
    $name="";
    $lastname="";
    $email="";
    $username="";
    $newPassword="";
    $ConfirmPassword="";
    $position=0;
    
    try {
        if($_POST['type']!=null){
            $typeAction=$_POST['type'];
        }
        if($_POST['name']!=null){
            $name=$_POST['name'];
        }
        if($_POST['last']!=null){
            $lastname=$_POST['last'];
        }
        if($_POST['mail']!=null){
            $email=$_POST['mail'];
        }
        if($_POST['user']!=null){
            $username=$_POST['user'];
        }
        if($_POST['npass']!=null){
            $newPassword=$_POST['npass'];
        }
        if($_POST['cpass']!=null){
            $ConfirmPassword=$_POST['cpass'];
        }
        if($_POST['posit']!=null){
            $position=$_POST['posit'];
        }
    } catch (Throwable $th) { }
    
    switch ($typeAction) {
        case "list": echo AllUsers(); break;
        case "create": 
            echo createUserEmployee($name,$lastname,$email,$username,$newPassword,$ConfirmPassword,$position); 
            break;
        //case "update": echo 'update'; break;
        case "delete": echo deleteUsers($position); break;
        case "upass": echo 'update password'; break;
        case "update": 
            echo updateUserEmployee(intval($username),$name,$lastname,$email,$position); 
            break;
        default : echo 0; break;
    }
?>