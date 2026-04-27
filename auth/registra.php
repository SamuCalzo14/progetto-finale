<?php
/* REGISTRAZIONE */
session_start();
require_once "../config/db.php";

/* Se è già loggato non ha senso registrarsi */
if (isset($_SESSION["ruolo"])) {
    header("Location: ../index.html");
    exit;
}

$errore   = "";
$successo = "";

/* Quando arriva il form */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome         = trim($_POST["nome"]);
    $cognome      = trim($_POST["cognome"]);
    $data_nascita = trim($_POST["data_nascita"]);
    $sex          = trim($_POST["sex"]);
    $num_tell     = trim($_POST["num_tell"]);
    $email        = trim($_POST["email"]);
    $password     = trim($_POST["password"]);
    $password2    = trim($_POST["password2"]);
    $privacy      = isset($_POST["privacy"]) ? 1 : 0;

    /* Controlli */
    if (empty($nome) || empty($cognome) || empty($email) || empty($password) || empty($data_nascita)) {
        $errore = "Compila tutti i campi obbligatori.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = "Indirizzo email non valido.";

    } elseif (strlen($password) < 6) {
        $errore = "La password deve essere di almeno 6 caratteri.";

    } elseif ($password !== $password2) {
        $errore = "Le due password non coincidono.";

    } elseif (!$privacy) {
        $errore = "Devi accettare la privacy policy.";

    } else {

        /* Controlla se l'email esiste già */
        $stmt = mysqli_prepare($conn, "SELECT id_utente FROM utente WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errore = "Questa email è già registrata.";

        } else {

            /* Cripta la password e inserisce l'utente */
            $password_criptata = password_hash($password, PASSWORD_DEFAULT);

            $stmt2 = mysqli_prepare($conn, "INSERT INTO utente (nome, cognome, data_nascita, sex, num_tell, email, password, privacy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "sssssssi", $nome, $cognome, $data_nascita, $sex, $num_tell, $email, $password_criptata, $privacy);

            if (mysqli_stmt_execute($stmt2)) {
                $successo = "Registrazione completata!";
            } else {
                $errore = "Errore durante la registrazione. Riprova.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione — Studio RC</title>
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
        <a href="login.php" class="btn-accedi-nav">
            <i class="fa-regular fa-circle-user"></i> Accedi
        </a>
    </nav>
    <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </button>
</div>

<div class="menu-mobile" id="menu-mobile">
    <a href="../index.html" onclick="chiudiMenu()">Home</a>
    <a href="login.php"     onclick="chiudiMenu()">Accedi</a>
</div>

<!-- LAYOUT: verde a sinistra, form a destra -->
<div class="auth-pagina">

    <!-- SINISTRA verde -->
    <div class="auth-sinistra">
        <p class="auth-sinistra-etichetta">· Studio RC · Fisioterapia ·</p>
        <h2>Inizia il tuo<br><span>percorso.</span></h2>
        <p>Crea il tuo account e prenota la tua prima visita in pochi minuti.</p>

        <div class="auth-punti">
            <div class="auth-punto">
                <i class="fa-solid fa-calendar-plus"></i>
                Prenota visite online
            </div>
            <div class="auth-punto">
                <i class="fa-solid fa-file-medical"></i>
                Storico visite sempre disponibile
            </div>
            <div class="auth-punto">
                <i class="fa-solid fa-user-shield"></i>
                Dati protetti e sicuri
            </div>
        </div>
    </div>

    <!-- DESTRA form -->
    <div class="auth-destra">
        <div class="auth-form-wrap" style="max-width: 500px;">

            <p class="etichetta">· Nuovo account ·</p>
            <h1 class="titolo-form">Registrati</h1>
            <p class="sottotitolo-form">Compila i campi per creare il tuo account.</p>

            <!-- Successo -->
            <?php if ($successo): ?>
                <div class="messaggio-ok">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        Registrazione completata!<br>
                        <a href="login.php" style="color:#2d5a3d; font-weight:600;">Vai al login →</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Errore -->
            <?php if ($errore): ?>
                <div class="messaggio-errore">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= $errore ?>
                </div>
            <?php endif; ?>

            <!-- Form (sparisce dopo registrazione riuscita) -->
            <?php if (!$successo): ?>
            <form method="POST" action="" onsubmit="return validaForm()">

                <!-- Nome e Cognome affiancati -->
                <div class="campo-doppio">
                    <div class="campo">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" placeholder="Mario" required>
                    </div>
                    <div class="campo">
                        <label for="cognome">Cognome *</label>
                        <input type="text" id="cognome" name="cognome" placeholder="Rossi" required>
                    </div>
                </div>

                <!-- Data nascita e Sesso affiancati -->
                <div class="campo-doppio">
                    <div class="campo">
                        <label for="data_nascita">Data di nascita *</label>
                        <input type="date" id="data_nascita" name="data_nascita" required>
                    </div>
                    <div class="campo">
                        <label for="sex">Sesso *</label>
                        <select id="sex" name="sex" required>
                            <option value="">— seleziona —</option>
                            <option value="M">Maschio</option>
                            <option value="F">Femmina</option>
                            <option value="Altro">Altro</option>
                        </select>
                    </div>
                </div>

                <!-- Telefono -->
                <div class="campo">
                    <label for="num_tell">Telefono</label>
                    <div class="campo-icona">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" id="num_tell" name="num_tell" placeholder="+39 333 000 0000">
                    </div>
                </div>

                <!-- Email -->
                <div class="campo">
                    <label for="email">Email *</label>
                    <div class="campo-icona">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="la.tua@email.it" required>
                    </div>
                </div>

                <!-- Password e Conferma affiancate -->
                <div class="campo-doppio">
                    <div class="campo">
                        <label for="password">Password *</label>
                        <div class="campo-icona">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="min. 6 caratteri" required>
                        </div>
                    </div>
                    <div class="campo">
                        <label for="password2">Conferma *</label>
                        <div class="campo-icona">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="password2" name="password2" placeholder="ripeti" required>
                        </div>
                    </div>
                </div>

                <!-- Privacy -->
                <div class="campo-check">
                    <input type="checkbox" id="privacy" name="privacy" required>
                    <label for="privacy">
                        Ho letto e accetto la <a href="#">privacy policy</a>.
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    Crea account <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="link-sotto">
                Hai già un account? <a href="login.php">Accedi</a>
            </p>
            <?php endif; ?>

        </div>
    </div>

</div>

<script src="../js/indexScript.js"></script>
<script>
/* Controlli base prima di inviare */
function validaForm() {
    var password  = document.getElementById("password").value;
    var password2 = document.getElementById("password2").value;

    if (password.length < 6) {
        alert("La password deve essere di almeno 6 caratteri.");
        return false;
    }

    if (password !== password2) {
        alert("Le due password non coincidono.");
        return false;
    }

    return true;
}
</script>

</body>
</html>