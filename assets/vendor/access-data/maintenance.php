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

    // employees
    function AllUsers(){
        $cargo_session=$_SESSION['position'];
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $iduser=$_SESSION['user']; // esto tiene que estar en la session
            $query="SELECT e.id_employee, e.name_employee, e.lastname_employee, e.email_employee, p.name_position, e.status_employee from employee e JOIN position_employee p on e.id_position=p.id_position where id_employee!= $iduser";
            $resul=$pdo->prepare($query);
            $resul->execute();
            $data=$resul->fetchAll(PDO::FETCH_ASSOC);
            $result= json_encode($data, JSON_UNESCAPED_UNICODE);
            return $result;
            $pdo=null;
        }
    }

    function createUserEmployee(){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        $nam=$_POST['name'];
        $last=$_POST['last'];
        $mail=$_POST['mail'];
        $usern=$_POST['user'];
        $nnpass=$_POST['npass'];
        $cpass=$_POST['cpass'];
        $post=$_POST['posit'];
        if($cargo_session==1){
            if($nnpass==$cpass){
                $npass=password_hash($nnpass,PASSWORD_DEFAULT);
                $flag=0;
                $pdo=cnx_db_restaurant();
                $sql="INSERT INTO employee (id_employee, name_employee, lastname_employee, email_employee, username_employee, password_employee, id_position, status_employee) VALUES (NULL, '$nam', '$last', '$mail', '$usern', '$npass', $post, 1)";
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

    function updateUserEmployee(){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        $code=intval($_POST['user']);
        $nam=$_POST['name'];
        $last=$_POST['last'];
        $mail=$_POST['mail'];
        $post=$_POST['posit'];
        if($cargo_session==1){
            $flag=0;
            $pdo=cnx_db_restaurant();
            $sql="update employee set name_employee='$nam', lastname_employee='$last', email_employee='$mail', id_position=$post where id_employee=$code";
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

    function deleteUsers(){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        $code=$_POST['posit'];
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $query="update employee set status_employee=0 WHERE id_employee=$code";
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

    // dishes
    function AllDishes(){
        $cargo_session=$_SESSION['position'];
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $query="SELECT d.id_dish, d.code_dish, d.id_category, c.name_category, d.name_dish, d.description_dish, d.url_image_dish, d.price_dish, d.status_dish FROM dish d join category_dish c on d.id_category=c.id_category ORDER BY d.id_dish ASC";
            $resul=$pdo->prepare($query);
            $resul->execute();
            $data=$resul->fetchAll(PDO::FETCH_ASSOC);
            $result= json_encode($data, JSON_UNESCAPED_UNICODE);
            $pdo=null;
            return $result;
        }
    }

    function deleteDishes(){
        $cargo_session=$_SESSION['position']; // esto tiene que estar en la session
        $code=$_POST['code'];
        $id=$_POST['dish'];
        if($cargo_session==1){
            $pdo=cnx_db_restaurant();
            $query="update dish set status_dish=0 WHERE id_dish=$id and code_dish='$code'";
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

    function loadImageServer(){
        // retorno de errores
        // E0: falta seleccionar una imagen
        // E1: no es una imagen
        // E2: el tamaño de la imagen excede el limite
        // E3: error al subir la imagen a la carpeta temporal
        // RUTA_IMG: imagen subida correctamente 

        $name_image=$_FILES['uploadimage']['name'];
        if($name_image==''){
            return 'E0';
        }else{
            $name_image=date("Ymd_His").'.'.pathinfo($name_image, PATHINFO_EXTENSION);
            $type_image=$_FILES['uploadimage']['type'];
            $size_image=$_FILES['uploadimage']['size'];
            if($type_image=="image/jpeg"||$type_image=="image/jpg"||$type_image=="image/png"||$type_image=="image/gif"){
                if($size_image<=15000000){
                    //ruta de la carpeta destino servidor
                    $target_file=$_SERVER['DOCUMENT_ROOT'].'/RestaurantAdmin/assets/img/menu/';
                    //movemos imagen del dir temporal al directorio escogido
                    $flag_move_file_upload=move_uploaded_file($_FILES['uploadimage']['tmp_name'],$target_file.$name_image);
                    if($flag_move_file_upload){
                        //return '../RestaurantAdmin/assets/img/menu/'.$name_image;
                        return 'assets/img/menu/'.$name_image;
                    }else{
                        return 'E3';
                    }
                }else{
                    return 'E2';
                }
            }else{
                return 'E1';
            }
        }
    }

    function updateDish(){
        $cargo_session=$_SESSION['position']; 
        $id_dish=$_POST['dish'];
        $code_dish=$_POST['dishcode'];
        $category=$_POST['category'];
        $name_dish=$_POST['namedish'];
        $descrip_dish=$_POST['descripdish'];
        $price_dish=$_POST['pricedish'];
        $upt_image=$_POST['imgpost'];
        if($cargo_session==1){
            $response_upload_image_server="";
            $flag_image=false;
            $url_image="";
            $sql="";
            if($upt_image=='true'){
                $response_upload_image_server=loadImageServer();
                switch ($response_upload_image_server) {
                    case 'E0': return 'Falta seleccionar una imagen'; break;
                    case 'E1': return 'No es una Imagen'; break;
                    case 'E2': return 'El tamaño de la imagen excede el limite'; break;
                    case 'E3': return 'Error al subir la imagen'; break;
                    default: 
                        $flag_image=true; 
                        $url_image=$response_upload_image_server; 
                        $sql="update dish set id_category=$category, name_dish='$name_dish', description_dish='$descrip_dish', url_image_dish='$url_image', price_dish=$price_dish WHERE id_dish=$id_dish and code_dish='$code_dish'";
                        break;
                }
            }else{
                $flag_image=true; 
                $sql="update dish set id_category=$category, name_dish='$name_dish', description_dish='$descrip_dish', price_dish=$price_dish WHERE id_dish=$id_dish and code_dish='$code_dish'";
            }
            if($flag_image){
                $pdo=cnx_db_restaurant();
                $result=$pdo->prepare($sql);
                $flag=$result->execute();
                if($flag==1){
                    return 'OK';
                }else{
                    return 'No se pudo Actualizar el platillo';
                }            
            }else{
                return 'No se pudo Actualizar el platillo, intenta más tarde';
            }  
        }else{
            return 'Usted no tiene Cargo de Administrador de Sistema';
        }
    }

    function createCodeDish(){
        $code="";
        $codeLimit=0;
        $pdo=cnx_db_restaurant();
        $sql="SELECT RIGHT(code_dish,4) as 'code' from dish order by id_dish desc LIMIT 1";
        $result=$pdo->prepare($sql);
        $result->execute();
        while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
            $codeLimit=intval($row['code'])+1;
        }
        if($codeLimit>0 && $codeLimit<10){
            $code="P000".$codeLimit;
        }else if($codeLimit>9 && $codeLimit<100){
            $code="P00".$codeLimit;
        }else if($codeLimit>99 && $codeLimit<1000){
            $code="P0".$codeLimit;
        }else if($codeLimit>999 && $codeLimit<10000){
            $code="P".$codeLimit;
        }
        return $code;
    }

    function createDish(){
        $cargo_session=$_SESSION['position']; 
        $code_dish=createCodeDish();
        $category=$_POST['ccategory'];
        $name_dish=$_POST['cnamedish'];
        $descrip_dish=$_POST['cdescripdish'];
        $price_dish=$_POST['cpricedish'];
        if($cargo_session==1){
            $response_upload_image_server=loadImageServer();
            $url_image="";
            $flag_image=false;
            switch ($response_upload_image_server) {
                case 'E0': return 'Falta seleccionar una imagen'; break;
                case 'E1': return 'No es una Imagen'; break;
                case 'E2': return 'El tamaño de la imagen excede el limite'; break;
                case 'E3': return 'Error al subir la imagen'; break;
                default: $flag_image=true; $url_image=$response_upload_image_server; break;
            }
            if($flag_image){
                $pdo=cnx_db_restaurant();
                $sql="INSERT INTO dish (id_dish, code_dish, id_category, name_dish, description_dish, url_image_dish, price_dish, status_dish)
                 VALUES (NULL, '$code_dish', $category, '$name_dish', '$descrip_dish', '$url_image', $price_dish, 1)";
                $result=$pdo->prepare($sql);
                $flag=$result->execute();
                if($flag==1){
                    return 'OK';
                }else{
                    return 'No se pudo Crear el platillo';
                }            
            }else{
                return 'No se pudo Crear el platillo, intenta más tarde';
            }  
        }else{
            return 'Usted no tiene Cargo de Administrador de Sistema';
        }
    }

    $typeAction="";
    
    try {
        if($_POST['type']!=null){
            $typeAction=$_POST['type'];
        }
    } catch (Throwable $th) { }
    
    switch ($typeAction) {
        case "list": echo AllUsers(); break;
        case "create": echo createUserEmployee(); break;
        //case "update": echo 'update'; break;
        case "delete": echo deleteUsers(); break;
        case "upass": echo 'update password'; break;
        case "update": echo updateUserEmployee(); break;
        // dishes
        case "listdish": echo AllDishes(); break;
        case "deletedish": echo deleteDishes(); break;
        case "updatedish": echo updateDish(); break;
        case "createdish": echo createDish(); break;
        default : echo 0; break;
    }
?>