<?php
include("/xampp/htdocs/eeris/php/config.php");

function calculateIMC($height, $weight) {
    if ($height > 0 && $weight > 0) {
        $height_in_m = $height / 100;
        return $weight / ($height_in_m * $height_in_m);
    }
    return null;
}

if(isset($_GET['pacient_id'])) {
    $pacient_id = $_GET['pacient_id'];
    
    // Preluăm ultimele valori pentru înălțime și greutate ale pacientului
    $query = "SELECT inaltime, greutate FROM pacient WHERE id = '$pacient_id'";
    $result = mysqli_query($con, $query);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $height = $row['inaltime'];
        $weight = $row['greutate'];
        
        $imc_value = calculateIMC($height, $weight);
        
        // Verificăm dacă ultima înregistrare IMC are aceleași valori
        $query = "SELECT * FROM imc WHERE pacient_id = '$pacient_id' ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($con, $query);
        
        if($result && mysqli_num_rows($result) > 0) {
            $last_imc = mysqli_fetch_assoc($result);
            if($last_imc['inaltime'] == $height && $last_imc['greutate'] == $weight) {
                echo "IMC already exists for the latest values.";
                exit();
            }
        }

        // Inserăm IMC-ul calculat în baza de date
        $query = "INSERT INTO imc (value, pacient_id, inaltime, greutate) VALUES ('$imc_value', '$pacient_id', '$height', '$weight')";
        if(mysqli_query($con, $query)) {
            echo "IMC calculated and inserted successfully.";
        } else {
            echo "Error inserting IMC: " . mysqli_error($con);
        }
    } else {
        echo "Error: Cannot retrieve patient details.";
    }
} else {
    echo "Error: Patient ID is missing.";
}
?>
