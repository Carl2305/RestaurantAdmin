<?php

  function loadHeadPage($title){
    return '<head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
      <title>'.$title.' - RestaurantAdmin</title>
      <meta content="" name="description">
      <meta content="" name="keywords">
    
      <!-- Favicons -->
      <link href="assets/img/favicon.png" rel="icon">
      <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
      <!-- Google Fonts -->
      <link href="https://fonts.gstatic.com" rel="preconnect">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
      <!-- Vendor CSS Files -->
      <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
      <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
      <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
      <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
      <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
      <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    
      <!-- Template Main CSS File -->
      <link href="assets/css/style.css" rel="stylesheet">
    
    </head>';
  }

  function loadHeader($username,$namelastname,$positionname){
    return '<header id="header" class="header fixed-top d-flex align-items-center">

      <div class="d-flex align-items-center justify-content-between">
        <a href="home.php" class="logo d-flex align-items-center">
          <img src="assets/img/logo.png" alt="">
          <span class="d-none d-lg-block">RestaurantAdmin</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div><!-- End Logo -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
  
          <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <!--<img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">-->
              <span class="d-none d-md-block dropdown-toggle ps-2">'.$username.'</span>
            </a><!-- End Profile Iamge Icon -->
  
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
                <h6>'.$namelastname.'</h6>
                <span>'.$positionname.'</span>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="profile.php">
                  <i class="bi bi-person"></i>
                  <span>Mi Perfil</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#" id="close-session">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Cerrar Sesión</span>
                </a>
              </li>
            </ul><!-- End Profile Dropdown Items -->
          </li><!-- End Profile Nav -->
  
        </ul>
      </nav><!-- End Icons Navigation -->
  
    </header>';
  }

  function loadSideBar($position=2,$link="L-",$ctive="A-"){
    // @param link recive el valor de que pagina se carga para 
    // posteriormente desactivar la clase collapse segun corresponda
    // L-HOME: home.php
    // L-LISTUSERS: listusers.php
    // L-ORDERS: orders.php
    // L-PROFILE: profile.php
    // L-REGISTER: register.php
    // L-SALES: sales.php
    // L-LOCALORDERS: localorders.php

    $html='';
    $html.='<aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">';
            if($link=="L-HOME"){$html.='<a class="nav-link " href="home.php">';}
            else{$html.='<a class="nav-link collapsed" href="home.php">';}
            $html.='<i class="bi bi-house"></i>
                <span>Inicio</span>
            </a>
            </li><!-- End Home Nav -->

            <li class="nav-item">';
            if ($link=='L-LOCALORDERS') {$html.='<a class="nav-link " href="localorders.php">';}
            else{$html.='<a class="nav-link collapsed" href="localorders.php">';}
            $html.='<i class="bx bx-bowl-hot"></i>
                <span>O. Local</span>
            </a>
            </li><!-- End Orders Nav -->
            
            <li class="nav-item">';
            if ($link=='L-ORDERS') {$html.='<a class="nav-link " href="orders.php">';}
            else{$html.='<a class="nav-link collapsed" href="orders.php">';}
            $html.='<i class="bi bi-card-list"></i>
                <span>Pedidos</span>
            </a>
            </li><!-- End Orders Nav -->
            
            <li class="nav-item">';
            if($link=='L-SALES'){$html.='<a class="nav-link " href="sales.php">';}
            else{$html.='<a class="nav-link collapsed" href="sales.php">';}
            $html.='<i class="bi bi-cart"></i>
                <span>Ventas</span>
            </a>
            </li><!-- End Sales Nav -->';

      if($position==1){
        $html.='<li class="nav-item">';
        if($link=='L-MANTE'){
            $html.='<a class="nav-link " data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Mantenimiento</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">';
        }
        else{
            $html.='<a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Mantenimiento</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
        }
        
        $html.='
          <li>';
          if($ctive=='A-EMPLOYEE'){
            $html.='<a href="employees.php" class="active">';
          }else{
            $html.='<a href="employees.php" class="">';
          }
          $html.='<i class="bi bi-circle"></i><span>Empleados</span>
            </a>
          </li>
          <li>';
          if($ctive=='A-DISH'){
            $html.='<a href="dishes.php" class="active">';
          }else{
            $html.='<a href="dishes.php" class="">';
          }
          $html.='<i class="bi bi-circle"></i><span>Carta</span>
            </a>
          </li>
        </ul>
      </li>';
      }
      $html.='</ul> </aside>';
    return $html;
  }

  function loadModalDetailOrder($btnconfirm=false){
    $html="";
    $html.='<div class="modal fade" id="modal-detail-order" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
            <div class="">
                <h3 class="modal-title">Detalle del pedido # <span id="number-order">0</span></h3>
                <p class="text-center"></p>
            </div>            
            <h2 class="modal-title">Total: <span id="total-price-order">S/ 0.00</span></h2>
            </div>
            <div class="modal-body">
            <div class="row no-gutters">
                <div class="col-12 row">
                <h6>Cliente: <span id="order-name-client"></span></h6>
                <h6>Teléfono: <span id="order-phone-client"></span></h6>
                <h6>Dirección: <span id="order-address-client"></span></h6>
                <h6>Lugar de Referencia: <span id="order-reference-client"></span></h6>
                </div>
                <div class="col-12">
                <div class="row justify-content-center" id="detail-order"></div>
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>';
            if($btnconfirm){
                $html.='<button type="button" class="btn btn-success" id="btn-check-order">Marcar Como Enviado</button>
                      </div>
                  </div>
                  </div>
              </div>';
            }
            $html.='
            </div>
        </div>
        </div>
    </div>';
    return $html;
  }

  function loadModalValidatePassword(){
    return '<form class="modal fade" id="formvalidatepassword" tabindex="-1" autocomplete="off" data-bs-backdrop="static" method="post" role="form">
        <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Verificación de Acceso</h5>
            </div>
            <div class="modal-body">
            <h6>Ingresa tu constraseña para marcar como Orden <span>Enviada</span></h6>
            <input type="password" id="txtpassword" class="form-control" required autofocus>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Confirmar</button>
            </div>
        </div>
        </div>
    </form>';
  }

  function loadScripts(){
    return '<!-- Vendor JS Files -->
      <script src="assets/vendor/sweetalert/sweetalert2.min.js"></script>
      <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
      <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    
      <!-- Template Main JS File -->
      <script src="assets/js/main.js"></script>';
  }

  function loadModalUpdateUserAdmin(){
    return '<form class="modal fade" id="formupdateuser" tabindex="-1" data-bs-backdrop="static" role="form" method="post">
      <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header justify-content-between">
            <h4 class="modal-title">Actualizar Datos de: <span id="name-user"></span></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                  <label for="yourName" class="form-label">Nombres</label>
                  <input type="text" name="name" class="form-control" id="yourName" required minlength="3" maxlength="40" autofocus>
                </div>
                <div class="col-12 col-lg-6">
                    <label for="lastName" class="form-label">Apellidos</label>
                    <input type="text" name="lastname" class="form-control" id="lastName" required minlength="3" maxlength="40">
                </div>
                <div class="col-12 col-lg-9">
                  <label for="yourEmail" class="form-label">Correo electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="email" name="email" class="form-control" id="yourEmail" required minlength="3" maxlength="60">
                  </div>
                </div>
                <div class="col-7">
                    <label for="yourPosition" class="form-label">Cargo</label>
                    <select id="yourPosition" class="form-control" name="position" required>
                        <option value="1">Administrador</option>
                        <option value="2">Empleado</option>
                    </select>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </form>';
  }

  function loadModalShowDetailDish(){
    return '<div class="modal fade" id="modal-detail-dish" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header justify-content-between">
            <div class="">
                <h3 class="modal-title">Platillo # <span id="number_dish">0</span></h3>
                <p class="text-center"></p>
            </div>            
            <h2 class="modal-title">Precio: <span id="price_dish">S/ 0.00</span></h2>
          </div>
          <div class="modal-body">
            <div class="col-12">
              <div class="row justify-content-center" id="detail-order">
                <div class="col-12 row mb-1">
                  <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <div class="d-flex justify-content-center">
                      <img class="w-50">
                    </div>
                  </div>
                  <div class="col-12 col-md-8">
                      <p class="fw-bold">Nombre: <span class="fw-normal" id="name_dish"></span></p>
                      <p class="fw-bold">Descripción: <span class="fw-normal" id="descrip_dish"></span></p>
                      <p class="fw-bold">Categoria: <span class="fw-normal" id="name_categ"></span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>';
  }

  function loadModalUpdateDish(){
    return '<form class="modal fade" id="formupdatedish" tabindex="-1" data-bs-backdrop="static" role="form" action="assets/vendor/"  enctype="multipart/form-data">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header justify-content-between">
          <h3 class="modal-title">Actualizar Datos de: <span id="name_dish"></span></h3>            
        </div>
        <div class="modal-body">
          <div class="col-12">
            <div class="row justify-content-center">
              <div class="col-12 row mb-1">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                  <div class="d-flex justify-content-center">
                    <img class="w-50">
                  </div>
                  <div class="d-flex justify-content-center">
                    <input type="file" name="uploadimage" id="uploadimage" class="form-control form-control-sm">
                  </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="row">
                      <div class="col-12">
                        <label for="namedish" class="form-label">Nombre</label>
                        <input type="text" name="namedish" class="form-control" id="namedish" required minlength="3" maxlength="100" autofocus>
                      </div>
                      <div class="col-12 col-md-4">
                        <label for="pricedish" class="form-label">Precio</label>
                        <input type="text" name="pricedish" class="form-control" id="pricedish" required minlength="2" maxlength="5" autofocus>
                      </div>
                      <div class="col-12 col-md-8">
                        <label for="category" class="form-label">Categoria</label>
                        <select id="category" class="form-control" name="category" required>
                          <option value="1">POLLOS</option>
                          <option value="2">ENSALADAS</option>
                          <option value="3">BEBIDAS</option>
                          <option value="4">COMBOS</option>
                        </select>
                      </div>
                      <div class="col-12">
                        <label for="descripdish" class="form-label">Descripción</label>
                        <textarea id="descripdish" class="form-control" name="descripdish" rows="5" maxlength="200"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success" id="btnsendupdatedish">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </form>';
  }

?>