<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");


if(isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];
} else {
    echo "<div class='message'>
              <p>Error: Consultation ID is missing.</p>
          </div> <br>";
    exit();
}

if(isset($_POST['submit'])){
    // Preluarea datelor din formular
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    $scor_miscare_umar = $_POST['scor_miscare_umar'];
    $scor_antebrat = $_POST['scor_antebrat'];
    $scor_durere = $_POST['scor_durere'];
    $scor_mobilitate_sold = $_POST['scor_mobilitate_sold'];
    $scor_genunchi = $_POST['scor_genunchi'];
    $scor_glezna = $_POST['scor_glezna'];
    $alte_scori_genunchi = $_POST['alte_scori_genunchi'];
    $flex_musculatura_genunchi = $_POST['flex_musculatura_genunchi'];
    $scor_flexori = $_POST['scor_flexori'];
    $scor_extensori = $_POST['scor_extensori'];
    $scor_glezna_flexie_dorsala = $_POST['scor_glezna_flexie_dorsala'];
    $scor_glezna_flexie_plantara = $_POST['scor_glezna_flexie_plantara'];
    $scor_durere_integrat = $_POST['scor_durere_integrat'];
    $pacient_id = $_POST['pacient_id']; // assuming it's passed in the form

    function calculateIMC($height, $weight) {
        if ($height > 0 && $weight > 0) {
            $height_in_m = $height / 100;
            return $weight / ($height_in_m * $height_in_m);
        }
        return null;
    }

    $bmi_value = calculateIMC($height, $weight);
    // Inserarea datelor în tabelul diagnostic
    $query = "INSERT INTO diagnostic (pacient_id, scor_miscare_umar, scor_antebrat, scor_durere, scor_mobilitate_sold, scor_genunchi, scor_glezna, alte_scori_genunchi, flex_musculatura_genunchi, scor_flexori, scor_extensori, scor_glezna_flexie_dorsala, scor_glezna_flexie_plantara, scor_durere_integrat, consultation_id) 
              VALUES ('$pacient_id', '$scor_miscare_umar', '$scor_antebrat', '$scor_durere', '$scor_mobilitate_sold', '$scor_genunchi', '$scor_glezna', '$alte_scori_genunchi', '$flex_musculatura_genunchi', '$scor_flexori', '$scor_extensori', '$scor_glezna_flexie_dorsala', '$scor_glezna_flexie_plantara', '$scor_durere_integrat', '$appointment_id')";

    $appointment_query = "SELECT * FROM consultations WHERE id = '$appointment_id'";
    $appointment_result = mysqli_query($con, $appointment_query);
    if($appointment_query) {
        $appointment = mysqli_fetch_assoc($appointment_result);
    }

    $bmi_date = $appointment['data_programarii'];
    $bmi_query = "INSERT INTO `imc`(`value`, `pacient_id`, `inaltime`, `greutate`, `created_at`, `consultation_id`) 
                  VALUES ('$bmi_value', '$pacient_id', '$height', '$weight','$bmi_date','$appointment_id')";

    if(mysqli_query($con, $query) &&  mysqli_query($con, $bmi_query)) {
        echo "<div class='message'>
                  <p>Diagnostic completed successfully!</p>
              </div> <br>";

        mysqli_query($con,"UPDATE consultations SET status = 'completed' WHERE id = '$appointment_id'");
    } else {
        echo "<div class='message'>
                  <p>Error: " . mysqli_error($con) . "</p>
              </div> <br>";
    }
}

// Preluăm detalii consultație pentru afișare
$query = "SELECT consultations.*, pacient.nume AS pacient_nume, pacient.prenume AS pacient_prenume 
          FROM consultations 
          JOIN pacient ON consultations.pacient_id = pacient.id 
          WHERE consultations.id = '$appointment_id'";
$result = mysqli_query($con, $query);

if($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<div class='message'>
              <p>Error: Cannot find consultation details.</p>
          </div> <br>";
    exit();
}
?>

