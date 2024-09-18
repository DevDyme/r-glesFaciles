<?php
session_start();
require 'config.php';  // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];  // Récupère le nom d'utilisateur
    $password = $_POST['password'];  // Récupère le mot de passe

    // Prépare la requête SQL pour récupérer l'administrateur
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si l'administrateur existe
    if ($admin) {
        // Vérification du mot de passe avec password_verify
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $username;  // Démarre la session
            header("Location: admin.php");  // Redirige vers la page d'administration
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Nom d'utilisateur incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="title" content="Règles Facile - Login">
    <meta name="description" content="Découvrez des règles, gérez vos jeux et plongez dans l'univers passionnant des jeux de société.">
    <meta name="keywords" content="jeux, jeux de sociétés, règles, règles facile, julien dyme, Julien Dyme,Dyme">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="7 days">
    <meta name="author" content="Julien Dyme">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règles Facile - Login</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
</head>
<body>
<!-- Menu de navigation -->
<?php include("navBar.php"); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2>Connexion Admin</h2>

            <!-- Affichage du message d'erreur si la connexion échoue -->
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Connexion</button>

                <a href="index.php" class="btn btn-primary ">Retour à l'accueil</a> <!-- Lien vers la page d'accueil -->
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('footer.php') ?>

</body>
</html>
