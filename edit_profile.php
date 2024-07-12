<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/header.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    
    // Verificăm parola nouă (dacă este introdusă)
    if (!empty($new_password) && ($new_password !== $confirm_new_password || strlen($new_password) < 8)) {
        echo "<div class='message'>
                <p>Parolele noi nu se potrivesc sau nu îndeplinesc cerințele de lungime minimă!</p>
            </div> <br>";
    } else {
        $id = $_SESSION['id'];

        // Actualizăm informațiile utilizatorului în tabelul 'user' utilizând instrucțiuni pregătite
        $update_query = "UPDATE user SET username=?, email=?";
        $params = array($username, $email);
        
        // Dacă este introdusă o parolă nouă validă, o adăugăm în interogare
        if (!empty($new_password)) {
            $update_query .= ", password=?";
            $params[] = $new_password;
        }

        $update_query .= " WHERE id=?";
        $params[] = $id;

        $stmt = mysqli_prepare($con, $update_query);

        if ($stmt) {
            // Legăm parametrii și executăm instrucțiunea
            mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
            $edit_query = mysqli_stmt_execute($stmt);

            if ($edit_query) {
                // Afisăm mesajul de succes pentru actualizarea profilului
                echo "<div class='alert alert-primary'>
                        <p>Profilul a fost actualizat cu succes!</p>
                    </div> <br>";
            } else {
                echo "A apărut o eroare la actualizarea informațiilor utilizatorului: " . mysqli_error($con);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "A apărut o eroare la pregătirea instrucțiunii pentru utilizator: " . mysqli_error($con);
        }
    }
} else {
    // Afisează formularul cu datele curente ale utilizatorului pentru editare
    $id = $_SESSION['id'];
    $query = "SELECT username, email FROM user WHERE id=?";
    
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $res_Uname, $res_Email);

        if (mysqli_stmt_fetch($stmt)) {
?>

<div class="container-fluid d-flex flex-md-row flex-column p-3 align-items-start">
    <a href="javascript:history.back()" type="button" class="btn btn-outline-dark-blue m-1">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
    <div class="border border-dark-blue container-fluid p-3 m-1 rounded">
        <div class="box form-box">
            <h2>Edit Profile</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control" type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $res_Email; ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Parola Noua</label>
                    <input class="form-control" type="password" name="new_password" id="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirma Parola Noua</label>
                    <input class="form-control" type="password" name="confirm_new_password" id="confirm_new_password">
                </div>
                <input type="submit" class="btn btn-dark-blue" name="submit" value="Save">
            </form>
        </div>
    </div>
<?php
        } else {
            echo "Nu s-au găsit date pentru utilizatorul curent.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "A apărut o eroare la pregătirea interogării: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>

<?php
include("{$_SERVER['DOCUMENT_ROOT']}/eeris/template/footer.php");
?>

