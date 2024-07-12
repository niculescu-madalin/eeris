<?php 
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

// Interogare pentru datele despre cadrele medicale
$query = "SELECT cadru_medical.nume, cadru_medical.prenume, cadru_medical.data_nasterii, cadru_medical.specialitate, cadru_medical.telefon, user.username, user.email 
          FROM cadru_medical 
          JOIN user ON cadru_medical.user_id = user.id";
$result = mysqli_query($con, $query);

?>

    <div class="container">
        <div class="box">
            <br><h2>Medical Staff Data</h2><br>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Date of Birth</th>
                                <th>Specialty</th>
                                <th>Phone</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nume']); ?></td>
                                    <td><?php echo htmlspecialchars($row['prenume']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_nasterii']); ?></td>
                                    <td><?php echo htmlspecialchars($row['specialitate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No medical staff data found.</p>
            <?php endif; ?>
        </div>
    </div>


<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>