<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

if($user_type === "cadru_medical"){
// Preluăm pacienții cadrului medical curent
$query = "SELECT pacient.* FROM pacient 
          JOIN consultations ON pacient.id = consultations.pacient_id 
          WHERE consultations.cadru_medical_id = '$physician_id' 
          GROUP BY pacient.id";
}
else {
  $query ="SELECT * FROM pacient";

}

$result = mysqli_query($con, $query);
?>



<div class="container-fluid">
    <h2 class="h3 pt-3 pb-2 pl-1 pr-1">My Patients</h2>
    <?php if(mysqli_num_rows($result) > 0): ?>
    <ul class="list-group">
      <?php
      foreach($result as $row) {
        printf('<li class="list-group-item d-flex justify-content-between">
                    <span><strong><i class="bi bi-person-fill"></i>
                    %s %s</strong> %s - Diagnosis: %s</span>
                    <a href="/eeris/patient/details.php?patient_id=%s">Details</a>
                </li>', 
            htmlspecialchars($row['nume']),
            htmlspecialchars($row['prenume']),
            htmlspecialchars($row['varsta']),
            htmlspecialchars($row['diagnostic']),
            htmlspecialchars($row['id']),
        );
      }
      ?>
    </ul>
    <?php else: ?>
        <p>You have no patients.</p>
    <?php endif; ?>
</div>

<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>