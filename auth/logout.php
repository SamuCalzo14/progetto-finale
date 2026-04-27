<?php
/* Distrugge la sessione e rimanda al login */
session_start();
session_destroy();
header("Location: ../auth/login.php");
exit;
?>