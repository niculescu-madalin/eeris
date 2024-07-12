<?php 
session_start();
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/start.php");
include("php/config.php");
?>


<body class="bg-dark-blue">
<section class="vh-100">
<div class="container-fluid">
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
                <?php

                
                if(isset($_POST['submit'])){
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $confirm_password = $_POST['confirm_password'];
                    $tip_utilizator = $_POST['user_type']; 
                    
                    // Verificare dacă parola are cel puțin 8 caractere
                    if(strlen($password) < 8) {
                        echo "<div class='message'>
                                  <p>Parola trebuie să aibă cel puțin 8 caractere!</p>
                              </div> <br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn btn-dark-blue'>Înapoi</button></a>";
                    } else {
                        // Verificare parolă confirmată
                        if($password !== $confirm_password) {
                            echo "<div class='message'>
                                      <p>Parolele nu se potrivesc!</p>
                                  </div> <br>";
                            echo "<a href='javascript:self.history.back()'><button class='btn'>Înapoi</button></a>";
                        } else {
                            // Verificare email unic
                            $verify_query = mysqli_query($con, "SELECT email FROM user WHERE email='$email'");
                            if(mysqli_num_rows($verify_query) != 0 ){
                                echo "<div class='message'>
                                          <p>Acest email este deja folosit. Vă rugăm să încercați altul!</p>
                                      </div> <br>";
                                echo "<a href='javascript:self.history.back()'><button class='btn'>Înapoi</button></a>";
                            } else {
                                // Verificare username unic
                                $verify_username_query = mysqli_query($con, "SELECT username FROM user WHERE username='$username'");
                                if(mysqli_num_rows($verify_username_query) != 0 ){
                                    echo "<div class='message'>
                                              <p>Acest nume de utilizator este deja folosit. Vă rugăm să încercați altul!</p>
                                          </div> <br>";
                                    echo "<a href='javascript:self.history.back()'><button class='btn'>Înapoi</button></a>";
                                } else {
                                    // Verificare rol admin existent
                                    if ($tip_utilizator == 'admin') {
                                        $admin_query = mysqli_query($con, "SELECT * FROM user WHERE tip_utilizator = 'admin'");
                                        if (mysqli_num_rows($admin_query) > 0) {
                                            echo "<div class='message'>
                                                      <p>Un admin există deja. Nu puteți atribui acest rol.</p>
                                                  </div> <br>";
                                            echo "<a href='javascript:self.history.back()'><button class='btn'>Înapoi</button></a>";
                                            exit();
                                        }
                                    }
                                    
                                    // Inserare utilizator nou
                                    $insert_query = "INSERT INTO user (username, email, password, tip_utilizator) VALUES ('$username', '$email', '$password', '$tip_utilizator')";
                                    if(mysqli_query($con, $insert_query)) {
                                        $user_id = mysqli_insert_id($con);
                                        $_SESSION['user_id'] = $user_id; // Salvați user_id-ul în sesiune
                                        
                                        if($tip_utilizator == 'cadru_medical') {
                                            header("Location: register_cadru_medical.php");
                                            exit();
                                        } elseif($tip_utilizator == 'pacient') {
                                            header("Location: register_pacient.php");
                                            exit();
                                        } else {
                                            echo "<div class='message'>
                                                      <p>Înregistrare reușită!</p>
                                                  </div> <br>";
                                            echo "<a href='login.php'><button class='btn'>Autentifică-te acum</button></a>";
                                        }
                                    } else {
                                        echo "<div class='message'>
                                                  <p>Eroare: " . mysqli_error($con) . "</p>
                                              </div> <br>";
                                    }
                                }
                            }
                        }
                    }
                } else {
                ?>
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Create a new account</h5>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input class="form-control" type="text" name="username" id="username" autocomplete="off" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" autocomplete="off" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" autocomplete="off" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" autocomplete="off" required>
                        </div>

                        <!-- <div class="form-group">
                            <label for="tip_utilizator">User Type</label>
                            <select class="form-control" name="tip_utilizator" id="tip_utilizator" required>
                                <option value="" disabled selected>Selectează Tipul</option>
                                <option value="cadru_medical">Cadru Medical</option>
                                <option value="pacient">Pacient</option>
                            </select>
                        </div> -->

                        <div class="form-group">
                            <label for="tip_utilizator">User Type</label>
                            <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                              <label class="btn btn-outline-dark-blue btn-lg active">
                                <input type="radio" name="user_type" autocomplete="off" checked value="pacient"> 
                                    <i class="bi bi-file-earmark-person"></i> Patient
                              </label>
                              <label class="btn btn-outline-dark-blue btn-lg">
                                <input type="radio" name="user_type" autocomplete="off" value="cadru_medical" > 
                                    <i class="bi bi-person-vcard"></i> Physician
                              </label>
                            </div>
                        </div>

                        <div class="pt-1 mb-4">
                            <input type="submit" class="btn btn-dark-blue btn-lg btn-block" name="submit" value="Register">
                        </div>

                        <p class="mb-5 pb-0" style="color: #393f81;">Already have an account? <a href="/eeris/login.php"
                            style="color: #393f81;">Sign in here</a></p>
                    </form>
                </div>
    <?php } ?>
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
