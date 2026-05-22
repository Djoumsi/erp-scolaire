<!-- Vue bulletin pour impression (layout print) -->
<div style="font-family: 'Segoe UI', Arial, sans-serif; max-width: 800px; margin: 0 auto; font-size: 12px;">

    <!-- En-tête établissement -->
    <div style="text-align:center; border-bottom: 2px solid #1e2a3a; padding-bottom: 12px; margin-bottom: 16px;">
        <?php if (!empty($bulletin['etab_logo'])): ?>
        <img src="<?= url($bulletin['etab_logo']) ?>" style="height:60px; margin-bottom: 8px;" alt="">
        <?php endif; ?>
        <div style="font-size:16px; font-weight:700; text-transform:uppercase;"><?= e($bulletin['etab_nom']) ?></div>
        <?php if (!empty($bulletin['etab_adresse'])): ?>
        <div style="color:#64748b; font-size:11px;"><?= e($bulletin['etab_adresse']) ?></div>
        <?php endif; ?>
        <div style="font-size:14px; font-weight:600; margin-top:8px; color:#1e2a3a;">
            BULLETIN DE NOTES — <?= strtoupper($bulletin['periode_nom']) ?>
        </div>
        <div style="color:#64748b; font-size:11px;">Année scolaire <?= e($bulletin['annee']) ?></div>
    </div>

    <!-- Infos élève -->
    <table style="width:100%; margin-bottom:16px; border-collapse:collapse;">
        <tr>
            <td style="width:50%;">
                <table>
                    <tr><td style="color:#64748b; width:100px;">Nom :</td><td><strong><?= e(strtoupper($bulletin['nom']).' '.$bulletin['prenoms']) ?></strong></td></tr>
                    <tr><td style="color:#64748b;">Matricule :</td><td><?= e($bulletin['matricule']) ?></td></tr>
                    <?php if ($bulletin['date_naissance']): ?>
                    <tr><td style="color:#64748b;">Né(e) le :</td><td><?= dateFormat($bulletin['date_naissance']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </td>
            <td style="width:50%;">
                <table>
                    <tr><td style="color:#64748b; width:100px;">Classe :</td><td><strong><?= e($bulletin['classe_nom']) ?></strong></td></tr>
                    <tr><td style="color:#64748b;">Effectif :</td><td><?= (int)$bulletin['effectif_classe'] ?> élèves</td></tr>
                    <tr><td style="color:#64748b;">Rang :</td><td><strong><?= $bulletin['rang'] ?? '—' ?></strong> / <?= (int)$bulletin['effectif_classe'] ?></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tableau des notes -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
        <thead>
            <tr style="background:#1e2a3a; color:#fff;">
                <th style="padding:8px; text-align:left; border:1px solid #ddd;">Matière</th>
                <th style="padding:8px; text-align:center; border:1px solid #ddd;">Coef</th>
                <th style="padding:8px; text-align:center; border:1px solid #ddd;">Moyenne</th>
                <th style="padding:8px; text-align:center; border:1px solid #ddd;">Rang</th>
                <th style="padding:8px; text-align:left; border:1px solid #ddd;">Appréciation</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($matieres)): ?>
        <tr><td colspan="5" style="padding:12px; text-align:center; color:#94a3b8;">Notes non disponibles</td></tr>
        <?php else: foreach ($matieres as $m): ?>
        <tr style="border-bottom:1px solid #e2e8f0;">
            <td style="padding:7px 8px; border:1px solid #e2e8f0;"><?= e($m['matiere_nom']) ?></td>
            <td style="padding:7px 8px; text-align:center; border:1px solid #e2e8f0;"><?= $m['coefficient'] ?></td>
            <td style="padding:7px 8px; text-align:center; border:1px solid #e2e8f0; font-weight:<?= ($m['moyenne'] !== null && $m['moyenne'] >= 10) ? 'bold' : 'normal' ?>; color:<?= ($m['moyenne'] !== null && $m['moyenne'] < 10) ? '#dc2626' : 'inherit' ?>;">
                <?= $m['moyenne'] !== null ? number_format($m['moyenne'],2) : '—' ?>
            </td>
            <td style="padding:7px 8px; text-align:center; border:1px solid #e2e8f0;"><?= $m['rang'] ?? '—' ?></td>
            <td style="padding:7px 8px; border:1px solid #e2e8f0; font-style:italic; color:#64748b;"><?= e($m['appreciation'] ?? '') ?></td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9; font-weight:bold;">
                <td colspan="2" style="padding:8px; border:1px solid #ddd;">MOYENNE GÉNÉRALE</td>
                <td style="padding:8px; text-align:center; border:1px solid #ddd; font-size:14px; color:<?= ($bulletin['moyenne_generale'] !== null && $bulletin['moyenne_generale'] >= 10) ? '#16a34a' : '#dc2626' ?>;">
                    <?= $bulletin['moyenne_generale'] !== null ? number_format($bulletin['moyenne_generale'],2).'/20' : '—' ?>
                </td>
                <td style="padding:8px; text-align:center; border:1px solid #ddd;"><?= $bulletin['rang'] ?? '—' ?></td>
                <td style="padding:8px; border:1px solid #ddd;"><?= e($bulletin['mention'] ?? '') ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Signatures -->
    <div style="display:flex; justify-content:space-between; margin-top:40px;">
        <div style="text-align:center; width:200px;">
            <div style="border-top:1px solid #1e2a3a; padding-top:8px;">Le Directeur</div>
        </div>
        <div style="text-align:center; width:200px;">
            <div style="border-top:1px solid #1e2a3a; padding-top:8px;">Le Prof. Principal</div>
        </div>
        <div style="text-align:center; width:200px;">
            <div style="border-top:1px solid #1e2a3a; padding-top:8px;">Parent / Tuteur</div>
        </div>
    </div>
</div>
