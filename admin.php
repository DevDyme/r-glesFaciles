<?php
session_start();
require 'config.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Traitement pour ajouter ou modifier une règle de jeu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_jeu = $_POST['nom_jeu'];
    $description = $_POST['description'];

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE regles_jeux SET nom_jeu = ?, description = ? WHERE id = ?");
        $stmt->execute([$nom_jeu, $description, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO regles_jeux (nom_jeu, description) VALUES (?, ?)");
        $stmt->execute([$nom_jeu, $description]);
    }

    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM regles_jeux WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit();
}

$editMode = false;
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM regles_jeux WHERE id = ?");
    $stmt->execute([$id]);
    $regle = $stmt->fetch(PDO::FETCH_ASSOC);
}

$regles = $pdo->query("SELECT * FROM regles_jeux ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);

function truncateWords($text, $limit)
{
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    return $text;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="title" content="Règles Facile - Admin">
    <meta name="description"
          content="Découvrez des règles, gérez vos jeux et plongez dans l'univers passionnant des jeux de société.">
    <meta name="keywords" content="jeux, jeux de sociétés, règles, règles facile, julien dyme, Julien Dyme,Dyme">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="7 days">
    <meta name="author" content="Julien Dyme">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règles Facile - Admin</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
    <style>
        /* Style général pour que le contenu occupe tout l'écran et que le footer reste en bas */
        body, html {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Fond sombre pour le titre et les descriptions */

        .btn-primary2 {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }

        .btn-primary2:hover, .btn-primary2:active, .btn-primary2:focus {
            background-color: white;
            border-color: #2c3e50;
        }

        .container {
            flex: 1;
        }

        .description-short {
            display: inline-block;
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .description-complete {
            display: none;
        }

        /* Style commun pour les boutons Voir plus et Voir moins */
        .btn-description-toggle {
            background-color: #343a40; /* Couleur de fond sombre */
            color: #fff; /* Couleur du texte en blanc pour lisibilité */
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.9rem;
            border-radius: 3px;
        }

        .btn-description-toggle:hover {
            background-color: #5a6268; /* Fond légèrement plus foncé au survol */
        }

        /* Responsive design pour centrer les formulaires sur petits écrans */
        @media (max-width: 576px) {
            h2 {
                text-align: center;
            }

            form {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td {
                white-space: normal;
            }

            .table td, .table th {
                display: block;
                text-align: center;
                padding: 10px;
            }

            .table th {
                font-weight: bold;
            }

            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>

<?php include("navBar.php"); ?>

<div class="container mt-5">
    <h2 class="text-center">Gestion des règles de jeux</h2>

    <div class="d-flex justify-content-center">
        <a href="index.php" class="btn btn-primary2 m-1">Retour à l'accueil</a>
        <a href="admin_management.php" class="btn btn-secondary m-1">Gestion Admin</a>
        <a href="logout.php" class="btn btn-danger m-1">Déconnexion</a>
    </div>

    <!-- Formulaire pour ajouter ou modifier une règle de jeu -->
    <form method="POST" action="admin.php" class="mt-4">
        <?php if (isset($_GET['edit'])): ?>
            <input type="hidden" name="id" value="<?= $regle['id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="nom_jeu" class="form-label">Nom du jeu</label>
            <input type="text" name="nom_jeu" id="nom_jeu" class="form-control"
                   value="<?= isset($regle) ? htmlspecialchars($regle['nom_jeu']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"
                      required><?= isset($regle) ? htmlspecialchars($regle['description']) : ''; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter'; ?></button>
        <?php if (isset($_GET['edit'])): ?>
            <a href="admin.php" class="btn btn-secondary">Annuler</a>
        <?php endif; ?>
    </form>

    <hr>

    <h3 class="mt-4">Règles existantes</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nom du jeu</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <?php foreach ($regles as $regle): ?>
                <tr>
                    <td><?= htmlspecialchars($regle['nom_jeu']) ?></td>
                    <td>
                        <span class="description-short"><?= htmlspecialchars(truncateWords($regle['description'], 15)) ?></span>
                        <span class="description-complete"><?= nl2br(htmlspecialchars($regle['description'])) ?></span>
                        <br>
                        <button class="btn-description-toggle mt-1 voir-plus">Voir plus</button>
                        <button class="btn-description-toggle mt-1 voir-moins" style="display:none;">Voir moins</button>
                    </td>
                    <td>
                        <a href="admin.php?edit=<?= $regle['id'] ?>" class="btn m-1 btn-warning">Modifier</a>
                        <a href="admin.php?delete=<?= $regle['id'] ?>" class="btn m-1  btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette règle ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.voir-plus').forEach(button => {
        button.addEventListener('click', function () {
            const parent = this.closest('td');
            parent.querySelector('.description-short').style.display = 'none';
            parent.querySelector('.description-complete').style.display = 'inline';
            this.style.display = 'none';
            parent.querySelector('.voir-moins').style.display = 'inline';
        });
    });

    document.querySelectorAll('.voir-moins').forEach(button => {
        button.addEventListener('click', function () {
            const parent = this.closest('td');
            parent.querySelector('.description-short').style.display = 'inline';
            parent.querySelector('.description-complete').style.display = 'none';
            this.style.display = 'none';
            parent.querySelector('.voir-plus').style.display = 'inline';
        });
    });
</script>


<!-- Footer -->
<?php include('footer.php') ?>
</body>
</html>
