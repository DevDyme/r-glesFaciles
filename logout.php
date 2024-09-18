<?php
session_start();

// Détruire la session
session_unset();  // Libère toutes les variables de session
session_destroy();  // Détruit la session

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>
