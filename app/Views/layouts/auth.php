<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/app.css">
</head>
<body class="auth-body">

    <div class="auth-wrapper">

        <!-- Hero (gauche) -->
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="auth-flag-bar">
                    <span></span><span></span><span></span>
                </div>

                <h2>Bienvenue sur<br><?= APP_NAME ?> 🇨🇲</h2>
                <p>La plateforme de gestion scolaire pensée pour les établissements camerounais — du Primaire à l'Université.</p>

                <ul class="auth-features">
                    <li><i class="bi bi-check2"></i> Gestion complète des élèves &amp; classes</li>
                    <li><i class="bi bi-check2"></i> Notes, bulletins et examens</li>
                    <li><i class="bi bi-check2"></i> Paiements &amp; comptabilité en FCFA</li>
                    <li><i class="bi bi-check2"></i> Communication parents &amp; personnel</li>
                </ul>
            </div>

            <div class="auth-hero-footer">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Plateforme sécurisée &middot; Données hébergées en Europe</span>
            </div>
        </div>

        <!-- Formulaire (droite) -->
        <div class="auth-card">
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h1><?= APP_NAME ?></h1>
                <p>Connectez-vous à votre espace</p>
            </div>

            <?= $content ?>

            <div class="text-center mt-4 pt-3 border-top">
                <small class="text-muted">
                    &copy; <?= date('Y') ?> <?= APP_NAME ?> &middot; Made in Cameroon 🇨🇲
                </small>
            </div>
        </div>

    </div>

    <script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
