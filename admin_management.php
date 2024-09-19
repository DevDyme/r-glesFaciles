<?php
session_start();
require 'config.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = '';
$username = '';
$password = '';

if (isset($_GET['edit']) && isset($_GET['type'])) {
    $id = $_GET['edit'];
    if ($_GET['type'] == 'utilisateur') {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        $username = $user['username'];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch();
        $username = $admin['username'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (isset($_POST['type']) && $_POST['type'] == 'utilisateur') {
        // Gestion des utilisateurs
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            if ($password) {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET username = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET username = ? WHERE id = ?");
                $stmt->execute([$username, $id]);
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
        }
    } else {
        // Gestion des administrateurs
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            if ($password) {
                $stmt = $pdo->prepare("UPDATE administrateurs SET username = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE administrateurs SET username = ? WHERE id = ?");
                $stmt->execute([$username, $id]);
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO administrateurs (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
        }
    }

    header("Location: admin_management.php");
    exit();
}

if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = $_GET['delete'];
    if ($_GET['type'] == 'utilisateur') {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: admin_management.php");
    exit();
}

// Récupération des administrateurs et des utilisateurs
$admins = $pdo->query("SELECT * FROM administrateurs")->fetchAll();
$utilisateurs = $pdo->query("SELECT * FROM utilisateurs")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs et administrateurs</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .liste23 {
                margin-top: 20px;
                padding-bottom: 0;
            }

            .card-custom2 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<?php include('navBar.php') ?>

<div class="container">
    <header class="my-4 text-center">
        <h1>Gestion des Administrateurs et Utilisateurs</h1>
        <nav class="d-flex justify-content-center mb-4">
            <a href="admin.php" class="btn btn-secondary me-2">Retour à l'admin</a>
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </nav>
    </header>

    <div class="row">
        <!-- Colonne Administrateurs -->
        <div class="col-lg-6">
            <!-- Formulaire pour les administrateurs -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Ajouter ou modifier un administrateur</h2>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="type" value="admin">
                        <input type="hidden" name="id"
                               value="<?= isset($_GET['edit']) && $_GET['type'] == 'admin' ? htmlspecialchars($id) : ''; ?>">
                        <div class="mb-3">
                            <label for="usernameAdmin" class="form-label">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" name="username" id="usernameAdmin"
                                   value="<?= isset($_GET['edit']) && $_GET['type'] == 'admin' ? htmlspecialchars($username) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordAdmin" class="form-label">Mot de passe :</label>
                            <input type="password" class="form-control" name="password" id="passwordAdmin">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Liste des administrateurs -->
            <div class="card">
                <div class="card-header">
                    <h2>Liste des administrateurs</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($admins as $admin) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($admin['username']); ?>
                                <div>
                                    <a href="?edit=<?php echo $admin['id']; ?>&type=admin"
                                       class="btn btn-warning btn-sm">Modifier</a>
                                    <a href="?delete=<?php echo $admin['id']; ?>&type=admin"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Colonne Utilisateurs -->
        <div class="col-lg-6">
            <!-- Formulaire pour les utilisateurs -->
            <div class="card mb-4 liste23">
                <div class="card-header">
                    <h2>Ajouter ou modifier un <br>utilisateur</br></h2>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="type" value="utilisateur">
                        <input type="hidden" name="id"
                               value="<?= isset($_GET['edit']) && $_GET['type'] == 'utilisateur' ? htmlspecialchars($id) : ''; ?>">
                        <div class="mb-3">
                            <label for="usernameUser" class="form-label">Nom d'utilisateur :</label>
                            <input type="text" class="form-control" name="username" id="usernameUser"
                                   value="<?= isset($_GET['edit']) && $_GET['type'] == 'utilisateur' ? htmlspecialchars($username) : ''; ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordUser" class="form-label">Mot de passe :</label>
                            <input type="password" class="form-control" name="password" id="passwordUser">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Liste des utilisateurs -->
            <div class="card card-custom2">
                <div class="card-header">
                    <h2>Liste des utilisateurs</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($utilisateurs as $utilisateur) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($utilisateur['username']); ?>
                                <div>
                                    <a href="?edit=<?php echo $utilisateur['id']; ?>&type=utilisateur"
                                       class="btn btn-warning btn-sm">Modifier</a>
                                    <a href="?delete=<?php echo $utilisateur['id']; ?>&type=utilisateur"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>

</body>
</html>
