<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");
?>
<div class="container-fluid">

<?php
if($user_type === "pacient"){

if(isset($_POST['request_appointment'])) {
    $preferred_time = mysqli_real_escape_string($con, $_POST['preferred_time']);
    $cadru_medical_id = mysqli_real_escape_string($con, $_POST['physician']);

    // Validarea datelor de intrare
    if(!empty($preferred_time)){
        // Verificăm dacă ora selectată este în intervalul 9-17
        $hour = date('H', strtotime($preferred_time));
        if($hour < 9 || $hour >= 17){
            echo '<div class="alert alert-warning pl-3 pr-3 mt-3 alert-dismissible fade show">
                    <strong>Failed</strong>
                    <p>Select a time between 09:00 and 17:00.</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } else {
            // Calculăm intervalul orar de 1 oră
            $start_time = date('Y-m-d H:i:s', strtotime($preferred_time));
            $end_time = date('Y-m-d H:i:s', strtotime($preferred_time) + 3600);

            // Verificăm dacă există deja consultații în intervalul de 1 oră
            $conflict_query = "SELECT * FROM consultations 
                               WHERE cadru_medical_id = '$cadru_medical_id' AND
                                     (data_programarii BETWEEN '$start_time' AND '$end_time')";
            $conflict_result = mysqli_query($con, $conflict_query);

            if(mysqli_num_rows($conflict_result) == 0){
                // Nu există conflicte, inserăm cererea în baza de date cu statusul "pending"
                $query = "INSERT INTO consultations (data_programarii, pacient_id, cadru_medical_id, status) 
                          VALUES ('$start_time', '$pacient_id', '$cadru_medical_id', 'pending')";
                if(mysqli_query($con, $query)){
                    echo '<div class="alert alert-success pl-3 pr-3 mt-3 alert-dismissible fade show">
                          <strong>Succesful</strong>
                          <p>Appoinment requested successfully!</p>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';
                } else {
                    echo '<div class="alert alert-danger pl-3 pr-3 mt-3 alert-dismissible fade show">
                          <strong>Failed</strong>
                          <p>Error: " . mysqli_error($con) . "</p>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';
                          
                }
            } else {
                // Există un conflict de programare

                echo '<div class="alert alert-warning pl-3 pr-3 mt-3 alert-dismissible fade show">
                      <strong>Failed</strong>
                      <p>There is already a consultation scheduled within this time.</p>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';

            }
        }
    } else {
        echo "<div class='alert alert-warning'>
                  <p>Preferred time cannot be empty!</p>
              </div> <br>";
    }

}
} else if($user_type === "cadru_medical") {
    if(isset($_POST['accept_request'])) {
        $appointment_id = $_POST['appointment_id'];
        mysqli_query($con,"UPDATE consultations SET status ='accepted' WHERE id = '$appointment_id'");
    }

}
?>

<?php if($user_type === "pacient") : ?>
<div class="mt-3 mb-3 p-1 card card-body bg-light border-dark-blue ">
<form method="post" action="" id="request_appointment_form" class="collapse p-3" >
    <div class="form-group">
        <label>Wanted Date</label>
        <input 
            type="datetime-local"
            name="preferred_time"
            id="preferred_time"
            placeholder="Enter date"
            class="form-control col"
            autocomplete="off" 
            required 
        >
    </div>
    <div class="form-group">
        <label>Wanted physician</label>
        <select name="physician" id="physician"  class="form-control" aria-label="Default select example">
            <option value="" disabled selected>Choose a physican</option>
            <?php
                $query = "SELECT * FROM cadru_medical";
                $result = mysqli_query($con, $query);
                foreach($result as $row) {
                    printf('<option value="%s">%s %s - %s</option>', 
                        $row['id'],
                        $row['nume'],
                        $row['prenume'],
                        ucfirst($row['specialitate']));
                }
            ?>
        </select>
    </div>
    <input type="submit" name="request_appointment" class="btn btn-dark-blue mb-3" value="Request an Appointment">
