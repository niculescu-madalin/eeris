<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");
?>
<div class="container-fluid">
<?php
// Interogăm baza de date pentru a obține exercițiile pacientului curent
$exercises_query = "SELECT * FROM istoricexercitii WHERE pacient_id = '$pacient_id' ORDER BY data_start_exercitiu DESC";
$exercises_result = mysqli_query($con, $exercises_query);
?>

<h2 class="h3 pt-3 pb-2 pl-1 pr-1">My Exercises</h2>
<?php if(mysqli_num_rows($exercises_result) > 0): ?>

<div class="table-responsive">
<table class="table table-hover rounded" style="overflow:hidden;">
    <thead class="thead-light">
        <tr>
            <th scope="col">Exercise Name</th>
            <th scope="col">Number of Repetitions</th>
            <th scope="col">Muscle Group</th>
            <th scope="col">Speed</th>
            <th scope="col">Duration</th>
            <th scope="col">Start Date</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($exercises_result)): ?>
        <tr>
            <td scope="row"><?php echo htmlspecialchars($row['nume_exercitiu']); ?></td>
            <td scope="row"><?php echo htmlspecialchars($row['nr_repetitii']); ?></td>
            <td scope="row"><?php echo htmlspecialchars($row['muschi']); ?></td>
            <td scope="row"><?php echo htmlspecialchars($row['viteza']); ?></td>
            <td scope="row"><?php echo htmlspecialchars($row['durata']); ?></td>
            <td scope="row"><?php echo htmlspecialchars($row['data_start_exercitiu']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php else: ?>
    <p>You have no exercises assigned.</p>
<?php endif; ?>
</div>

<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>

