<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Paramètres</li></ol>

<div class="page-header"><h1><i class="bi bi-gear-fill text-secondary me-2"></i>Paramètres</h1></div>

<!-- Nav onglets -->
<ul class="nav nav-tabs mb-3" id="paramTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#annees">Années scolaires</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cycles">Cycles & Niveaux</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#matieres">Matières</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#creneaux">Créneaux</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#salles">Salles</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#frais">Types de frais</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#evaluations">Types éval.</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#periodes">Périodes</a></li>
</ul>

<div class="tab-content">

    <!-- Années scolaires -->
    <div class="tab-pane fade show active" id="annees">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <div class="table-card-header"><h6 class="mb-0">Années scolaires</h6></div>
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Libellé</th><th>Début</th><th>Fin</th><th>Statut</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($annees as $a): ?>
                        <tr>
                            <td class="fw-medium"><?= e($a['libelle']) ?></td>
                            <td><?= dateFormat($a['date_debut']) ?></td>
                            <td><?= dateFormat($a['date_fin']) ?></td>
                            <td><?= $a['en_cours'] ? '<span class="badge bg-success">En cours</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
                            <td>
                                <?php if (!$a['en_cours']): ?>
                                <form method="POST" action="<?= url('/parametres/annee-scolaire/' . $a['id'] . '/activer') ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-success" data-confirm="Activer cette année scolaire ?">Activer</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouvelle année</h6>
                    <form method="POST" action="<?= url('/parametres/annee-scolaire') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><label class="form-label">Libellé</label><input type="text" name="libelle" class="form-control" placeholder="2025-2026" required></div>
                        <div class="mb-2"><label class="form-label">Début</label><input type="date" name="date_debut" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Fin</label><input type="date" name="date_fin" class="form-control" required></div>
                        <button class="btn btn-primary w-100">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cycles & Niveaux -->
    <div class="tab-pane fade" id="cycles">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="table-card">
                    <div class="table-card-header"><h6 class="mb-0">Cycles</h6></div>
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Nom</th><th>Niveaux</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($cycles as $c): ?>
                        <tr><td><?= e($c['nom']) ?></td><td><small class="text-muted"><?= e($c['niveaux'] ?? '—') ?></small></td>
                        <td><form method="POST" action="<?= url('/parametres/cycle/'.$c['id'].'/supprimer') ?>">
                            <?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" data-confirm="Supprimer ce cycle ?">&times;</button></form></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-card mt-3">
                    <h6 class="fw-semibold mb-3">Nouveau cycle</h6>
                    <form method="POST" action="<?= url('/parametres/cycle') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="ex: Secondaire" required></div>
                        <div class="mb-3"><input type="number" name="ordre" class="form-control" placeholder="Ordre (1, 2, 3…)" min="1" required></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouveau niveau</h6>
                    <form method="POST" action="<?= url('/parametres/niveau') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2">
                            <label class="form-label">Cycle</label>
                            <select name="cycle_id" class="form-select" required>
                                <?php foreach ($cycles as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" placeholder="ex: 6ème" required></div>
                        <div class="mb-2"><label class="form-label">Abréviation</label><input type="text" name="abreviation" class="form-control" placeholder="ex: 6e"></div>
                        <div class="mb-3"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="1" min="1"></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Matières -->
    <div class="tab-pane fade" id="matieres">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Matière</th><th>Code</th><th>Type</th></tr></thead>
                        <tbody>
                        <?php foreach ($matieres as $m): ?>
                        <tr><td><?= e($m['nom']) ?></td><td><code><?= e($m['code'] ?? '—') ?></code></td><td><?= e($m['type']) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouvelle matière</h6>
                    <form method="POST" action="<?= url('/parametres/matiere') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="Nom de la matière" required></div>
                        <div class="mb-2"><input type="text" name="code" class="form-control" placeholder="Code (ex: MATHS)"></div>
                        <div class="mb-3">
                            <select name="type" class="form-select">
                                <option value="principale">Principale</option>
                                <option value="optionnelle">Optionnelle</option>
                                <option value="activite">Activité</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Créneaux -->
    <div class="tab-pane fade" id="creneaux">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>#</th><th>Libellé</th><th>Début</th><th>Fin</th><th>Type</th></tr></thead>
                        <tbody>
                        <?php foreach ($creneaux as $cr): ?>
                        <tr><td><?= $cr['ordre'] ?></td><td><?= e($cr['nom'] ?? '—') ?></td><td><?= substr($cr['heure_debut'],0,5) ?></td><td><?= substr($cr['heure_fin'],0,5) ?></td><td><?= $cr['type'] ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouveau créneau</h6>
                    <form method="POST" action="<?= url('/parametres/creneau') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="Libellé (ex: Cours 1)"></div>
                        <div class="mb-2"><input type="time" name="heure_debut" class="form-control" required></div>
                        <div class="mb-2"><input type="time" name="heure_fin" class="form-control" required></div>
                        <div class="mb-2"><select name="type" class="form-select"><option value="cours">Cours</option><option value="pause">Pause</option><option value="repas">Repas</option></select></div>
                        <div class="mb-3"><input type="number" name="ordre" class="form-control" placeholder="Ordre" value="1" min="1"></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Salles -->
    <div class="tab-pane fade" id="salles">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Salle</th><th>Capacité</th><th>Type</th></tr></thead>
                        <tbody>
                        <?php foreach ($salles as $s): ?>
                        <tr><td><?= e($s['nom']) ?></td><td><?= $s['capacite'] ?? '—' ?></td><td><?= $s['type'] ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouvelle salle</h6>
                    <form method="POST" action="<?= url('/parametres/salle') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="Nom (ex: Salle A1)" required></div>
                        <div class="mb-2"><input type="number" name="capacite" class="form-control" placeholder="Capacité"></div>
                        <div class="mb-3"><select name="type" class="form-select"><option value="cours">Cours</option><option value="labo">Laboratoire</option><option value="informatique">Informatique</option><option value="sport">Sport</option><option value="amphi">Amphithéâtre</option></select></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Types de frais -->
    <div class="tab-pane fade" id="frais">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Type de frais</th><th>Montant défaut</th><th>Obligatoire</th></tr></thead>
                        <tbody>
                        <?php foreach ($typesFrais as $tf): ?>
                        <tr><td><?= e($tf['nom']) ?></td><td><?= money($tf['montant_defaut']) ?></td><td><?= $tf['obligatoire'] ? '✓' : '—' ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouveau type de frais</h6>
                    <form method="POST" action="<?= url('/parametres/type-frais') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="ex: Scolarité, Inscription…" required></div>
                        <div class="mb-2"><input type="number" name="montant_defaut" class="form-control" placeholder="Montant par défaut" step="100"></div>
                        <div class="mb-3 form-check"><input type="checkbox" name="obligatoire" class="form-check-input" id="obligatoire" checked><label class="form-check-label" for="obligatoire">Obligatoire</label></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Types évaluation -->
    <div class="tab-pane fade" id="evaluations">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Type</th><th>Coefficient</th><th>Sur</th></tr></thead>
                        <tbody>
                        <?php foreach ($typesEval as $te): ?>
                        <tr><td><?= e($te['nom']) ?></td><td><?= $te['coefficient'] ?></td><td>/<?= $te['sur'] ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouveau type</h6>
                    <form method="POST" action="<?= url('/parametres/type-evaluation') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="ex: Devoir, Composition…" required></div>
                        <div class="mb-2"><input type="number" name="coefficient" class="form-control" placeholder="Coefficient" value="1" step="0.5" min="0.5"></div>
                        <div class="mb-3"><input type="number" name="sur" class="form-control" placeholder="Note sur" value="20" min="1" max="100"></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Périodes -->
    <div class="tab-pane fade" id="periodes">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="table-card">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>#</th><th>Période</th><th>Type</th><th>Début</th><th>Fin</th></tr></thead>
                        <tbody>
                        <?php foreach ($periodes as $p): ?>
                        <tr><td><?= $p['ordre'] ?></td><td><?= e($p['nom']) ?></td><td><?= $p['type'] ?></td><td><?= dateFormat($p['date_debut']) ?></td><td><?= dateFormat($p['date_fin']) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <?php if ($anneeEnCours): ?>
                <div class="form-card">
                    <h6 class="fw-semibold mb-3">Nouvelle période — <?= e($anneeEnCours['libelle']) ?></h6>
                    <form method="POST" action="<?= url('/parametres/periode') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="annee_scolaire_id" value="<?= $anneeEnCours['id'] ?>">
                        <div class="mb-2"><input type="text" name="nom" class="form-control" placeholder="ex: Trimestre 1" required></div>
                        <div class="mb-2"><select name="type" class="form-select"><option value="trimestre">Trimestre</option><option value="semestre">Semestre</option><option value="annuel">Annuel</option></select></div>
                        <div class="mb-2"><input type="date" name="date_debut" class="form-control" required></div>
                        <div class="mb-2"><input type="date" name="date_fin" class="form-control" required></div>
                        <div class="mb-3"><input type="number" name="ordre" class="form-control" placeholder="Ordre" value="1" min="1"></div>
                        <button class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
                <?php else: ?>
                <div class="alert alert-warning">Activez d'abord une année scolaire.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
