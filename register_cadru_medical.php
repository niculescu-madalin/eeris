<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Înregistrare Cadru Medical</title>
    <script>
        // Funcție pentru calcularea vârstei în funcție de data nașterii
        function calculateAge() {
            var birthday = new Date(document.getElementById("data_nasterii").value);
            var today = new Date();
            var age = today.getFullYear() - birthday.getFullYear();
            var m = today.getMonth() - birthday.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }
            document.getElementById("varsta").value = age;
        }
    </script>
</head>
<body>
<div class="container">
    <div class="box form-box">
        <?php
        session_start(); // Începeți sesiunea pentru a gestiona utilizatorul curent

        include("php/config.php");

        // Verificați dacă utilizatorul este autentificat și are un user_id valid în sesiune
        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id']; // Obțineți user_id-ul din sesiune

            if(isset($_POST['submit'])){
                // Procesare formularul de înregistrare pentru cadru medical
                $nume = $_POST['nume'];
                $prenume = $_POST['prenume'];
                $data_nasterii = $_POST['data_nasterii'];
                $specialitate = $_POST['specialitate'];
                $telefon = $_POST['telefon'];
                
                // Salvare în baza de date
                $insert_query = "INSERT INTO cadru_medical (nume, prenume, data_nasterii, specialitate, telefon, user_id) 
                                 VALUES ('$nume', '$prenume', '$data_nasterii', '$specialitate', '$telefon', '$user_id')";

                if(mysqli_query($con, $insert_query)) {
                    echo "<div class='message'>
                              <p>Înregistrare cadru medical reușită!</p>
                          </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Înapoi la Pagina Principală</button></a>";
                    // Opcional: Puteți adăuga un mesaj de succes și alte acțiuni după înregistrare
                } else {
                    echo "<div class='message'>
                              <p>Eroare la înregistrare: " . mysqli_error($con) . "</p>
                          </div> <br>";
                    echo "<a href='register_cadru_medical.php'><button class='btn'>Încearcă din Nou</button></a>";
                    // Opcional: Puteți oferi utilizatorului posibilitatea de a încerca din nou
                }
            } else {
                // Afisează formularul pentru înregistrare cadru medical
                ?>
                <header>Înregistrare Cadru Medical</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="nume">Nume</label>
                        <input type="text" name="nume" id="nume" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="prenume">Prenume</label>
                        <input type="text" name="prenume" id="prenume" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="data_nasterii">Data Nașterii</label>
                        <input type="date" name="data_nasterii" id="data_nasterii" autocomplete="off" onchange="calculateAge()" required>
                    </div>

                    <div class="field input">
                        <label for="varsta">Vârsta</label>
                        <input type="number" name="varsta" id="varsta" autocomplete="off" readonly>
                    </div>

                    <div class="field input">
                        <label for="specialitate">Specialitate</label>
                        <select name="specialitate" id="specialitate" required>
                            <option value="" disabled selected>Selectează Specialitatea</option>
                            <option value="ortoped">Ortoped</option>
                            <option value="neurochirurg">Neurochirurg</option>
                            <option value="reumatolog">Reumatolog</option>
                            <option value="medicina_sportiva">Medicină Sportivă</option>
                            <option value="neurolog">Neurolog</option>
                            <option value="other">Altul</option>
                        </select>
                    </div>

                    <div class="field input">
                        <label for="telefon">Telefon</label>
                        <input type="text" name="telefon" id="telefon" autocomplete="off">
                    </div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Înregistrează-te">
                    </div>
                </form>
            <?php
            }
        } else {
            // Dacă utilizatorul nu este autentificat, redirecționați către pagina de autentificare
            header("Location: login.php");
            exit();
        }
        ?>
    </div>
</div>
</body>
</html>
