<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Peut être 'admin' ou 'utilisateur'

    if ($role == 'admin') {
        // Authentification pour les administrateurs
        $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['id']; // Stocke l'ID de l'administrateur dans la session
            header("Location: admin.php");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect pour l'administrateur";
        }
    } else {
        // Authentification pour les utilisateurs
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['id']; // Stocke l'ID de l'utilisateur dans la session
            header("Location: user_view.php"); // Redirection vers la page de consultation des règles de jeu
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect pour l'utilisateur";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
</head>
<body>

<?php include('navBar.php') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h2>Connexion</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe :</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle :</label>
                            <select class="form-select" name="role" id="role">
                                <option value="admin">Administrateur</option>
                                <option value="utilisateur">Utilisateur</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>