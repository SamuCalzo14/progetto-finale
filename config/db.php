<?php
/* ================================
   CONNESSIONE AL DATABASE
   ================================ */

$host     = "localhost";
$utente_db = "root";         /* cambia con il tuo utente MySQL */
$password_db = "";           /* cambia con la tua password MySQL */
$database = "studio_rc";     /* cambia con il nome del tuo database */

$conn = mysqli_connect($host, $utente_db, $password_db, $database);

if (!$conn) {
    die("Errore connessione: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>