</form>
<a class="btn btn-light" data-toggle="collapse" href="#request_appointment_form" role="button" aria-expanded="false" aria-controls="collapseExample">
    Request an appointment
</a>
</div>

<?php endif; ?>

<table class="table table-hover rounded mt-3" style="overflow:hidden;">
  <thead class="thead-light">
    <tr>
    <?php if($user_type === "pacient") : ?>
      <th scope="col">Date</th>
      <th scope="col">Physician</th>
      <th scope="col">Status</th>
      <th scope="col"></th>
    <?php elseif($user_type === "cadru_medical") : ?>
      <th scope="col">Date</th>
      <th scope="col">Patient</th>
      <th scope="col">Diagnostic</th>
      <th scope="col">Status</th>
      <th scope="col" colspan="2"></th>
    <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php
    if($user_type === "pacient") {
        $appointments_query = "SELECT * FROM consultations WHERE pacient_id ='$pacient_id' ORDER BY `consultations`.`data_programarii` ASC";
    } else if ($user_type === "cadru_medical") {
        $appointments_query = "SELECT * FROM consultations WHERE cadru_medical_id ='$physician_id' ORDER BY `consultations`.`data_programarii` ASC";
    } else {
        $appointments_query = "SELECT * FROM consultations";
    }
    
    $appointments_result =  mysqli_query($con, $appointments_query);
    foreach($appointments_result as $appintment) {
        $physician_query = "SELECT nume, prenume FROM cadru_medical WHERE id = {$appintment['cadru_medical_id']}";
        $physician_result = mysqli_query($con, $physician_query);
        $physician = mysqli_fetch_assoc($physician_result);

        $patient_query = "SELECT nume, prenume, diagnostic, varsta FROM pacient WHERE id = {$appintment['pacient_id']}";
        $patient_result = mysqli_query($con, $patient_query);
        $patient = mysqli_fetch_assoc($patient_result);

        if($user_type === "pacient") {
            printf(
                '<tr scope="row">
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td><a class="btn btn-link p-0" href="/eeris/appointment/details.php?appointment_id=%s">Details</a></td>
                </tr>',
                $appintment['data_programarii'],
                "{$physician['nume']} {$physician['prenume']}",
                ucfirst($appintment['status']),
                $appintment['id'],
            );
        } else {
            printf(
                '<tr scope="row">
                    <td>%s</span>
                    <td><a href="/eeris/patient/details.php?patient_id=%s"><i class="bi bi-person-fill"></i> %s</a></td>
                    <td>%s</td>
                    <td>%s</td>
                    <td><a class="btn btn-link p-0" href="/eeris/appointment/details.php?appointment_id=%s">Details</a></td>
                    ',
                $appintment['data_programarii'],
                $appintment['pacient_id'],
                "{$patient['nume']} {$patient['prenume']} ({$patient['varsta']})",
                $patient['diagnostic'],
                ucfirst($appintment['status']),
                $appintment['id'],
            );
            if($appintment['status'] === "accepted"){
                printf('<td><a class="btn btn-link p-0" href="/eeris/appointment/fill.php?appointment_id=%s">Complete diagnosis</a></td>', $appintment['id'],);
            } else if($appintment['status'] === "pending") {
                printf('<td>
                            <form method="post" class="p-0 m-0">
                                <input type="hidden" name="appointment_id" value="%s"> 
                                <input type="submit" name="accept_request" class="btn btn-link m-0 p-0" value="Accept Request" /> 
                            </form>
                        </td>', $appintment['id'],);
            } else {
                printf('<td></td>');
            }
            printf('</tr>');

        }
    }   
    ?>
  </tbody>
</table>
</div>

<script>
let dateInput = document.getElementById("preferred_time");
dateInput.min = new Date().toISOString().slice(0,new Date().toISOString().lastIndexOf(":"));
</script>
<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>
