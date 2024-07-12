<?php 
session_start();

include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/start.php");

include("php/config.php");

// // Afișăm mesajul de deconectare dacă există
// if(isset($_SESSION['logout_message'])) {
//     echo "<div class='message'>" . $_SESSION['logout_message'] . "</div>";
//     unset($_SESSION['logout_message']); // Eliminăm mesajul după afișare
// }
$ok = true;

if(isset($_POST['submit'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $result = mysqli_query($con, "SELECT * FROM user WHERE username='$username' AND password='$password'") or die("Select Error: " . mysqli_error($con));
    $row = mysqli_fetch_assoc($result);

    if($row){
        $ok = true;
        $_SESSION['valid'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['tip_utilizator'] = $row['tip_utilizator'];

        // Verificăm rolul utilizatorului și redirecționăm către pagina corectă
        if($row['tip_utilizator'] == "admin") {
            header("Location: home.php");
            exit();
        } elseif ($row['tip_utilizator'] == "cadru_medical") {
            header("Location: home.php");
            exit();
        } else {
            header("Location: home.php");
            exit();
        }
    } else {
        $ok = false;
        
    }
}
?>
<body class="bg-dark-blue">
<section class="vh-100">
  <div class="container h-100 py-3">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="/eeris/assets/hero.jpg"
                alt="login form" class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem; object-fit: cover;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">
                <form action="" method="post">
                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0"><i class="bi bi-clipboard2-pulse-fill"></i><br> Health Management System</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>
                  <?php
                    if($ok === false) {
                        echo "<div class='alert alert-warning pb-0'>
                                <p>Wrong Username or Password</p>
                              </div>";
                  }
                  ?>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input name="username" type="text" id="username" class="form-control form-control-lg" required>
                    <label class="form-label" for="username">Username</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input name="password" type="password" id="password" class="form-control form-control-lg" required>
                    <label class="form-label" for="password">Password</label>
                  </div>

                  <div class="pt-1 mb-4">
                    <input type="submit" class="btn btn-dark-blue btn-lg btn-block" name="submit" value="Login">
                  </div>

                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="/eeris/register.php"
                      style="color: #393f81;">Register here</a></p>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


</body>
</html>
