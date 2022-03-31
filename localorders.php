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
  echo loadHeadPage("Generar Ordenes del Local");
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
      echo loadSideBar(1,"L-LOCALORDERS");
    }else{
      echo loadSideBar(2,"L-LOCALORDERS");
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Ordenes de Mesas en el Local</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="localorders.php">Ordenes Locales</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">
        <div class="col-8">

          <div class="col-12">
            <h1>Buscar Platillos</h1>
            <input type="search" class="form-control" name="search" id="search" maxlength="50"/>
          </div>

          <div class="col-12">
            <div class="row mt-4" id="itemsdish">
              <?php
              #<div class="col-6 d-flex justify-content-center">
              #  <div class="card">
              #    <div class="card-body">
              #      <br>
              #      <div class="d-flex align-items-center">
              #        <div class="card-icon d-flex align-items-center justify-content-center">
              #          <img src="assets/img/menu/pollo-papas-ensalada.png" class="w-100 rounded-circle">
              #        </div><!-- end div de la imagen-->
              #        <div class="ps-3">
              #          <div class="d-flex justify-content-between">
              #            <h5>1 Pollo + Papa</h5>
              #            <h5 class="fw-bold">S/ 63.90</h5>
              #          </div><!-- end head card name and price -->
              #          <p>1 Pollo + Papa + Ensalada Clásica Familiar + Salsas</p>
              #          <h6 class="fw-bold">COMBOS</h6>
              #          <div class="d-flex justify-content-end">
              #            <button class="btn btn-success btn-sm btnaddcart" onclick="alert('hola mundo')">Agregar</button>
              #          </div>
              #        </div><!-- end ps-3 -->
              #      </div>
              #    </div><!-- end body card -->
              #  </div><!-- end card-->
              #</div><!-- end firts column-->

              #<div class="col-6">
              #  
              #</div><!--end second column-->

              ?>


            </div><!-- end #itemsdish column-->

            
            <div class="card mt-4 d-none" id="msgerrorlist">
              <div class="card-body">
                <br>
                <div class="d-flex align-items-center justify-content-center">
                  <h3 class="fw-bold">No hay platillos con esta descripción</h3>
                </div>
              </div>
            </div>

          </div><!-- end col-12 -->

        </div>

        <div class="col-4">
          <div class="col-12 row no-gutters mb-4 d-flex align-items-center">
            <div class="col-4">
              <select name="nametable" id="nametable" class="form-control"></select>
            </div>
            <div class="col-2">
              <buttont class="btn btn-info" id="btnselectedboard"><i class="bi bi-check"></i></buttont>
            </div>
            <div class="col-6 d-flex align-items-center">
              <h5 class="w-100">M. <span class="fw-bold" id="nameboard"></span></h5>
              <h5 class="w-100">Cap. <span class="fw-bold" id="capacityboard">0</span></h5>
            </div>
          </div>
          <div class="col-12 d-flex justify-content-between">
            <h5 class="fw-bold">Total</h5>
            <h4 class="fw-bold" id="totalorderdishlocal">S/. 00.00</h4>
          </div>
          
          <div class="col-12 overflow-auto p-3" style="max-height:650px;" id="listdishesselected">

            <!--<div class="card">
              <div class="card-body p-0">
                <div class="col-12 position-relative">
                  <button class="btn btn-danger position-absolute top-0 end-0" data-codedish="" data-dish=""><i class="bi bi-trash"></i></button>
                </div>
                <br>
                <div class="text-center">
                  <h6 class="fw-bold">1 Pollo + Papa + Ensalada</h6>
                  <p>1/2 Pollo + Papa + Ensalada Clásica Mediana + Salsas</p>
                </div>
              </div>
            </div>--><!-- end card -->


            
          </div>

          
          <div class="card mt-4" id="msgerrorselect">
            <div class="card-body">
              <br>
              <div class="d-flex align-items-center justify-content-center">
                <h5 class="fw-bold">No hay platillos seleccionados</h5>
              </div>
            </div>
          </div>

          <div class="col-12 d-flex justify-content-center">
            <button class="mt-4 btn btn-success btn-lg d-none" id="btnsaveorderdishselect">Finalizar Pedido</button>
          </div>

        </div>
      </div>
    </section>
    
    <?php
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
  <script src="assets/js/locationorders.js"></script>
</body>

</html>