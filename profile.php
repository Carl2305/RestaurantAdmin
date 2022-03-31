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
  echo loadHeadPage("Mi Perfil");
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
      echo loadSideBar(1);
    }else{
      echo loadSideBar(2);
    }
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Mi Perfil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="home.php">Inicio</a></li>
          <li class="breadcrumb-item"><a href="profile.html">Mi Perfil</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section profile">
      <div class="row d-flex justify-content-center">
        <div class="col-md-12 col-xl-8">

            <div class="card">
              <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">
  
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Datos Personales</button>
                  </li>
  
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                  </li>
  
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar Contraseña</button>
                  </li>
  
                </ul>
                <div class="tab-content pt-2">
  
                  <div class="tab-pane fade show active profile-overview" id="profile-overview">
                    
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Nombres</div>
                      <div class="col-lg-9 col-md-8"><?php echo $_SESSION['name']; ?></div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Apellidos</div>
                      <div class="col-lg-9 col-md-8"><?php echo $_SESSION['lastname']; ?></div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Correo Electrónico</div>
                      <div class="col-lg-9 col-md-8"><?php echo $_SESSION['emailuser']; ?></div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Cargo</div>
                      <div class="col-lg-9 col-md-8"><?php echo $_SESSION['postname']; ?></div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Usuario</div>
                      <div class="col-lg-9 col-md-8"><?php echo $_SESSION['username']; ?></div>
                    </div>
  
                  </div>
  
                  <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
  
                    <!-- Profile Edit Form -->
                    <form role="form" id="formUpdateUser" autocomplete="off" method="post">
                      <div class="row mb-3">
                        <label for="firstName" class="col-md-4 col-lg-3 col-form-label">Nombres</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="firstName" type="text" class="form-control" id="firstName" required minlength="3" maxlength="40" value="<?php echo $_SESSION['name']; ?>">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Apellidos</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="lastName" type="text" class="form-control" id="lastName" required minlength="3" maxlength="40" value="<?php echo $_SESSION['lastname']; ?>">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Correo Electrónico</label>
                        <div class="col-md-8 col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="email" name="email" class="form-control" id="email" required minlength="3" maxlength="60" value="<?php echo $_SESSION['emailuser']; ?>">
                              </div>
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                      </div>
                    </form><!-- End Profile Edit Form -->
  
                  </div>
  
                  <div class="tab-pane fade profile-edit pt-3" id="profile-change-password">
                    <!-- Change Password Form -->
                    <form role="form" id="formUpdatePassUser" autocomplete="off" method="post">
                      <div class="row mb-3">
                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Actual</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="password" type="password" class="form-control" id="currentPassword" required minlength="5" maxlength="15">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nueva Contraseña</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="newpassword" type="password" class="form-control" id="newPassword" required minlength="5" maxlength="15">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Confirmar nueva Contraseña</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="renewpassword" type="password" class="form-control" id="renewPassword" required minlength="5" maxlength="15">
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                      </div>
                    </form><!-- End Change Password Form -->
  
                  </div>
  
                </div><!-- End Bordered Tabs -->
  
              </div>
            </div>
  
          </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>RestaurantAdmin</span></strong>. Todos los Derechos Reservados
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php echo loadScripts(); ?>
  
</body>

</html>