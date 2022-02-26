<?php
    error_reporting(0);
    session_start();
    $varsesion=$_SESSION['login'];
    if($varsesion==null||$varsesion==''||$varsesion!=true){
      header('location:./index.php');
      die();
    }
    if(isset($_SESSION['timesession'])){
      $disable=900;
      $life_session = time() - $_SESSION['timesession'];
      if($life_session>$disable){
        session_destroy();
        header('location:./index.php');
        die();
      }
    }else{
      $_SESSION['timesession']=time();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pedidos - RestaurantlyAdmin</title>
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

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="home.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">RestaurantlyAdmin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!--<img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">-->
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['username']; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['name']. ' '.$_SESSION['lastname']; ?></h6>
              <span><?php echo $_SESSION['postname']; ?></span>
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

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="home.php">
          <i class="bi bi-house"></i>
          <span>Inicio</span>
        </a>
      </li><!-- End Home Nav -->

      <li class="nav-item">
        <a class="nav-link " href="orders.php">
          <i class="bi bi-card-list"></i>
          <span>Pedidos</span>
        </a>
      </li><!-- End Sales Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="sales.php">
          <i class="bi bi-cart"></i>
          <span>Ventas</span>
        </a>
      </li><!-- End Sales Nav -->

      <?php
        if($_SESSION['position']==1){
          echo '<li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-gear"></i><span>Mantenimiento</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="register.php">
                <i class="bi bi-circle"></i><span>Registrar Usuario</span>
              </a>
            </li>
            <li>
              <a href="listusers.php">
                <i class="bi bi-circle"></i><span>Listado de Usuarios</span>
              </a>
            </li>
          </ul>
        </li>';
        }
      ?>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <h1>Estadísticas de Pedidos</h1>
            <form class="ms-2" role="form" autocomplete="off" id="formsearchorderstypedata">
                <select class="form-control-sm" id="dpworderstypedata"> 
                    <option value="today">Hoy Día</option>
                    <option value="month">Este Mes</option>
                    <option value="year">Este AÑo</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm ms-2 text-white fw-bold">Buscar</button>
            </form>
            <form class="ms-3" role="form" autocomplete="off" id="formsearchordersfordate">
                <input type="date" class="form-control-sm" id="txtdatedata" required>
                <button type="submit" class="btn btn-primary btn-sm ms-2 text-white fw-bold">Buscar por fecha</button>
            </form>
        </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
          <li class="breadcrumb-item active">Pedidos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <!-- Ordes Card -->
        <div class="col-xxl-8 col-xl-12">

          <div class="card info-card customers-card">

            <div class="card-body">
              <h5 class="card-title">Pedidos | <span id="lblorders">Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-clock-history"></i>
                </div>
                <div class="ps-3">
                  <h6>Total: <span id="lbltotalorders">0</span></h6>
                  <span class="text-success small pt-1 fw-bold me-1">Enviados: <span class="text-muted small pt-2 ps-1" id="dispatched_orders_total">0</span></span>
                  <span class="text-warning small pt-1 fw-bold me-1">Pendientes: <span class="text-muted small pt-2 ps-1" id="pending_orders_total">0</span></span>
                  <span class="text-danger small pt-1 fw-bold">Cancelados: <span class="text-muted small pt-2 ps-1" id="cancel_orders_total">0</span></span>
                </div>
              </div>

            </div>
          </div>

        </div><!-- End Orders Card -->

        <!-- Recent Sales -->
        <div class="col-12">
          <div class="card recent-sales overflow-auto">

            
            <div class="filter">
              <a class="icon" href="#"><i class="bi bi-arrow-clockwise" id="btnreloaddataorders"></i></a>
            </div>

            <div class="card-body">
              <h5 class="card-title">Pedidos | <span id="lblorderstable">Hoy</span></h5>

              <table class="table table-borderless" id="datatable-orders">
                
              </table>
            </div>

          </div>
        </div><!-- End Recent Sales -->

      </div>
    </section>
    <!-- Vertically centered Modal -->
    <div class="modal fade" id="modal-detail-order" tabindex="-1" data-bs-backdrop="static">
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
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div><!-- End Vertically centered Modal-->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>RestaurantlyAdmin</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/sweetalert/sweetalert2.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/order.js"></script>
  
</body>

</html>