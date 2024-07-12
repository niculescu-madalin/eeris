<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Înregistrare Pacient</title>
    <script>
        // Funcție pentru validarea numerelor pozitive
        function validatePositiveNumber(input) {
            if (input.value < 0) {
                input.setCustomValidity('Valoarea trebuie să fie pozitivă.');
            } else {
                input.setCustomValidity('');
            }
        }

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
                // Procesare formularul de înregistrare pentru pacient
                $nume = $_POST['nume'];
                $prenume = $_POST['prenume'];
                $varsta = $_POST['varsta'];
                $gen = $_POST['gen'];
                $inaltime = $_POST['inaltime'];
                $greutate = $_POST['greutate'];
                $diagnostic = $_POST['diagnostic'];
                $patologie_asociata = $_POST['patologie_asociata'];
                $telefon = $_POST['telefon'];
                $data_nasterii = $_POST['data_nasterii'];

                // Salvare în baza de date
                $insert_query = "INSERT INTO pacient (nume, prenume, varsta, gen, inaltime, greutate, diagnostic, patologie_asociata, telefon, user_id, data_nasterii) 
                                 VALUES ('$nume', '$prenume', '$varsta', '$gen', '$inaltime', '$greutate', '$diagnostic', '$patologie_asociata', '$telefon', '$user_id', '$data_nasterii')";

                if(mysqli_query($con, $insert_query)) {
                    echo "<div class='message'>
                              <p>Înregistrare pacient reușită!</p>
                          </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Autentifică-te acum</button></a>";
                    // Opcional: Puteți adăuga un mesaj de succes și alte acțiuni după înregistrare
                } else {
                    echo "<div class='message'>
                              <p>Eroare la înregistrare: " . mysqli_error($con) . "</p>
                          </div> <br>";
                    echo "<a href='register_pacient.php'><button class='btn'>Încearcă din Nou</button></a>";
                    // Opcional: Puteți oferi utilizatorului posibilitatea de a încerca din nou
                }
            } else {
                // Afisează formularul pentru înregistrare pacient
                ?>
                <header>Înregistrare Pacient</header>
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
                        <label for="gen">Gen</label>
                        <select name="gen" id="gen" required>
                            <option value="" disabled selected>Selectează Genul</option>
                            <option value="M">Masculin</option>
                            <option value="F">Feminin</option>
                        </select>
                    </div>

                    <div class="field input">
                        <label for="inaltime">Înălțime (cm)</label>
                        <input type="number" name="inaltime" id="inaltime" autocomplete="off" required oninput="validatePositiveNumber(this)">
                    </div>

                    <div class="field input">
                        <label for="greutate">Greutate (kg)</label>
                        <input type="number" name="greutate" id="greutate" autocomplete="off" required oninput="validatePositiveNumber(this)">
                    </div>

                    <div class="field input">
                        <label for="diagnostic">Diagnostic</label>
                        <input type="text" name="diagnostic" id="diagnostic" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="patologie_asociata">Patologie Asociată</label>
                        <input type="text" name="patologie_asociata" id="patologie_asociata" autocomplete="off">
                    </div>

                    <div class="field input">
                        <label for="telefon">Telefon</label>
                        <input type="text" name="telefon" id="telefon" autocomplete="off">
                    </div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Înregistrează-te">
                    </div>
                    <div class="links">
                        Deja membru? <a href="login.php">Autentifică-te</a>
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
