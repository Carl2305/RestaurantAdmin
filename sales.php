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
  echo loadHeadPage("Ventas");
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
      echo loadSideBar(1,"L-SALES");
    }else{
      echo loadSideBar(2,"L-SALES");
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <div class="d-flex">
        <h1>Estadísticas de Ventas</h1>
        <form class="ms-2" role="form" autocomplete="off" id="formsearchtypedata">
            <select class="form-control-sm" id="dpwtypedata"> 
                <option value="today">Hoy Día</option>
                <option value="month">Este Mes</option>
                <option value="year">Este AÑo</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm ms-2 text-white fw-bold">Buscar</button>
        </form>
        <form class="ms-3" role="form" autocomplete="off" id="formsearchfordate">
            <input type="date" class="form-control-sm" id="txtdatedata" required>
            <button type="submit" class="btn btn-primary btn-sm ms-2 text-white fw-bold">Buscar por fecha</button>
        </form>
        
      </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
          <li class="breadcrumb-item active">Ventas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <!-- Sales Card -->
        <div class="col-xxl-6 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Ventas | <span id="lblsales">Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6 id="number-sales-type">0</h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Revenue Card -->
        <div class="col-xxl-6 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Ingresos | <span id="lblincome">Hoy</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cash-coin"></i>
                </div>
                <div class="ps-3">
                  <h6 id="total-sales-type">S/ 0.00</h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Recent Sales -->
        <div class="col-12">
          <div class="card recent-sales overflow-auto">

            
            <div class="filter">
              <a class="icon" href="#"><i class="bi bi-arrow-clockwise" id="btnreloaddatasale"></i></a>
            </div>

            <div class="card-body">
              <h5 class="card-title">Ventas | <span id="lblsalestable">Hoy</span></h5>

              <table class="table table-borderless" id="datatable-sale">
                
              </table>
            </div>

          </div>
        </div><!-- End Recent Sales -->

      </div>
    </section>
    <!-- Vertically centered Modal -->
    <?php echo loadModalDetailOrder(false); ?>
    <!-- End Vertically centered Modal-->


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>RestaurantAdmin</span></strong>. Todos los Derechos Reservados
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php echo loadScripts(); ?>
  <script src="assets/js/sale.js"></script>
  
</body>

</html>