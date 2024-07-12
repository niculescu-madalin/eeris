<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");


if(!isset($_GET['pacient_id'])) {
    echo "<div class='message'>
              <p>Error: Patient ID is missing.</p>
          </div> <br>";
    exit();
}

$patient_id = $_GET['pacient_id'];

if(isset($_POST['submit'])){
    // Preluăm datele din formular
    $scor_adl = $_POST['scor_adl'];
    $descriere = $_POST['descriere'];
    $data = $_POST['data'];

    // Inserăm scorul ADL în baza de date
    $query = "INSERT INTO scor_adl (pacient_id, scor_adl, data, descriere) 
              VALUES ('$patient_id', '$scor_adl', '$data', '$descriere')";

    if(mysqli_query($con, $query)) {
        echo "<div class='message'>
                  <p>ADL Score assigned successfully!</p>
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
        <h2>Assign ADL Score to Patient</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="scor_adl">ADL Score</label>
                <input class="form-control" type="number" name="scor_adl" id="scor_adl" required>
            </div>
            <div class="form-group">
                <label for="descriere">Description</label>
                <textarea class="form-control" name="descriere" id="descriere" required></textarea>
            </div>
            <div class="form-group">
                <label for="data">Date</label>
                <input class="form-control" type="date" name="data" id="data" required>
            </div>
            <input type="submit" class="btn btn-dark-blue" name="submit" value="Assign ADL Score">
        </form>
    </div>
</div>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>

