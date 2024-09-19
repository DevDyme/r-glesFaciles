<?php
// Connexion à la base de données
$db = new PDO('mysql:host=localhost;dbname=gestion_jeux', 'root', '');

// Requête pour récupérer les règles
$query = $db->query('SELECT nom_jeu, description FROM regles_jeux');

// Récupération des résultats
$regles = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règles des jeux</title>
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
    <style>
        .btn-primary-custom {
            background-color: #e74c3c;
            color: white;
            border-color: #2c3e50;
        }

        .btn-primary-custom:hover, .btn-primary-custom:active, .btn-primary-custom:focus {
            background-color: white;
            color: #e74c3c;
            border-color: #2c3e50;
        }

        .card {
            margin-bottom: 20px;
            max-width: 100%;
        }

        @media (max-width: 576px) {
            .modal-lg {
                max-width: 80%; /* Rendre la modale plus large */
                margin-left: 10%;
            }
        }

        .modal-body p {
            font-size: 1.1rem;
            line-height: 1.5;
            white-space: pre-wrap; /* Conserver les sauts de ligne */
        }

        .btn-close-custom {
            background-color: transparent;
            border: none;
            color: #2c3e50;
            font-size: 1rem;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }

        .btn-close-custom:hover {
            color: #e74c3c; /* Couleur rouge au survol */
        }
    </style>
</head>
<body>

<!-- Menu de navigation -->
<?php include("navBar.php"); ?>

<div class="container mt-5">
    <h2>Liste des règles de jeux</h2>

    <div class="row">
        <?php foreach ($regles as $regle): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($regle['nom_jeu']) ?></h5>

                        <p class="card-text">
                            <!-- Aperçu limité à 40 caractères -->
                            <span class="description-short"><?= htmlspecialchars(substr($regle['description'], 0, 40)) ?>...</span>
                        </p>

                        <!-- Bouton pour ouvrir la modale avec la description complète -->
                        <button class="btn btn-primary-custom voir-plus" data-bs-toggle="modal"
                                data-bs-target="#modalDescription"
                                data-nom-jeu="<?= htmlspecialchars($regle['nom_jeu']) ?>"
                                data-description="<?= htmlspecialchars($regle['description']) ?>">
                            Voir plus
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modale Bootstrap pour afficher la description complète -->
<div class="modal fade" id="modalDescription" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Bouton "FERMER" en haut à droite -->
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal">FERMER</button>
                <!-- Titre de la modale -->
                <h5 class="modal-title mx-auto" id="modalNomJeu"></h5>
            </div>
            <div class="modal-body">
                <p id="modalDescriptionContent"></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Lorsqu'on clique sur "Voir plus", on charge les données dans la modale
    document.querySelectorAll('.voir-plus').forEach(button => {
        button.addEventListener('click', function () {
            const nomJeu = this.getAttribute('data-nom-jeu');
            const description = this.getAttribute('data-description');

            // Mettre à jour les éléments de la modale avec les informations du jeu
            document.getElementById('modalNomJeu').textContent = nomJeu;
            document.getElementById('modalDescriptionContent').textContent = description;
        });
    });
</script>

<!-- Footer -->
<?php include('footer.php'); ?>


</body>
</html>

