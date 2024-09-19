<?php
// Informations de connexion à la base de données
$host = "localhost";      // Nom de l'hôte
$dbname = "gestion_jeux"; // Nom de la base de données
$username = "admin";   // Nom d'utilisateur de la base de données
$password = "admin";   // Mot de passe de la base de données

try {
    // Crée une nouvelle instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Définit le mode d'erreur PDO pour afficher les exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, affiche un message d'erreur et arrête l'exécution
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour vérifier la connexion à la base de données (facultatif)
function checkDatabaseConnection() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT 1");
        if ($stmt) {
            return true;
        }
    } catch (PDOException $e) {
        return false;
    }
    return true;
}

// Exemple d'utilisation pour tester la connexion
if (checkDatabaseConnection()) {
    echo "Connexion à la base de données réussie!";
} else {
    echo "Échec de connexion à la base de données.";
}

?>
