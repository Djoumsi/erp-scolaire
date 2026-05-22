<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/notes') ?>">Notes</a></li>
    <li class="breadcrumb-item active"><?= e($classe['nom']) ?></li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-table text-primary me-2"></i>Notes — <?= e($classe['nom']) ?></h1>
        <p class="text-muted mb-0"><?= e($classe['niveau_nom']) ?></p>
    </div>
</div>

<!-- Filtre période -->
<?php if (!empty($periodes)): ?>
<div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
    <span class="text-muted small fw-medium">Période :</span>
    <?php foreach ($periodes as $p): ?>
    <a href="<?= url('/notes/classe/'.$classe['id'].'?periode='.$p['id']) ?>"
       class="btn btn-sm <?= ($periode && $periode['id'] == $p['id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($p['nom']) ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($matieres) || empty($eleves)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune donnée disponible pour cette classe.</div>
<?php else: ?>

<div class="table-card">
    <div class="table-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
            Tableau de moyennes <?= $periode ? '— <span class="text-primary">'.e($periode['nom']).'</span>' : '' ?>
        </h6>
        <?php if (can('bulletins.voir') && $periode): ?>
        <a href="<?= url('/bulletins/classe/'.$classe['id'].'?periode='.$periode['id']) ?>"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-text me-1"></i>Voir les bulletins
        </a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0" style="min-width:700px;">
            <thead class="table-dark">
                <tr>
                    <th style="min-width:180px;">Élève</th>
                    <?php foreach ($matieres as $m): ?>
                    <th class="text-center small" title="Coeff. <?= $m['coefficient'] ?> — <?= e($m['prof_prenom'].' '.$m['prof_nom']) ?>">
                        <?= e(mb_substr($m['matiere_nom'], 0, 6)) ?>.
                        <div class="badge bg-secondary" style="font-size:9px;">C<?= $m['coefficient'] ?></div>
                    </th>
                    <?php endforeach; ?>
                    <th class="text-center">Moy. Gén.</th>
                    <th class="text-center">Rang</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($eleves as $e): ?>
            <?php
                $moyGene = $moyennes[$e['inscription_id']]['gen'] ?? null;
                $ok = $moyGene !== null && $moyGene >= 10;
            ?>
            <tr>
                <td class="fw-medium">
                    <?= htmlspecialchars(strtoupper($e['nom']).' '.$e['prenoms']) ?>
                    <div class="text-muted" style="font-size:10px;"><?= htmlspecialchars($e['matricule']) ?></div>
                </td>
                <?php foreach ($matieres as $m): ?>
                <?php $moy = $moyennes[$e['inscription_id']][$m['id']] ?? null; ?>
                <td class="text-center small">
                    <?php if ($moy !== null): ?>
                    <span class="fw-bold <?= $moy >= 10 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($moy, 1) ?>
                    </span>
                    <?php else: ?>
                    <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
                <td class="text-center">
                    <span class="fw-bold fs-6 <?= $ok ? 'text-success' : ($moyGene !== null ? 'text-danger' : 'text-muted') ?>">
                        <?= $moyGene !== null ? number_format($moyGene, 2) : '—' ?>
                    </span>
                </td>
                <td class="text-center">
                    <?php $rang = $moyennes[$e['inscription_id']]['rang'] ?? null; ?>
                    <?= $rang ? '<span class="badge bg-secondary">'.$rang.'</span>' : '—' ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Légende -->
<div class="mt-3 d-flex gap-3 flex-wrap">
    <?php foreach ($matieres as $m): ?>
    <small class="text-muted">
        <strong><?= e(mb_substr($m['matiere_nom'], 0, 6)) ?>.</strong> = <?= e($m['matiere_nom']) ?> (C<?= $m['coefficient'] ?>)
    </small>
    <?php endforeach; ?>
</div>

<?php endif; ?>
