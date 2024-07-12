<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

if (isset($_GET['appointment_id']) && ctype_digit($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];
} else {
    header("Location: {$_SERVER['DOCUMENT_ROOT']}/eeris/appointment/list.php");
}


$appointment_sql = "SELECT c.data_programarii, c.status, c.id, c.cadru_medical_id, c.pacient_id, cm.nume, cm.prenume, cm.specialitate 
	FROM consultations c
    JOIN cadru_medical cm ON c.cadru_medical_id = cm.id
    JOIN pacient p ON c.pacient_id = p.id
    WHERE c.id = '$appointment_id'";
$appointment_result = mysqli_query($con, $appointment_sql);

if ($appointment_result) {
    $appointment = mysqli_fetch_assoc($appointment_result);
}

$physician_query = "SELECT nume, prenume FROM cadru_medical WHERE id = {$appointment['cadru_medical_id']}";
$physician_result = mysqli_query($con, $physician_query);
$physician = mysqli_fetch_assoc($physician_result);

$patient_query = "SELECT nume, prenume FROM pacient WHERE id = {$appointment['pacient_id']}";
$patient_result = mysqli_query($con, $patient_query);
$patient = mysqli_fetch_assoc($patient_result);
?>

<div class="container-fluid d-flex flex-md-row flex-column p-3 align-items-start">
    <a href="javascript:history.back()" type="button" class="btn btn-outline-dark-blue m-1">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
    <div class="border border-dark-blue container-fluid p-3 m-1 rounded">
        <?php
        printf(
            '<h2>%s - %s<br>
                    <small class="text-muted">Diagnostic done by Dr. %s %s</small> <br>
                    <small class="text-muted">Patient: %s %s</small>
                    </h2>
                    ',
            $appointment['data_programarii'],
            ucfirst($appointment['status']),
            $physician['nume'],
            $physician['prenume'],
            $patient['nume'],
            $patient['prenume'],
        );
        ?>
        <hr>
        <?php
        $diagnostic_query = "SELECT * FROM diagnostic WHERE consultation_id='$appointment_id'";
        $diagnostic_result = mysqli_query($con, $diagnostic_query);
        $diagnostic = mysqli_fetch_assoc($diagnostic_result);
        if ($diagnostic) {
            printf(
                '<table class="table rounded table-hover table-bordered" style="overflow:hidden;">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Scores</th>
                        </tr>
                </thead>
                <tbody>
                <tr>
                <th scope="row">Shoulder Movement Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_miscare_umar']
            );
            printf(
                '<tr>
                <th scope="row">Forearm Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_antebrat']
            );

            printf(
                '<tr>
                <th scope="row">Pain Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_durere']
            );

            printf(
                '<tr>
                <th scope="row">Hip Mobility Score</th>
                <td>%s</td> 
            </tr>',
                $diagnostic['scor_mobilitate_sold']
            );

            printf(
                '<tr>
                <th scope="row">Knee Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_genunchi']
            );
            printf(
                '<tr>
                <th scope="row">Ankle Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_glezna']
            );
            printf(
                '<tr>
                <th scope="row">Other Knee Scores</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['alte_scori_genunchi']
            );
            printf(
                '<tr>
                <th scope="row">Musculature Flexibility</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['flex_musculatura_genunchi']
            );
            printf(
                '<tr>
                <th scope="row">Flexor Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_flexori']
            );
            printf(
                '<tr>
                <th scope="row">Extensor Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_extensori']
            );
            printf(
                '<tr>
                <th scope="row">Ankle Dorsiflexion Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_glezna_flexie_dorsala']
            );
            printf(
                '<tr>
                <th scope="row">Ankle Plantarflexion Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_glezna_flexie_plantara']
            );
            printf(
                '<tr>
                <th scope="row">Integrated Pain Score</th>
                <td>%s</td> 
                </tr>',
                $diagnostic['scor_durere_integrat']
            );
        } else {
            printf('<div class="alert alert-secondary m-0" role="alert">
                        No details yet
                    </div>');
        }
        ?>
        </tbody>
        </table>
        <hr>
        <h4>Body Mass Index </h4>
        <?php 
        $bmi_query = "SELECT * FROM imc WHERE consultation_id='$appointment_id'";
        $bmi_result = mysqli_query($con, $bmi_query);
        $bmi = mysqli_fetch_assoc($bmi_result);
        

        if ($bmi) {
            $bmi_val = $bmi['value'];
            $bmi_case = match(true) {
                $bmi_val < 16.0 => "Underweight (Severe)",
                $bmi_val < 16.9 => "Underweight (Moderate)",
                $bmi_val < 18.4 => "Underweight (Mild)",
                $bmi_val < 24.9 => "Normal",
                $bmi_val < 29.9 => "Overweight",
                $bmi_val < 34.9 => "Overweight (Obese Class I)",
                $bmi_val < 39.9 => "Overweight (Obese Class II)",
                default => "Overweight (Obese Class III)",
            };

            $alert_color = match(true) {
                $bmi_val < 16.0 => "alert alert-danger m-0 pt-1 pb-1 pl-3 pr-3",
                $bmi_val < 18.4 => "alert alert-warning m-0 pt-1 pb-1 pl-3 pr-3",
                $bmi_val < 24.9 => "alert alert-succes m-0 pt-1 pb-1 pl-3 pr-3",
                $bmi_val < 29.9 => "alert alert-warning m-0 pt-1 pb-1 pl-3 pr-3",
                default => "alert alert-danger m-0 pt-1 pb-1 pl-3 pr-3",
            };
            
            printf('<div class="card p-3 d-flex flex-row justify-content-between align-items-center">
                    <div> Value: <strong>%s</strong> </div>
                    <div> Height: <strong>%scm</strong> </div>
                    <div> Weight: <strong>%scm</strong> </div>
                    <div class="%s" role="alert">
                        %s
                    </div>
                    </div>', 
                $bmi['value'],
                $bmi['inaltime'],
                $bmi['greutate'],
                $alert_color,
                $bmi_case,
            );
        } else {
            printf('<div class="alert alert-secondary m-0" role="alert">
                        No Body Mass Index  yet
                    </div>');
        }
        ?>
        
    </div>
</div>

<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>