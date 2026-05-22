<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu <?= e($paiement['numero_recu']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; background: #fff; }
        .recu { width: 80mm; margin: 0 auto; padding: 8mm; }
        .entete { text-align: center; margin-bottom: 8px; border-bottom: 2px solid #000; padding-bottom: 6px; }
        .entete h1 { font-size: 14px; font-weight: bold; }
        .entete p { font-size: 10px; color: #444; }
        .titre-recu { text-align: center; font-size: 13px; font-weight: bold; margin: 8px 0; border: 1px solid #000; padding: 4px; }
        .info-row { display: flex; justify-content: space-between; margin: 3px 0; font-size: 11px; }
        .info-row .label { color: #555; }
        .montant { text-align: center; font-size: 18px; font-weight: bold; margin: 10px 0; border: 2px solid #000; padding: 6px; }
        .signature { margin-top: 12px; display: flex; justify-content: space-between; font-size: 10px; }
        .merci { text-align: center; font-size: 10px; color: #666; margin-top: 8px; border-top: 1px dashed #aaa; padding-top: 6px; }
        @media screen {
            body { background: #f0f0f0; }
            .recu { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.15); margin-top: 2rem; }
            .print-btn { text-align: center; margin: 1rem; }
        }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
<div class="print-btn">
    <button onclick="window.print()" style="padding:.5rem 2rem;background:#3b82f6;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:14px">
        🖨️ Imprimer
    </button>
    <a href="<?= url('/paiements') ?>" style="margin-left:1rem;padding:.5rem 1.5rem;background:#e5e7eb;color:#222;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:14px">
        ← Retour
    </a>
</div>

<div class="recu">
    <!-- En-tête établissement -->
    <div class="entete">
        <?php if (!empty($paiement['etab_logo'])): ?>
        <img src="<?= url($paiement['etab_logo']) ?>" style="height:30px;margin-bottom:4px" alt="">
        <?php endif; ?>
        <h1><?= e($paiement['etablissement_nom']) ?></h1>
        <?php if (!empty($paiement['etab_adresse'])): ?>
        <p><?= e($paiement['etab_adresse']) ?></p>
        <?php endif; ?>
        <?php if (!empty($paiement['etab_tel'])): ?>
        <p>Tél : <?= e($paiement['etab_tel']) ?></p>
        <?php endif; ?>
    </div>

    <div class="titre-recu">REÇU DE PAIEMENT</div>

    <div class="info-row"><span class="label">N° Reçu</span><strong><?= e($paiement['numero_recu']) ?></strong></div>
    <div class="info-row"><span class="label">Date</span><span><?= dateFormat($paiement['date_paiement']) ?></span></div>
    <div class="info-row"><span class="label">Année</span><span><?= e($paiement['annee']) ?></span></div>

    <hr style="margin:6px 0;border-color:#ddd">

    <div class="info-row"><span class="label">Élève</span><strong><?= e(strtoupper($paiement['nom']) . ' ' . $paiement['prenoms']) ?></strong></div>
    <div class="info-row"><span class="label">Matricule</span><span><?= e($paiement['matricule']) ?></span></div>
    <div class="info-row"><span class="label">Classe</span><span><?= e($paiement['classe']) ?></span></div>

    <hr style="margin:6px 0;border-color:#ddd">

    <div class="info-row"><span class="label">Mode de paiement</span><span><?= ucfirst(str_replace('_', ' ', $paiement['mode_paiement'])) ?></span></div>
    <?php if (!empty($paiement['reference_transaction'])): ?>
    <div class="info-row"><span class="label">Référence</span><span><?= e($paiement['reference_transaction']) ?></span></div>
    <?php endif; ?>

    <div class="montant"><?= money($paiement['montant']) ?></div>

    <?php if (!empty($paiement['observation'])): ?>
    <div style="font-size:10px;color:#555;margin-bottom:8px">Note : <?= e($paiement['observation']) ?></div>
    <?php endif; ?>

    <div class="signature">
        <div>
            <div style="margin-bottom:16px">Reçu par :</div>
            <div><?= e($paiement['caissier_prenom'] . ' ' . $paiement['caissier_nom']) ?></div>
        </div>
        <div style="text-align:right">
            <div style="margin-bottom:16px">Signature & Cachet :</div>
            <div style="width:60px;height:40px;border-bottom:1px solid #000"></div>
        </div>
    </div>

    <div class="merci">Merci pour votre paiement · Ce reçu est votre justificatif officiel</div>
</div>
</body>
</html>
