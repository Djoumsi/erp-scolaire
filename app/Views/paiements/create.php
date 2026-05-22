<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/paiements') ?>">Paiements</a></li><li class="breadcrumb-item active">Encaisser</li></ol>

<div class="page-header">
    <h1><i class="bi bi-cash-coin text-success me-2"></i>Encaisser un paiement</h1>
</div>

<div class="row g-4">
    <!-- Infos dossier -->
    <div class="col-lg-4">
        <div class="form-card mb-4">
            <div class="form-section-title">Élève</div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Nom</dt><dd class="col-7 fw-semibold"><?= e($dossier['prenoms'].' '.$dossier['nom']) ?></dd>
                <dt class="col-5 text-muted">Matricule</dt><dd class="col-7 font-monospace"><?= e($dossier['matricule']) ?></dd>
                <dt class="col-5 text-muted">Classe</dt><dd class="col-7"><?= e($dossier['classe']) ?></dd>
                <dt class="col-5 text-muted">Année</dt><dd class="col-7"><?= e($dossier['annee']) ?></dd>
            </dl>
        </div>
        <div class="form-card">
            <div class="form-section-title">Situation financière</div>
            <dl class="row mb-0 small">
                <dt class="col-6 text-muted">Total dû</dt><dd class="col-6 fw-bold"><?= money($dossier['montant_total']) ?></dd>
                <dt class="col-6 text-muted">Déjà payé</dt><dd class="col-6 fw-bold text-success"><?= money($dossier['montant_paye']) ?></dd>
                <dt class="col-6 text-muted">Reste</dt><dd class="col-6 fw-bold text-danger"><?= money(max(0, $dossier['montant_total'] - $dossier['montant_paye'])) ?></dd>
            </dl>
            <div class="mt-3">
                <?php
                $pct = $dossier['montant_total'] > 0 ? min(100, round($dossier['montant_paye'] / $dossier['montant_total'] * 100)) : 0;
                ?>
                <div class="progress" style="height:8px;">
                    <div class="progress-bar bg-success" style="width:<?= $pct ?>%"></div>
                </div>
                <small class="text-muted"><?= $pct ?>% payé</small>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Formulaire paiement -->
        <div class="form-card mb-4">
            <div class="form-section-title">Nouveau paiement</div>
            <form method="POST" action="<?= url('/paiements') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="dossier_paiement_id" value="<?= $dossier['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="montant" class="form-control" placeholder="0" min="1" step="any" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                        <select name="mode_paiement" class="form-select" required>
                            <option value="espece">Espèces</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="cheque">Chèque</option>
                            <option value="carte">Carte bancaire</option>
                        </select>
                    </div>
                    <?php if (!empty($tranches)): ?>
                    <div class="col-12">
                        <label class="form-label">Tranche concernée</label>
                        <select name="tranche_id" class="form-select">
                            <option value="">— Paiement libre —</option>
                            <?php foreach ($tranches as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= e($t['type_frais'].' — '.money($t['montant_attendu']).' — Échéance: '.dateFormat($t['date_echeance'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label">Référence (optionnel)</label>
                        <input type="text" name="reference" class="form-control" placeholder="N° chèque, transaction…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Observation</label>
                        <input type="text" name="observation" class="form-control" placeholder="Note optionnelle">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Valider le paiement</button>
                    <a href="<?= url('/paiements') ?>" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>

        <!-- Historique -->
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Historique des paiements</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>N° Reçu</th><th>Montant</th><th>Mode</th><th>Date</th><th>Caissier</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($historique)): ?>
                <tr><td colspan="6" class="text-center py-3 text-muted">Aucun paiement enregistré</td></tr>
                <?php else: foreach ($historique as $h): ?>
                <tr class="<?= $h['annule'] ? 'text-decoration-line-through text-muted' : '' ?>">
                    <td class="font-monospace"><?= e($h['numero_recu']) ?></td>
                    <td class="fw-semibold"><?= money($h['montant']) ?></td>
                    <td><?= e($h['mode_paiement']) ?></td>
                    <td><?= dateFormat($h['date_paiement']) ?></td>
                    <td><?= e(($h['caissier_prenom']??'').' '.($h['caissier_nom']??'')) ?></td>
                    <td>
                        <?php if (!$h['annule']): ?>
                        <a href="<?= url('/paiements/'.$h['id'].'/recu') ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="Reçu"><i class="bi bi-printer"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
