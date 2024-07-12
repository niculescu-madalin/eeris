<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

if(isset($_GET['patient_id']) && ctype_digit($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id']; 
} else {
    header("Location: {$_SERVER['DOCUMENT_ROOT']}/eeris/patient/list");
}

$patient_query = "SELECT * FROM pacient WHERE id='{$patient_id}'";
$patient_result = mysqli_query($con, $patient_query);

if ($patient_result) {
    $patient = mysqli_fetch_assoc($patient_result);
}
?>

<div class="container-fluid d-flex flex-md-row flex-column p-3 align-items-start">
    <a href="javascript:history.back()" type="button" class="btn btn-outline-dark-blue m-1">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
    <div class="border border-dark-blue container-fluid p-3 m-1 rounded">
    <?php
    printf(
        '<h2>%s</h2>',
        "{$patient['nume']} {$patient['prenume']}",
    );
    ?>
    <table class="table rounded table-hover table-bordered" style="overflow:hidden;">
    <tbody>
    <?php
    printf(
       '<tr>
            <th scope="row">Diagnosis</th>
            <td>%s</td>
        </tr>
        <tr>
            <th scope="row">Pathology</th>
            <td>%s</td>
        </tr>
        <tr>
            <th scope="row">Age</th>
            <td>%s</td> 
        </tr>
        <tr>
            <th scope="row">Birth Date</th>
            <td>%s</td> 
        </tr>
        <tr>
            <th scope="row">Gender</th>
            <td>%s</td> 
        </tr>
        <tr>
            <th scope="row">Height</th>
            <td>%s</td> 
        </tr>
        <tr>
            <th scope="row">Weight</th>
            <td>%s</td> 
        </tr>
        ',
        $patient['diagnostic'],
        $patient['patologie_asociata'],
        $patient['varsta'],
        $patient['data_nasterii'],
        $patient['gen'],
        $patient['inaltime']." cm",
        $patient['greutate']." kg",

    );
    ?>
    </tbody>
    </table>
    <a class="btn btn-dark-blue m-1" href="edit.php?pacient_id=<?php echo $patient['id']; ?>">Edit</a></td> <br>
    <!-- <button class="btn btn-dark-blue m-1" onclick="calculateIMC(<?php echo $patient['id']; ?>)">Calculate BMI</button> <br> -->
    <button class="btn btn-dark-blue m-1" onclick="assignExercises(<?php echo $patient['id']; ?>)">Assign Exercises</button> <br>
    <button class="btn btn-dark-blue m-1" onclick="assignAdlScore(<?php echo $patient['id']; ?>)">Assign ADL Score</button> <br>
    </div>

<script>
    function viewIMC(pacient_id) {
        window.open("view_imc.php?pacient_id=" + pacient_id, "IMC History", "width=600,height=400");
    }
    function calculateIMC(pacient_id) {
        fetch('calculate_imc.php?pacient_id=' + pacient_id)
            .then(response => response.text())
            .then(data => {
                alert(data);
            });
    }
    function assignExercises(pacient_id) {
        window.location.href = "assign_exercises.php?pacient_id=" + pacient_id;
    }
    function assignAdlScore(pacient_id) {
        window.location.href = "assign_adl_score.php?pacient_id=" + pacient_id;
    }
</script>

<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>