<div class="container-fluid d-flex flex-md-row flex-column p-3 align-items-start">
    <a href="/eeris/appointment/list.php" type="button" class="btn btn-outline-dark-blue m-1">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
    <div class="border border-dark-blue container-fluid p-3 m-1 rounded">
        <h2>Fill Diagnostic for <?php echo htmlspecialchars($row['pacient_nume']) . ' ' . htmlspecialchars($row['pacient_prenume']); ?></h2>
        <form action="" method="post">
            <input type="hidden" name="pacient_id" value="<?php echo $row['pacient_id']; ?>">
            <input type="hidden" name="appointment_id" value="<?php echo $appointment_id ?>">
            <div class="form-group">
                <label for="scor_miscare_umar">Greutate (kg)</label>
                <input class="form-control" type="number" name="weight" id="weight" required>
            </div>
            <div class="form-group">
                <label for="scor_miscare_umar">Inaltime (cm)</label>
                <input class="form-control" type="number" name="height" id="height" required>
            </div>
            <div class="form-group">
                <label for="scor_miscare_umar">Scor Mișcare Umăr</label>
                <input class="form-control" type="number" name="scor_miscare_umar" id="scor_miscare_umar" required>
            </div>
            <div class="form-group">
                <label for="scor_antebrat">Scor Antebraț</label>
                <input class="form-control" type="number" name="scor_antebrat" id="scor_antebrat" required>
            </div>
            <div class="form-group">
                <label for="scor_durere">Scor Durere</label>
                <input class="form-control" type="number" name="scor_durere" id="scor_durere" required>
            </div>
            <div class="form-group">
                <label for="scor_mobilitate_sold">Scor Mobilitate Șold</label>
                <input class="form-control" type="number" name="scor_mobilitate_sold" id="scor_mobilitate_sold" required>
            </div>
            <div class="form-group">
                <label for="scor_genunchi">Scor Genunchi</label>
                <input class="form-control" type="number" name="scor_genunchi" id="scor_genunchi" required>
            </div>
            <div class="form-group">
                <label for="scor_glezna">Scor Gleznă</label>
                <input class="form-control" type="number" name="scor_glezna" id="scor_glezna" required>
            </div>
            <div class="form-group">
                <label for="alte_scori_genunchi">Alte Scoruri Genunchi</label>
                <input class="form-control" type="number" name="alte_scori_genunchi" id="alte_scori_genunchi" required>
            </div>
            <div class="form-group">
                <label for="flex_musculatura_genunchi">Flexibilitate Musculatură Genunchi</label>
                <input class="form-control" type="text" name="flex_musculatura_genunchi" id="flex_musculatura_genunchi" required>
            </div>
            <div class="form-group">
                <label for="scor_flexori">Scor Flexori</label>
                <input class="form-control" type="number" name="scor_flexori" id="scor_flexori" required>
            </div>
            <div class="form-group">
                <label for="scor_extensori">Scor Extensori</label>
                <input class="form-control" type="number" name="scor_extensori" id="scor_extensori" required>
            </div>
            <div class="form-group">
                <label for="scor_glezna_flexie_dorsala">Scor Gleznă Flexie Dorsală</label>
                <input class="form-control" type="number" name="scor_glezna_flexie_dorsala" id="scor_glezna_flexie_dorsala" required>
            </div>
            <div class="form-group">
                <label for="scor_glezna_flexie_plantara">Scor Gleznă Flexie Plantară</label>
                <input class="form-control" type="number" name="scor_glezna_flexie_plantara" id="scor_glezna_flexie_plantara" required>
            </div>
            <div class="form-group">
                <label for="scor_durere_integrat">Scor Durere Integrat</label>
                <input class="form-control"type="number" name="scor_durere_integrat" id="scor_durere_integrat" required>
            </div>
            <input type="submit" class="btn btn-dark-blue" name="submit" value="Submit Diagnostic">
        </form>
    </div>
</div>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>
