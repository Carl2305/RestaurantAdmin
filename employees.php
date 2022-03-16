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
  echo loadHeadPage("Mantenimiento Empleados");
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
      echo loadSideBar(1,"L-MANTE","A-EMPLOYEE");
    }else{
      echo loadSideBar(2,"L-MANTE","A-EMPLOYEE");
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <div class="d-flex">
        <h1>Mantenimiento Empleados</h1>
      </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Inicio</a></li>
          <li class="breadcrumb-item active">Mantenimiento Empleados</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#list-users" id="btntablistusers">Listado de Empleados</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#create-user">Registrar Nuevo Empleado</button>
            </li>  
          </ul>
          <div class="tab-content pt-2"> 
            <div class="tab-pane fade show active list-users" id="list-users">
              <div class="d-flex justify-content-center mt-3 mb-4">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Empleados | <span>contando al usuario que está Logueado</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6>Total: <span id="number-users-total">0</span></h6>
                        <span class="text-success small pt-1 fw-bold me-1">Habilitados: <span class="text-muted small pt-2 ps-1" id="enable_users_total">0</span></span>
                        <span class="text-danger small pt-1 fw-bold">Deshabilitados: <span class="text-muted small pt-2 ps-1" id="disabled_users_total">0</span></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card recent-sales overflow-auto">
                <div class="filter">
                  <a class="icon" href="#"><i class="bi bi-arrow-clockwise" id="btnreloaddatausers"></i></a>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Listado de Empleados | <span>sin usuario que está Logueado</span></h5>
                  <table class="table table-borderless" id="datatable-mant-users"></table>
                </div>
              </div>
            </div>

            <div class="tab-pane fade profile-edit pt-0" id="create-user">
              <div class="pt-0 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Crear Cuenta de Acceso</h5>
                <p class="text-center small">Ingresa tus datos personales para crear una cuenta de acceso</p>
              </div>
              <form class="row g-3" role="form" autocomplete="off" id="formcreateaccount">
                <div class="col-12 col-md-6">
                  <label for="yourName" class="form-label">Nombres</label>
                  <input type="text" name="name" class="form-control" id="yourName" required minlength="3" maxlength="40" autofocus>
                </div>
                <div class="col-12 col-md-6">
                  <label for="lastName" class="form-label">Apellidos</label>
                  <input type="text" name="lastname" class="form-control" id="lastName" required minlength="3" maxlength="40">
                </div>
                <div class="col-12 col-md-7 col-lg-8">
                  <label for="yourEmail" class="form-label">Correo electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="email" name="email" class="form-control" id="yourEmail" required minlength="3" maxlength="60">
                  </div>
                </div>
                <div class="col-7 col-md-5 col-lg-4">
                  <label for="yourPosition" class="form-label">Cargo</label>
                  <select id="yourPosition" class="form-control" name="position" required>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                  </select>
                </div>
                <div class="col-12 col-sm-8 col-md-7">
                  <label for="yourUsername" class="form-label">usuario</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" id="yourUsername" required minlength="5" maxlength="20">
                  </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6">
                  <label for="yourPassword" class="form-label">Contraseña</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" name="password" class="form-control" id="yourPassword" required minlength="5" maxlength="15">
                  </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6">
                  <label for="yourConfirmPassword" class="form-label">Confirmar Contraseña</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" name="confirmpassword" class="form-control" id="yourConfirmPassword" required minlength="5" maxlength="15">
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Registrar Cuenta</button>
                  </div>
                </div>
              </form>
            </div>   
          </div><!-- End Bordered Tabs -->
        </div>
      </div>
    </section>
    <!-- Vertically centered Modal -->
    <?php echo loadModalUpdateUserAdmin(); ?>
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
  <script src="assets/js/maintenance.js"></script>
  
</body>

</html>