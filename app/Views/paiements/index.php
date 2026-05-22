<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item active">Paiements</li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-cash-coin text-success me-2"></i>Paiements</h1>
        <?php if ($annee): ?>
        <small class="text-muted">Année scolaire : <?= e($annee['libelle']) ?></small>
        <?php endif; ?>
    </div>
    <div class="text-end">
        <div class="fw-bold text-success fs-5"><?= money($totalMois) ?></div>
        <div class="text-muted small">Encaissé ce mois</div>
    </div>
</div>

<!-- Filtres -->
<div class="table-card mb-3 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control" placeholder="Rechercher élève (nom, matricule)…" value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="statut" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="non_paye" <?= $statut === 'non_paye' ? 'selected' : '' ?>>Non payé</option>
                <option value="partiel"  <?= $statut === 'partiel'  ? 'selected' : '' ?>>Partiel</option>
                <option value="solde"    <?= $statut === 'solde'    ? 'selected' : '' ?>>Soldé</option>
                <option value="exonere"  <?= $statut === 'exonere'  ? 'selected' : '' ?>>Exonéré</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Filtrer</button></div>
        <?php if ($search || $statut): ?>
        <div class="col-md-2"><a href="<?= url('/paiements') ?>" class="btn btn-outline-secondary w-100">Réinitialiser</a></div>
        <?php endif; ?>
    </form>
</div>

<div class="table-card">
    <div class="table-card-header">
        <span class="text-muted small"><?= number_format($pagination['total']) ?> dossier(s)</span>
        <?php if (can('paiements.exporter')): ?>
        <a href="<?= url('/paiements/export/excel') ?>" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Exporter
        </a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Élève</th>
                    <th>Classe</th>
                    <th class="text-end">Total dû</th>
                    <th class="text-end">Payé</th>
                    <th class="text-end">Restant</th>
                    <th>Statut</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dossiers)): ?>
                <tr><td colspan="7" class="text-center py-5 text-muted">Aucun dossier trouvé</td></tr>
                <?php else: ?>
                <?php foreach ($dossiers as $d): ?>
                <?php
                $restant = $d['montant_total'] - $d['total_paye'];
                ?>
                <tr>
                    <td>
                        <div class="fw-medium"><?= e(strtoupper($d['nom']) . ' ' . $d['prenoms']) ?></div>
                        <div class="text-muted" style="font-size:.75rem"><code><?= e($d['matricule']) ?></code></div>
                    </td>
                    <td><?= e($d['classe']) ?></td>
                    <td class="text-end fw-medium"><?= money($d['montant_total']) ?></td>
                    <td class="text-end text-success"><?= money($d['total_paye']) ?></td>
                    <td class="text-end <?= $restant > 0 ? 'text-danger fw-semibold' : 'text-muted' ?>"><?= $restant > 0 ? money($restant) : '—' ?></td>
                    <td><?= statutBadge($d['statut']) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <?php if (can('paiements.encaisser') && $d['statut'] !== 'solde'): ?>
                            <a href="<?= url('/paiements/encaisser/' . $d['inscription_id']) ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Encaisser">
                                <i class="bi bi-cash"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= url('/paiements/encaisser/' . $d['inscription_id']) ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Dossier">
                                <i class="bi bi-folder2-open"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination['last_page'] > 1): ?>
    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Page <?= $pagination['current_page'] ?> / <?= $pagination['last_page'] ?></small>
        <?= paginationLinks($pagination, url('/paiements') . '?q=' . urlencode($search) . '&statut=' . $statut) ?>
    </div>
    <?php endif; ?>
</div>
