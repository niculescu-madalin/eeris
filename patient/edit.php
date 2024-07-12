<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

if(!isset($_GET['pacient_id'])) {
    echo "<div class='message'>
              <p>Error: Patient ID is missing.</p>
          </div> <br>";
    exit();
}

$patient_id = $_GET['pacient_id'];

// Preluăm detaliile pacientului
$patient_query = "SELECT * FROM pacient WHERE id = '$patient_id'";
$patient_result = mysqli_query($con, $patient_query);

if($patient_result && mysqli_num_rows($patient_result) > 0) {
    $pacient = mysqli_fetch_assoc($patient_result);
} else {
    echo "<div class='message'>
              <p>Error: Cannot find patient details.</p>
          </div> <br>";
    exit();
}

if(isset($_POST['submit'])){
    // Preluăm datele din formular
    $inaltime = $_POST['inaltime'];
    $greutate = $_POST['greutate'];
    $diagnostic = $_POST['diagnostic'];
    $patologie_asociata = $_POST['patologie_asociata'];
    $telefon = $_POST['telefon'];

    // Actualizăm datele pacientului
    $query = "UPDATE pacient SET 
              inaltime = '$inaltime', 
              greutate = '$greutate', 
              diagnostic = '$diagnostic', 
              patologie_asociata = '$patologie_asociata', 
              telefon = '$telefon' 
              WHERE id = '$pacient_id'";

    if(mysqli_query($con, $query)) {
        echo "<div class='message'>
                  <p>Patient details updated successfully!</p>
              </div> <br>";
    } else {
        echo "<div class='message'>
                  <p>Error: " . mysqli_error($con) . "</p>
              </div> <br>";
    }
}

?>
<div class="container-fluid d-flex flex-md-row flex-column p-3 align-items-start">
    <a href="/eeris/patient/details.php?patient_id=<?php echo $patient_id ?>" type="button" class="btn btn-outline-dark-blue m-1">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
    <div class="border border-dark-blue container-fluid p-3 m-1 rounded">
        <h2>Edit Patient Details</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nume">Last Name</label>
                <input class="form-control" type="text" name="nume" id="nume" value="<?php echo htmlspecialchars($pacient['nume']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="prenume">First Name</label>
                <input class="form-control" type="text" name="prenume" id="prenume" value="<?php echo htmlspecialchars($pacient['prenume']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="varsta">Age</label>
                <input class="form-control" type="number" name="varsta" id="varsta" value="<?php echo htmlspecialchars($pacient['varsta']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="gen">Gender</label>
                <input class="form-control" type="text" name="gen" id="gen" value="<?php echo htmlspecialchars($pacient['gen']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="inaltime">Height</label>
                <input class="form-control" type="number" name="inaltime" id="inaltime" value="<?php echo htmlspecialchars($pacient['inaltime']); ?>" required>
            </div>
            <div class="form-group">
                <label for="greutate">Weight</label>
                <input class="form-control" type="number" name="greutate" id="greutate" value="<?php echo htmlspecialchars($pacient['greutate']); ?>" required>
            </div>
            <div class="form-group">
                <label for="diagnostic">Diagnosis</label>
                <input class="form-control" type="text" name="diagnostic" id="diagnostic" value="<?php echo htmlspecialchars($pacient['diagnostic']); ?>" required>
            </div>
            <div class="form-group">
                <label for="patologie_asociata">Associated Pathology</label>
                <input class="form-control" type="text" name="patologie_asociata" id="patologie_asociata" value="<?php echo htmlspecialchars($pacient['patologie_asociata']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefon">Phone</label>
                <input class="form-control" type="text" name="telefon" id="telefon" value="<?php echo htmlspecialchars($pacient['telefon']); ?>" required>
            </div>
            <input type="submit" class="btn btn-dark-blue" name="submit" value="Update">
        </form>
    </div>
</div>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>
