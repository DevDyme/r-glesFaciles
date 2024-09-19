<style>

    /* #2c3e50 | #dee2e6 | #7b8a8b */

    .navbar {
        background-color: #2c3e50;
    }

    .navbar-nav .nav-link {
        color: white !important;
    }

    .navbar-nav .nav-link:hover {
        color: #dee2e6 !important;
    }

    .principal-section {
        height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-size: cover;
        background-position: center;
        color: #2c3e50;
        text-align: center;
    }

    .principal-section h1 {
        font-size: 4rem;
    }

    .principal-section p {
        font-size: 1.5rem;
    }

    #linkNavTilte {
        color: #dee2e6;

    }

</style>

<!-- Menu de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" id="linkNavTilte" href="index.php">Règles Facile</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <!-- <li class="nav-item">
                             <a class="nav-link" href="rules.php">Règles des jeux</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="about.php">À propos</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="contact.php">Contact</a>
                 </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Connexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>






