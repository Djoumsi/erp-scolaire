<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Rapports</li></ol>

<div class="page-header">
    <h1><i class="bi bi-graph-up-arrow text-primary me-2"></i>Rapports & Statistiques</h1>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= $annee ? e($annee['libelle']) : 'Aucune année en cours' ?></span>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours. <a href="<?= url('/parametres') ?>">Configurer</a>.</div>
<?php else: ?>

<div class="row g-3">
    <!-- Élèves -->
    <div class="col-md-6 col-lg-3">
        <div class="form-card text-center py-4 h-100 d-flex flex-column">
            <div class="stat-icon blue mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-people-fill fs-3"></i></div>
            <h6 class="fw-semibold">Rapport Élèves</h6>
            <p class="text-muted small flex-grow-1">Effectifs, répartition par sexe, pyramide des classes</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= url('/rapports/eleves') ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye me-1"></i>Consulter
                </a>
                <a href="<?= url('/rapports/eleves/export') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="col-md-6 col-lg-3">
        <div class="form-card text-center py-4 h-100 d-flex flex-column">
            <div class="stat-icon yellow mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-pencil-square fs-3"></i></div>
            <h6 class="fw-semibold">Rapport Notes</h6>
            <p class="text-muted small flex-grow-1">Moyennes, résultats par matière et par classe</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= url('/rapports/notes') ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-eye me-1"></i>Consulter
                </a>
                <a href="<?= url('/rapports/notes/export') ?>" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Paiements -->
    <div class="col-md-6 col-lg-3">
        <div class="form-card text-center py-4 h-100 d-flex flex-column">
            <div class="stat-icon green mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-cash-coin fs-3"></i></div>
            <h6 class="fw-semibold">Rapport Paiements</h6>
            <p class="text-muted small flex-grow-1">Recettes, taux de recouvrement, retards de paiement</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= url('/rapports/paiements') ?>" class="btn btn-sm btn-success">
                    <i class="bi bi-eye me-1"></i>Consulter
                </a>
                <a href="<?= url('/rapports/paiements/export') ?>" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Présences -->
    <div class="col-md-6 col-lg-3">
        <div class="form-card text-center py-4 h-100 d-flex flex-column">
            <div class="stat-icon orange mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-clipboard2-check-fill fs-3"></i></div>
            <h6 class="fw-semibold">Rapport Présences</h6>
            <p class="text-muted small flex-grow-1">Taux d'absentéisme, absences par élève et par classe</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?= url('/rapports/presences') ?>" class="btn btn-sm btn-secondary">
                    <i class="bi bi-eye me-1"></i>Consulter
                </a>
                <a href="<?= url('/rapports/presences/export') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
