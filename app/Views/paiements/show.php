<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/paiements') ?>">Paiements</a></li><li class="breadcrumb-item active">Dossier</li></ol>

<div class="page-header">
    <h1><i class="bi bi-folder2-open text-primary me-2"></i>Dossier paiement</h1>
    <?php if (can('paiements.encaisser') && $dossier['statut'] !== 'solde'): ?>
    <a href="<?= url('/paiements/'.$dossier['inscription_id'].'/encaisser') ?>" class="btn btn-success">
        <i class="bi bi-cash-coin me-1"></i>Encaisser
    </a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="form-card">
            <div class="form-section-title">Élève</div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Nom</dt><dd class="col-7 fw-semibold"><?= e($dossier['prenoms'].' '.$dossier['nom']) ?></dd>
                <dt class="col-5 text-muted">Matricule</dt><dd class="col-7 font-monospace"><?= e($dossier['matricule']) ?></dd>
                <dt class="col-5 text-muted">Classe</dt><dd class="col-7"><?= e($dossier['classe']) ?></dd>
                <dt class="col-5 text-muted">Année</dt><dd class="col-7"><?= e($dossier['annee']) ?></dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7"><?= statutBadge($dossier['statut'], ['solde'=>'success','partiel'=>'warning','non_paye'=>'danger']) ?></dd>
                <dt class="col-5 text-muted">Total dû</dt><dd class="col-7 fw-bold"><?= money($dossier['montant_total']) ?></dd>
                <dt class="col-5 text-muted">Payé</dt><dd class="col-7 fw-bold text-success"><?= money($dossier['montant_paye']) ?></dd>
                <dt class="col-5 text-muted">Reste</dt><dd class="col-7 fw-bold text-danger"><?= money(max(0,$dossier['montant_total']-$dossier['montant_paye'])) ?></dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-receipt me-2 text-success"></i>Paiements</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>N° Reçu</th><th>Montant</th><th>Mode</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (empty($paiements)): ?>
                <tr><td colspan="5" class="text-center py-3 text-muted">Aucun paiement</td></tr>
                <?php else: foreach ($paiements as $p): ?>
                <tr class="<?= $p['annule'] ? 'text-decoration-line-through opacity-50' : '' ?>">
                    <td class="font-monospace"><?= e($p['numero_recu']) ?></td>
                    <td class="fw-semibold"><?= money($p['montant']) ?></td>
                    <td><?= e($p['mode_paiement']) ?></td>
                    <td><?= dateFormat($p['date_paiement']) ?></td>
                    <td class="d-flex gap-1">
                        <a href="<?= url('/paiements/'.$p['id'].'/recu') ?>" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i></a>
                        <?php if (!$p['annule'] && can('paiements.annuler')): ?>
                        <form method="POST" action="<?= url('/paiements/'.$p['id'].'/annuler') ?>" onsubmit="return confirm('Annuler ce paiement ?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
