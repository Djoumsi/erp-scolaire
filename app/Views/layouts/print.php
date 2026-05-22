<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= APP_NAME ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; background: #fff; }
        @media screen { body { background: #e5e7eb; } .print-wrapper { max-width: 900px; margin: 1rem auto; background: #fff; box-shadow: 0 2px 20px rgba(0,0,0,.2); padding: 2rem; } .no-print { text-align: center; margin: 1rem; } }
        @media print { .no-print { display: none !important; } .print-wrapper { padding: 0; } }
    </style>
</head>
<body>
<div class="no-print" style="background:#1e2a3a;padding:.75rem;color:#fff;text-align:center">
    <button onclick="window.print()" style="background:#3b82f6;color:#fff;border:none;padding:.5rem 2rem;border-radius:6px;cursor:pointer;font-size:14px;margin-right:1rem">
        🖨️ Imprimer
    </button>
    <a href="javascript:history.back()" style="color:#94a3b8;text-decoration:none">← Retour</a>
</div>
<div class="print-wrapper">
    <?= $content ?>
</div>
</body>
</html>
