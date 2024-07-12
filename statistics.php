<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");


$user_id = $_SESSION['id']; // Presupunem că id-ul userului este stocat în sesiune

// Preluăm datele IMC și greutate din baza de date
$query = "SELECT created_at, value AS imc, greutate FROM imc WHERE pacient_id = ? ORDER BY created_at ASC";
$stmt = mysqli_prepare($con, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $pacient_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $date, $imc, $greutate);
    
    $dates = [];
    $imc_values = [];
    $greutate_values = [];
    
    while (mysqli_stmt_fetch($stmt)) {
        $dates[] = $date;
        $imc_values[] = $imc;
        $greutate_values[] = $greutate;
    }
    mysqli_stmt_close($stmt);
} else {
    echo "A apărut o eroare la preluarea datelor: " . mysqli_error($con);
}

mysqli_close($con);

// Verificare date
echo "<script>console.log('Dates: " . json_encode($dates) . "');</script>";
echo "<script>console.log('IMC Values: " . json_encode($imc_values) . "');</script>";
echo "<script>console.log('Weight Values: " . json_encode($greutate_values) . "');</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    
    <div class="container">
        <br><h2>Statistics: IMC & Weight</h2><br>
        <div class="chart-container">
            <canvas id="imcWeightChart"></canvas>
        </div>
    </div>
    <script>
        const dates = <?php echo json_encode($dates); ?>;
        const imcValues = <?php echo json_encode($imc_values); ?>;
        const weightValues = <?php echo json_encode($greutate_values); ?>;

        const ctx = document.getElementById('imcWeightChart').getContext('2d');
        const imcWeightChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'IMC',
                        data: imcValues,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 3,
                        fill: false,
                        yAxisID: 'y-axis-imc',
                        tension: 0.4 // Smooths the line
                    },
                    {
                        label: 'Weight',
                        data: weightValues,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderWidth: 3,
                        fill: false,
                        yAxisID: 'y-axis-weight',
                        tension: 0.4 // Smooths the line
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    'y-axis-imc': {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'IMC'
                        },
                        beginAtZero: true
                    },
                    'y-axis-weight': {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Weight (kg)'
                        },
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false // only want the grid lines for one axis to show up
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

