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
    include ('./assets/html/codehtml.php');
?>
<!DOCTYPE html>
<html lang="en">

<?php
  echo loadHeadPage("Inicio");
?>

<body>

  <!-- ======= Header ======= -->
  <?php
    $username=$_SESSION['username'];
    $namelastname=$_SESSION['name']. ' '.$_SESSION['lastname'];
    $positionname=$_SESSION['postname'];
    echo loadHeader($username,$namelastname,$positionname);
  ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php
    if($_SESSION['position']==1){
      echo loadSideBar(1,"L-HOME");
    }else{
      echo loadSideBar(2,"L-HOME");
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Estadísticas Hoy</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="home.php">Inicio</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <!-- Ordes Card -->
        <div class="col-xxl-4 col-xl-12">

          <div class="card info-card customers-card">

            <div class="card-body">
              <h5 class="card-title">Pedidos | <span>Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-clock-history"></i>
                </div>
                <div class="ps-3">
                  <h6>Total: <span id="orders_today">0</span></h6>
                  <span class="text-success small pt-1 fw-bold me-1">Enviados: <span class="text-muted small pt-2 ps-1" id="dispatched_orders_today">0</span></span>
                  <span class="text-warning small pt-1 fw-bold me-1">Pendientes: <span class="text-muted small pt-2 ps-1" id="pending_orders_today">0</span></span>
                  <span class="text-danger small pt-1 fw-bold">Cancelados: <span class="text-muted small pt-2 ps-1" id="cancel_orders_today">0</span></span>
                </div>
              </div>

            </div>
          </div>

        </div><!-- End Orders Card -->

        <!-- Sales Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Ventas | <span>Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6 id="number-sales-today">0</h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Revenue Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Ingresos | <span>Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cash-coin"></i>
                </div>
                <div class="ps-3">
                  <h6 id="total-sales-today">S/ 0.00</h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Recent Sales -->
        <div class="col-12">
          <div class="card recent-sales overflow-auto">

            
            <div class="filter">
              <a class="icon" href="#"><i class="bi bi-arrow-clockwise" id="btnreloaddatahome"></i></a>
            </div>

            <div class="card-body">
              <h5 class="card-title">Pedidos Recientes | <span>Hoy</span></h5>

              <table class="table table-borderless" id="datatable-home">
                
              </table>
            </div>

          </div>
        </div><!-- End Recent Sales -->

      </div>
    </section>
    
    <?php
      echo loadModalDetailOrder(true);
      echo loadModalValidatePassword();
    ?>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>RestaurantAdmin</span></strong>. Todos los Derechos Reservados
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php echo loadScripts(); ?>
  <script src="assets/js/home.js"></script>
  
</body>

</html>