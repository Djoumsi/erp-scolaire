<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= APP_NAME ?></title>
    <!-- CSS local (Bootstrap + icons + app) -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/bootstrap.min.css?v=<?= filemtime(BASE_PATH.'/public/assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/bootstrap-icons.min.css?v=<?= filemtime(BASE_PATH.'/public/assets/css/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/app.css?v=<?= filemtime(BASE_PATH.'/public/assets/css/app.css') ?>">
    <?php if (isset($extraCss)) echo $extraCss; ?>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <?php $etab = \App\Core\Session::get('user')['etablissement_id'] ?? null; ?>
        <a href="<?= url('/dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill"></i>
            <span><?= APP_NAME ?></span>
        </a>
    </div>

    <div class="sidebar-menu">
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard') ? 'active' : '' ?>"
                   href="<?= url('/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <?php if (can('eleves.voir')): ?>
            <!-- Élèves -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/eleves') ? 'active' : '' ?>"
                   href="<?= url('/eleves') ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Élèves</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('personnel.voir')): ?>
            <!-- Personnel -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/personnel') ? 'active' : '' ?>"
                   href="<?= url('/personnel') ?>">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Personnel</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('classes.voir')): ?>
            <!-- Classes -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/classes') ? 'active' : '' ?>"
                   href="<?= url('/classes') ?>">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <span>Classes</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('emploi_temps.voir')): ?>
            <!-- Emploi du temps -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/emploi-du-temps') ? 'active' : '' ?>"
                   href="<?= url('/emploi-du-temps') ?>">
                    <i class="bi bi-calendar3-week-fill"></i>
                    <span>Emploi du temps</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('presences.voir')): ?>
            <!-- Présences -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/presences') ? 'active' : '' ?>"
                   href="<?= url('/presences') ?>">
                    <i class="bi bi-clipboard2-check-fill"></i>
                    <span>Présences</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('notes.voir')): ?>
            <!-- Notes -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/notes') ? 'active' : '' ?>"
                   href="<?= url('/notes') ?>">
                    <i class="bi bi-pencil-square"></i>
                    <span>Notes</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('bulletins.voir')): ?>
            <!-- Bulletins -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/bulletins') ? 'active' : '' ?>"
                   href="<?= url('/bulletins') ?>">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Bulletins</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('examens.voir')): ?>
            <!-- Examens -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/examens') ? 'active' : '' ?>"
                   href="<?= url('/examens') ?>">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Examens</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('paiements.voir')): ?>
            <!-- Paiements -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/paiements') ? 'active' : '' ?>"
                   href="<?= url('/paiements') ?>">
                    <i class="bi bi-cash-coin"></i>
                    <span>Paiements</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('comptabilite.voir')): ?>
            <!-- Comptabilité -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/comptabilite') ? 'active' : '' ?>"
                   href="<?= url('/comptabilite') ?>">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Comptabilité</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('bibliotheque.voir')): ?>
            <!-- Bibliothèque -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/bibliotheque') ? 'active' : '' ?>"
                   href="<?= url('/bibliotheque') ?>">
                    <i class="bi bi-book-half"></i>
                    <span>Bibliothèque</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('communication.voir')): ?>
            <!-- Communication -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/annonces') ? 'active' : '' ?>"
                   href="<?= url('/annonces') ?>">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>Annonces</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Messages -->
            <li class="nav-item">
                <?php
                $msgStmt = \App\Core\Database::getInstance()->prepare("SELECT COUNT(*) FROM messages WHERE destinataire_id=? AND lu=0");
                $msgStmt->execute([\App\Core\Auth::id()]);
                $msgNonLus = (int) $msgStmt->fetchColumn();
                ?>
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/messages') ? 'active' : '' ?>"
                   href="<?= url('/messages') ?>">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Messages<?php if ($msgNonLus > 0): ?> <span class="badge bg-danger rounded-pill ms-1"><?= $msgNonLus ?></span><?php endif; ?></span>
                </a>
            </li>

            <?php if (can('rapports.voir')): ?>
            <!-- Rapports -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/rapports') ? 'active' : '' ?>"
                   href="<?= url('/rapports') ?>">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Rapports</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-divider"></li>

            <?php if (\App\Core\Auth::isSuperAdmin()): ?>
            <!-- Établissements (super admin) -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/etablissements') ? 'active' : '' ?>"
                   href="<?= url('/etablissements') ?>">
                    <i class="bi bi-building-fill"></i>
                    <span>Établissements</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (can('parametres.voir')): ?>
            <!-- Paramètres -->
            <li class="nav-item">
                <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/parametres') ? 'active' : '' ?>"
                   href="<?= url('/parametres') ?>">
                    <i class="bi bi-gear-fill"></i>
                    <span>Paramètres</span>
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>

<!-- Main -->
<div class="main-content">

    <!-- Topbar -->
    <header class="topbar">
        <button class="btn btn-link sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <?php
        $user = \App\Core\Auth::user();
        $_nStmt = \App\Core\Database::getInstance()->prepare("SELECT COUNT(*) FROM notifications WHERE user_id=? AND lu=0");
        $_nStmt->execute([$user['id'] ?? 0]);
        $notifsCount = (int) $_nStmt->fetchColumn();
        ?>

        <div class="topbar-right ms-auto d-flex align-items-center gap-3">
            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <span class="notif-badge" id="notifBadge" <?= $notifsCount > 0 ? '' : 'style="display:none"' ?>><?= $notifsCount ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown" id="notifDropdown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <div id="notifList"><div class="p-3 text-muted text-center">Aucune notification</div></div>
                    <div class="dropdown-divider"></div>
                    <a href="<?= url('/notifications') ?>" class="dropdown-item text-center small">Voir tout</a>
                </div>
            </div>

            <!-- Profil -->
            <div class="dropdown">
                <button class="btn btn-link d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <?php if (!empty($user['photo'])): ?>
                        <img src="<?= url($user['photo']) ?>" class="avatar-sm" alt="">
                    <?php else: ?>
                        <div class="avatar-initials"><?= strtoupper(substr($user['nom'], 0, 1) . substr($user['prenoms'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <span class="d-none d-md-inline fw-medium"><?= e($user['prenoms'] . ' ' . $user['nom']) ?></span>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header"><?= e($user['role_nom'] ?? '') ?></h6></li>
                    <li><a class="dropdown-item" href="<?= url('/profil') ?>"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page content -->
    <main class="page-content">

        <!-- Flash messages -->
        <?php if (\App\Core\Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= e(\App\Core\Session::flash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e(\App\Core\Session::flash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::hasFlash('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i><?= e(\App\Core\Session::flash('warning')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php
        // Nettoyer les erreurs de validation après affichage
        \App\Core\Session::delete('validation_errors');
        \App\Core\Session::delete('old_input');
        ?>

        <?= $content ?>
    </main>
</div>

<script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js?v=<?= filemtime(BASE_PATH.'/public/assets/js/bootstrap.bundle.min.js') ?>"></script>
<script>const ERP_BASE = '<?= APP_URL ?>';</script>
<script src="<?= asset('js/app.js') ?>"></script>
<?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>
