<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Comptabilité</li></ol>

<div class="page-header">
    <h1><i class="bi bi-bar-chart-fill text-primary me-2"></i>Comptabilité <?= $annee ? '<span class="text-muted fs-6 fw-normal">— '.e($annee['libelle']).'</span>' : '' ?></h1>
    <form method="GET" class="d-flex gap-2">
        <input type="month" name="mois" class="form-control form-control-sm" value="<?= e($mois) ?>">
        <button class="btn btn-sm btn-outline-primary">Filtrer</button>
    </form>
</div>

<!-- Résumé financier -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div><div class="stat-value text-success"><?= money($totaux['recette']) ?></div><div class="stat-label">Recettes du mois</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <div><div class="stat-value text-danger"><?= money($totaux['depense']) ?></div><div class="stat-label">Dépenses du mois</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon <?= $solde >= 0 ? 'blue' : 'orange' ?>"><i class="bi bi-wallet2"></i></div>
            <div><div class="stat-value <?= $solde >= 0 ? 'text-primary' : 'text-warning' ?>"><?= money($solde) ?></div><div class="stat-label">Solde</div></div>
        </div>
    </div>
</div>

<!-- Formulaire nouvelle transaction -->
<?php if (can('comptabilite.creer')): ?>
<div class="form-card mb-4">
    <div class="form-section-title">Enregistrer une transaction</div>
    <form method="POST" action="<?= url('/comptabilite') ?>" class="row g-3 align-items-end">
        <?= csrf_field() ?>
        <div class="col-md-3">
            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
            <select name="categorie_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php
                $recettes = array_filter($categories, fn($c) => $c['type'] === 'recette');
                $depenses = array_filter($categories, fn($c) => $c['type'] === 'depense');
                if (!empty($recettes)): ?><optgroup label="Recettes">
                    <?php foreach ($recettes as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option><?php endforeach; ?>
                </optgroup><?php endif;
                if (!empty($depenses)): ?><optgroup label="Dépenses">
                    <?php foreach ($depenses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option><?php endforeach; ?>
                </optgroup><?php endif; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Libellé <span class="text-danger">*</span></label>
            <input type="text" name="libelle" class="form-control" required placeholder="Description…">
        </div>
        <div class="col-md-2">
            <label class="form-label">Montant <span class="text-danger">*</span></label>
            <input type="number" name="montant" class="form-control" required min="1" placeholder="0">
        </div>
        <div class="col-md-2">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" name="date_transaction" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-lg me-1"></i>Enregistrer</button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Tableau des transactions -->
<div class="table-card">
    <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Transactions — <?= e($mois) ?></h6></div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Date</th><th>Libellé</th><th>Catégorie</th><th>Type</th><th class="text-end">Montant</th></tr>
        </thead>
        <tbody>
        <?php if (empty($transactions)): ?>
        <tr><td colspan="5" class="text-center py-5 text-muted">Aucune transaction ce mois-ci</td></tr>
        <?php else: ?>
        <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= dateFormat($t['date_transaction']) ?></td>
            <td class="fw-medium"><?= e($t['libelle']) ?></td>
            <td class="small text-muted"><?= e($t['categorie']) ?></td>
            <td><?= $t['type'] === 'recette' ? '<span class="badge bg-success bg-opacity-15 text-success">Recette</span>' : '<span class="badge bg-danger bg-opacity-15 text-danger">Dépense</span>' ?></td>
            <td class="text-end fw-semibold <?= $t['type'] === 'recette' ? 'text-success' : 'text-danger' ?>">
                <?= $t['type'] === 'recette' ? '+' : '-' ?><?= money($t['montant']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
