<?php
    error_reporting(0);
    session_start();
    $varsesion=$_SESSION['login'];
    if($varsesion==null||$varsesion==''||$varsesion!=true){
      header('location:./index.php');
      die();
    }else if($_SESSION['position']!=1){
      session_destroy();
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
  echo loadHeadPage("Mantenimiento Carta");
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
      echo loadSideBar(1,"L-MANTE","A-DISH");
    }else{
      echo loadSideBar(2,"L-MANTE","A-DISH");
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <div class="d-flex">
        <h1>Mantenimiento Carta</h1>
      </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
          <li class="breadcrumb-item active">Mantenimiento Carta</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#list-dishes" id="btntablistdishes">Listado de Platillos y Bebidas</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#create-dish">Registrar Nuevo Platillo o Bebida</button>
            </li>  
          </ul>
          <div class="tab-content pt-2"> 
            <div class="tab-pane fade show active list-users" id="list-dishes">
              <div class="d-flex justify-content-center mt-3 mb-4">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Platillos y Bebidas  <span></span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class='bx bx-bowl-hot'></i><i class="bi bi-cup-straw"></i>
                      </div>
                      <div class="ps-3">
                        <h6>Total: <span id="number_dishes_total">0</span></h6>
                        <span class="text-info small pt-1 fw-bold me-1">Platos: <span class="text-muted small pt-2 ps-1" id="dishes_total">0</span></span>
                        <span class="text-warning small pt-1 fw-bold">Bebidas: <span class="text-muted small pt-2 ps-1" id="drinks_total">0</span></span>
                        <span class="text-success small pt-1 fw-bold me-1">Ensaladas: <span class="text-muted small pt-2 ps-1" id="salads_total">0</span></span>
                        <span class="text-danger small pt-1 fw-bold">Combos: <span class="text-muted small pt-2 ps-1" id="combos_total">0</span></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- start -->

              <form class="modal fade" id="formupdatedish" tabindex="-1" data-bs-backdrop="static" role="form" action="assets/vendor/"  enctype="multipart/form-data">
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
                                <input type="file" name="uploadimage" id="uploadimage">
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
                </form>



              <!-- end -->



              <div class="card recent-sales overflow-auto">
                <div class="filter">
                  <a class="icon" href="#"><i class="bi bi-arrow-clockwise" id="btnreloaddatadishes"></i></a>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Listado de Platillos y Bebidas | <span></span></h5>
                  <table class="table table-borderless" id="datatable-mant-dishes"></table>
                </div>
              </div>
            </div>

            <div class="tab-pane fade profile-edit pt-0" id="create-dish">
              <div class="pt-0 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Crear Nuevo Platillo o Bebida</h5>
                <p class="text-center small">Ingresa los datos necesarios para crear un platillo o bebida</p>
              </div>
              <form class="row g-3" role="form" autocomplete="off" id="formcreatedish" method="post" role="form" action="assets/vendor/"  enctype="multipart/form-data">
                <div class="col-12 col-md-8">
                  <label for="cnamedish" class="form-label">Nombre</label>
                  <input type="text" name="cnamedish" class="form-control" id="cnamedish" required minlength="3" maxlength="100" autofocus>
                </div>
                <div class="col-7 col-md-4">
                  <label for="ccategory" class="form-label">Categoria</label>
                  <select id="ccategory" class="form-control" name="ccategory" required autofocus>
                    <option value="1">POLLOS</option>
                    <option value="2">ENSALADA</option>
                    <option value="3">BEBIDAS</option>
                    <option value="4">COMBOS</option>
                  </select>
                </div>
                <div class="col-6 col-md-3">
                  <label for="cpricedish" class="form-label">Precio</label>
                  <input type="text" name="cpricedish" class="form-control" id="cpricedish" required minlength="2" maxlength="5" autofocus>
                </div>
                <div class="col-12 col-md-9">
                  <label for="cdescripdish" class="form-label">Descripción</label>
                  <textarea id="cdescripdish" class="form-control" name="cdescripdish" rows="5" maxlength="200" autofocus></textarea>
                </div>
                <div class="col-12 col-sm-8 col-md-7">
                  <label for="uploadimage" class="form-label">Imagen</label>
                  <div class="input-group">
                    <input type="file" name="uploadimage" class="form-control" id="uploadimage" required minlength="5" maxlength="20" autofocus>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Registrar Platillo</button>
                  </div>
                </div>
              </form>
            </div>   
          </div><!-- End Bordered Tabs -->
        </div>
      </div>
    </section>
    
  </main><!-- End #main -->

  <?php
    echo loadModalShowDetailDish();
  ?>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>RestaurantAdmin</span></strong>. Todos los Derechos Reservados
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php echo loadScripts(); ?>
  <script src="assets/js/maintenance.js"></script>
  
</body>

</html>