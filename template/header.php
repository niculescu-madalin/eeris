<?php
session_start();

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['valid'])) {
    header("Location: ../eeris/login.php");
    exit();
}

// Include configurația bazei de date
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/php/config.php");

if (isset($_SESSION['id'])) {
    $id = mysqli_real_escape_string($con, $_SESSION['id']);

    // Interogăm baza de date pentru a obține detalii despre utilizator
    $user_query = "SELECT * FROM user WHERE id = '$id'";
    $user_result = mysqli_query($con, $user_query);

    if ($user_result) {
        $user = mysqli_fetch_assoc($user_result);
        $username = $user['username'];
        $user_id = $user['id'];
        $user_email = $user['email'];
        $user_type = $user['tip_utilizator'];
    } else {
        die("Query Error: " . mysqli_error($con));
    }

    if ($user_type === "pacient") {
        $pacient_query = "SELECT * FROM pacient WHERE user_id = '$user_id'";
        $pacient_result =  mysqli_query($con, $pacient_query);
        if ($pacient_result) {
            $pacient = mysqli_fetch_assoc($pacient_result);
            $pacient_id = $pacient['id'];
            $first_name = $pacient['prenume'];
            $last_name =  $pacient['nume'];
        }
    } else if ($user_type === "cadru_medical") {
        $physician_query = "SELECT u.username, u.email, cm.id, cm.nume, cm.prenume, cm.specialitate FROM user u
                JOIN cadru_medical cm ON u.id = cm.user_id
                WHERE u.id = '$id'";
        $physician_result = mysqli_query($con, $physician_query);

        if($physician_result) {
            $physician = mysqli_fetch_assoc($physician_result);
            $physician_id = $physician['id'];
            $first_name = $physician['prenume'];
            $last_name =  $physician['nume'];
            $speciality = $physician['specialitate'];
        } else {
            die("Query Error: " . mysqli_error($con));
        }
    } else if ($user_type === "admin") {
        // No additional queries needed for admin at this point
    }

} else {
    die("Session ID not set");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS</title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/eeris/template/bootstrap.config.css">
      
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue">
  <a class="navbar-brand" href="/eeris/home.php"><i class="bi bi-clipboard2-pulse-fill"></i> Health Management System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" href="/eeris/home.php">Home<span class="sr-only">(current)</span></a>
      </li>
    <?php if($user_type === 'pacient') : ?>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/appointment/list.php">Appointments <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/exercises/list.php">Exercises<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/statistics.php">Statistics <span class="sr-only">(current)</span></a>
      </li>
      
    <?php elseif($user_type === 'cadru_medical') : ?>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/appointment/list.php">Appointments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/patient/list.php">Patients</a>
      </li>
    <?php elseif($user_type === 'admin') : ?>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/appointment/list.php">Appointments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/physician/list.php">Physicians</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/eeris/patient/list.php">Patients</a>
      </li>
    <?php else : ?>
      <li class="nav-item">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
    <?php endif; ?>

    </ul>
    <ul class="navbar-nav ml-auto justify-content-end">
      <li class="nav-item dropdown justify-content-end">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo "{$username} ({$user_email})" ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <!-- <a class="dropdown-item" href="#">View Profile</a> -->
          <a class="dropdown-item" href="/eeris/edit_profile.php">Edit Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/eeris/php/logout.php">Log Out</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
