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
    $nume_exercitiu = $_POST['nume_exercitiu'];
    $nr_repetitii = $_POST['nr_repetitii'];
    $muschi = $_POST['muschi'];
    $viteza = $_POST['viteza'];
    $durata_ore = $_POST['durata_ore'];
    $durata_minute = $_POST['durata_minute'];
    $durata_secunde = $_POST['durata_secunde'];
    $data_start_exercitiu = $_POST['data_start_exercitiu'];

    // Formatează durata într-un format HH:MM:SS
    $durata = sprintf('%02d:%02d:%02d', $durata_ore, $durata_minute, $durata_secunde);

    // Inserăm exercițiile în baza de date
    $query = "INSERT INTO istoricexercitii (pacient_id, nr_repetitii, muschi, viteza, durata, data_start_exercitiu, nume_exercitiu) 
              VALUES ('$patient_id', '$nr_repetitii', '$muschi', '$viteza', '$durata', '$data_start_exercitiu', '$nume_exercitiu')";

    if(mysqli_query($con, $query)) {
        echo "<div class='message'>
                  <p>Exercise assigned successfully!</p>
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
        <h2>Assign Exercises to Patient</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nume_exercitiu">Exercise Name</label>
                <input class="form-control" type="text" name="nume_exercitiu" id="nume_exercitiu" required>
            </div>
            <div class="form-group">
                <label for="nr_repetitii">Number of Repetitions</label>
                <input class="form-control" type="number" name="nr_repetitii" id="nr_repetitii" required>
            </div>
            <div class="form-group">
                <label for="muschi">Muscle Group</label>
                <input class="form-control" type="text" name="muschi" id="muschi" required>
            </div>
            <div class="form-group">
                <label for="viteza">Speed</label>
                <input class="form-control" type="text" name="viteza" id="viteza" required>
            </div>
            <div class="form-group">
                <label for="durata_ore">Duration Hours</label>
                <input class="form-control" type="number" name="durata_ore" id="durata_ore" min="0" max="23" required>
            </div>
            <div class="form-group">
                <label for="durata_minute">Duration Minutes</label>
                <input class="form-control" type="number" name="durata_minute" id="durata_minute" min="0" max="59" required>
            </div>
            <div class="form-group">
                <label for="durata_secunde">Duration Seconds</label>
                <input class="form-control" type="number" name="durata_secunde" id="durata_secunde" min="0" max="59" required>
            </div>
            <div class="form-group">
                <label for="data_start_exercitiu">Start Date</label>
                <input class="form-control" type="date" name="data_start_exercitiu" id="data_start_exercitiu" required>
            </div>
            <input type="submit" class="btn btn-dark-blue" name="submit" value="Assign Exercise">
        </form>
    </div>
</div>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>
