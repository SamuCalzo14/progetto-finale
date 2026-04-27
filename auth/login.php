<?php
/* LOGIN */
session_start();
require_once "../config/db.php";

/* Se è già loggato manda alla pagina giusta */
if (isset($_SESSION["ruolo"])) {
    if ($_SESSION["ruolo"] === "fisio") {
        header("Location: ../fisio/dashboard.php");
    } else {
        header("Location: ../utente/mie_prenotazioni.php");
    }
    exit;
}

$errore = "";

/* Quando arriva il form */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {

        $errore = "Compila tutti i campi.";

    } else {

        /* Cerca tra gli utenti normali */
        $stmt = mysqli_prepare($conn, "SELECT id_utente, nome, cognome, password FROM utente WHERE email = ? AND attivo = 'attivo'");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $utente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($utente && password_verify($password, $utente["password"])) {
            $_SESSION["id_utente"] = $utente["id_utente"];
            $_SESSION["nome"]      = $utente["nome"];
            $_SESSION["cognome"]   = $utente["cognome"];
            $_SESSION["ruolo"]     = "utente";
            header("Location: ../utente/mie_prenotazioni.php");
            exit;
        }

        /* Cerca tra i fisioterapisti */
        $stmt2 = mysqli_prepare($conn, "SELECT id_fisio, nome, cognome, password FROM fisiot WHERE email = ? AND attivo = 'attivo'");
        mysqli_stmt_bind_param($stmt2, "s", $email);
        mysqli_stmt_execute($stmt2);
        $fisio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

        if ($fisio && password_verify($password, $fisio["password"])) {
            $_SESSION["id_fisio"] = $fisio["id_fisio"];
            $_SESSION["nome"]     = $fisio["nome"];
            $_SESSION["cognome"]  = $fisio["cognome"];
            $_SESSION["ruolo"]    = "fisio";
            header("Location: ../fisio/dashboard.php");
            exit;
        }

        $errore = "Email o password non corretti. Se non hai un account, registrati prima.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi — Studio RC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,700;1,700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/stile_comune.css">
</head>
<body>

<!-- NAVBAR -->
<div id="navbar">
    <a href="../index.html" class="logo">Studio <span>RC</span></a>
    <nav>
        <a href="../index.html">Home</a>
        <a href="../index.html#studio">Lo studio</a>
        <a href="../index.html#sec-fisio">Il fisioterapista</a>
        <a href="registra.php" class="btn-prenota-nav"><i class="fa-regular fa-address-card"></i> Registrati</a>
    </nav>
    <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </button>
</div>

<div class="menu-mobile" id="menu-mobile">
    <a href="../index.html" onclick="chiudiMenu()">Home</a>
    <a href="registra.php"  onclick="chiudiMenu()">Registrati</a>
</div>

<!-- LAYOUT: verde a sinistra, form a destra -->
<div class="auth-pagina">

    <!-- SINISTRA verde -->
    <div class="auth-sinistra">
        <p class="auth-sinistra-etichetta">· Studio RC · Fisioterapia ·</p>
        <h2>Bentornato<br><span>nel tuo spazio.</span></h2>
        <p>Accedi per gestire le tue prenotazioni e consultare lo storico delle tue visite.</p>

        <div class="auth-punti">
            <div class="auth-punto">
                <i class="fa-solid fa-calendar-check"></i>
                Gestisci le tue prenotazioni
            </div>
            <div class="auth-punto">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Consulta lo storico visite
            </div>
            <div class="auth-punto">
                <i class="fa-solid fa-shield-halved"></i>
                Accesso sicuro e protetto
            </div>
        </div>
    </div>

    <!-- DESTRA form -->
    <div class="auth-destra">
        <div class="auth-form-wrap">

            <p class="etichetta">· Accesso ·</p>
            <h1 class="titolo-form">Accedi</h1>
            <p class="sottotitolo-form">Inserisci le tue credenziali per entrare.</p>

            <!-- Errore (appare solo se c'è) -->
            <?php if ($errore): ?>
                <div class="messaggio-errore">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= $errore ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" onsubmit="return validaLogin()">

                <div class="campo">
                    <label for="email">Email</label>
                    <div class="campo-icona">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="la.tua@email.it" required>
                    </div>
                </div>

                <div class="campo">
                    <label for="password">Password</label>
                    <div class="campo-icona">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Accedi <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="link-sotto">
                Non hai un account? <a href="registra.php">Registrati gratis</a>
            </p>

        </div>
    </div>

</div>

<script src="../js/indexScript.js"></script>
<script>
/* Controllo base prima di inviare il form */
function validaLogin() {
    if (document.getElementById("email").value === "" ||
        document.getElementById("password").value === "") {
        alert("Compila tutti i campi.");
        return false;
    }
    return true;
}
</script>

</body>
</html>