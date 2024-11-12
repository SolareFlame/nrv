<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>NRV - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="src/css/style.css">
    <link rel="icon" href="res/logo/logo_3.png">
</head>

<body>

<div class="header-background">
    <header class="header d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img src="res/logo/logo_2.png" alt="NRV" width="180"/>
            </a>
        </div>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 gap-4">
            <li><a href="index.php" class="nav-link px-2 text-white text-uppercase fs-5">Accueil</a></li>
            <li><a href="index.php?action=shows" class="nav-link px-2 text-white text-uppercase fs-5">Spectacles</a>
            </li>
            <li><a href="index.php?action=favs" class="nav-link px-2 text-white text-uppercase fs-5">Préférences</a></li>
            <li><a href="index.php?action=" class="nav-link px-2 text-white text-uppercase fs-5">A propos</a></li>
        </ul>

        <div class="col-md-3 text-end">
            <?php if (!empty($_SESSION['pwd'])): ?>
                <button type="button" class="btn btn-outline-warning me-2">
                    Se déconnecter
                </button>
            <?php endif; ?>
        </div>
    </header>

    <div class="d-flex justify-content-between position-absolute bottom-0 start-0 end-0 p-3">
        <span class="revivre-text text-white">REVIVEZ LE FESTIVAL #NRV2024</span>
        <div class="social-icons">
            <a href="#" target="_blank" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="bi bi-tiktok"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="bi bi-youtube"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="bi bi-twitter"></i></a>
        </div>
    </div>
</div>
<div class="header-gradient"></div>

<main>
    <div class="content">
        {{CONTENT}}
    </div>
</main>

<footer class="footer">
    <div class="container py-4">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-4 text-md-start text-center mb-3 mb-md-0">
                <p class="footer-text">&copy; 2024 - NRV</p>
            </div>

            <div class="col-md-4 d-flex justify-content-center gap-4">
                <a href="index.php?action=login" class="footer-link">ESPACE ORGANISATEUR</a>
                <a href="index.php?action=contact" class="footer-link">CONTACT</a>
                <a href="https://github.com/SolareFlame/nrv" class="footer-link">PROJET</a>
            </div>
        </div>
    </div>
</footer>


</body>
</html>
