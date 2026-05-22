<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/eleves') ?>">Élèves</a></li><li class="breadcrumb-item active"><?= e($eleve['prenoms'].' '.$eleve['nom']) ?></li></ol>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <?php if (!empty($eleve['photo'])): ?>
        <img src="<?= url($eleve['photo']) ?>" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
        <?php else: ?>
        <div class="avatar-initials" style="width:56px;height:56px;font-size:1.2rem;"><?= strtoupper(substr($eleve['prenoms'],0,1).substr($eleve['nom'],0,1)) ?></div>
        <?php endif; ?>
        <div>
            <h1 class="mb-0"><?= e($eleve['prenoms'].' '.$eleve['nom']) ?></h1>
            <span class="badge bg-light text-dark border font-monospace"><?= e($eleve['matricule']) ?></span>
        </div>
    </div>
    <?php if (can('eleves.modifier')): ?>
    <a href="<?= url('/eleves/'.$eleve['id'].'/modifier') ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Modifier</a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Infos personnelles -->
    <div class="col-lg-4">
        <div class="form-card mb-4">
            <div class="form-section-title">Informations personnelles</div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Sexe</dt><dd class="col-7"><?= $eleve['sexe'] === 'M' ? 'Masculin' : 'Féminin' ?></dd>
                <dt class="col-5 text-muted">Né(e) le</dt><dd class="col-7"><?= $eleve['date_naissance'] ? dateFormat($eleve['date_naissance']) : '—' ?></dd>
                <dt class="col-5 text-muted">Lieu</dt><dd class="col-7"><?= e($eleve['lieu_naissance'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Nationalité</dt><dd class="col-7"><?= e($eleve['nationalite'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Téléphone</dt><dd class="col-7"><?= e($eleve['telephone'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Email</dt><dd class="col-7"><?= e($eleve['email'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Adresse</dt><dd class="col-7"><?= e($eleve['adresse'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Groupe sang.</dt><dd class="col-7"><?= e($eleve['groupe_sanguin'] ?? '—') ?></dd>
            </dl>
        </div>
        <div class="form-card">
            <div class="form-section-title">Parents / Tuteurs</div>
            <dl class="row mb-0 small">
                <dt class="col-12 text-muted fw-semibold mb-1">Parent 1</dt>
                <dt class="col-5 text-muted">Nom</dt><dd class="col-7"><?= e($eleve['parent1_nom'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Tél</dt><dd class="col-7"><?= e($eleve['parent1_telephone'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Profession</dt><dd class="col-7"><?= e($eleve['parent1_profession'] ?? '—') ?></dd>
                <?php if (!empty($eleve['parent2_nom'])): ?>
                <dt class="col-12 text-muted fw-semibold mt-2 mb-1">Parent 2</dt>
                <dt class="col-5 text-muted">Nom</dt><dd class="col-7"><?= e($eleve['parent2_nom']) ?></dd>
                <dt class="col-5 text-muted">Tél</dt><dd class="col-7"><?= e($eleve['parent2_telephone'] ?? '—') ?></dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Inscriptions -->
        <div class="table-card mb-4">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-check me-2 text-primary"></i>Historique des inscriptions</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Année</th><th>Classe</th><th>Statut</th></tr></thead>
                <tbody>
                <?php if (empty($inscriptions)): ?>
                <tr><td colspan="3" class="text-center py-3 text-muted">Aucune inscription</td></tr>
                <?php else: foreach ($inscriptions as $i): ?>
                <tr>
                    <td><?= e($i['annee']) ?></td>
                    <td class="fw-medium"><?= e($i['classe']) ?></td>
                    <td><?= statutBadge($i['statut'], ['inscrit'=>'success','transfere'=>'warning','exclu'=>'danger','diplome'=>'primary']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Notes récentes -->
        <div class="table-card mb-4">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2 text-warning"></i>Notes récentes</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Évaluation</th><th>Matière</th><th>Date</th><th>Note</th></tr></thead>
                <tbody>
                <?php if (empty($notes)): ?>
                <tr><td colspan="4" class="text-center py-3 text-muted">Aucune note</td></tr>
                <?php else: foreach ($notes as $n): ?>
                <tr>
                    <td><?= e($n['evaluation']) ?></td>
                    <td><?= e($n['matiere']) ?></td>
                    <td class="text-muted"><?= dateFormat($n['date_evaluation']) ?></td>
                    <td><strong><?= $n['note'] !== null ? number_format((float)$n['note'],2) : 'Abs' ?></strong></td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paiements -->
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2 text-success"></i>Paiements récents</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>N° Reçu</th><th>Montant</th><th>Date</th><th>Mode</th></tr></thead>
                <tbody>
                <?php if (empty($paiements)): ?>
                <tr><td colspan="4" class="text-center py-3 text-muted">Aucun paiement</td></tr>
                <?php else: foreach ($paiements as $p): ?>
                <tr>
                    <td class="font-monospace"><?= e($p['numero_recu'] ?? '—') ?></td>
                    <td class="fw-semibold"><?= $p['total_paye'] ? money($p['total_paye']) : '—' ?></td>
                    <td><?= $p['date_paiement'] ? dateFormat($p['date_paiement']) : '—' ?></td>
                    <td><?= e($p['mode_paiement'] ?? '—') ?></td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
