<?php
session_start();
require 'config.php';
// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Modification d'un administrateur
        $id = $_POST['id'];

        // Si le mot de passe est vide, on ne le modifie pas
        if ($password) {
            $stmt = $pdo->prepare("UPDATE administrateurs SET username = ?, password = ? WHERE id = ?");
            $stmt->execute([$username, $password, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE administrateurs SET username = ? WHERE id = ?");
            $stmt->execute([$username, $id]);
        }

    } else {
        // Ajout d'un nouvel administrateur
        $stmt = $pdo->prepare("INSERT INTO administrateurs (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
    }

    header("Location: admin_management.php");
    exit();
}

// Suppression d'un administrateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin_management.php");
    exit();
}

// Récupération des administrateurs
$administrateurs = $pdo->query("SELECT * FROM administrateurs ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des administrateurs</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
</head>
<body>
<?php include('navBar.php'); ?>
<div class="container mt-5">
    <h2>Gestion des administrateurs</h2>

    <!-- Formulaire d'ajout/modification -->
    <form method="POST" action="admin_management.php" class="mb-4">
        <input type="hidden" name="id" id="adminId">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="admin.php" class="btn btn-secondary">Retour à l'administration</a>
    </form>

    <!-- Liste des administrateurs -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($administrateurs as $admin): ?>
            <tr>
                <td><?= htmlspecialchars($admin['id']); ?></td>
                <td><?= htmlspecialchars($admin['username']); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editAdmin('<?= $admin['id']; ?>', '<?= htmlspecialchars($admin['username']); ?>')">Modifier</button>
                    <a href="admin_management.php?delete=<?= $admin['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Remplissage du formulaire pour modifier un administrateur
    function editAdmin(id, username) {
        document.getElementById('adminId').value = id;
        document.getElementById('username').value = username;
        document.getElementById('password').value = '';  // Le mot de passe reste vide pour ne pas le modifier
    }
</script>

<?php include("footer.php");
?>
</body>
</html>
