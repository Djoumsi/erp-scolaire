<?php
use App\Core\Router;

/** @var Router $router */

// -------------------------------------------------------
// Auth (publiques)
// -------------------------------------------------------
$router->get('/login',           'AuthController@showLogin',    'login');
$router->post('/login',          'AuthController@login');
$router->get('/logout',          'AuthController@logout',       'logout');
$router->get('/mot-de-passe',                    'AuthController@showReset',       'password.request');
$router->post('/mot-de-passe',                   'AuthController@sendReset');
$router->get('/mot-de-passe/nouveau/{token}',    'AuthController@showNewPassword');
$router->post('/mot-de-passe/reinitialiser',     'AuthController@resetPassword');

// Redirect racine
$router->get('/', 'DashboardController@index');

// -------------------------------------------------------
// Dashboard
// -------------------------------------------------------
$router->get('/dashboard', 'DashboardController@index', 'dashboard')->middleware(['auth']);

// -------------------------------------------------------
// Établissements (super_admin)
// -------------------------------------------------------
$router->get('/etablissements',                  'EtablissementController@index',   'etablissements.index')  ->middleware(['auth', 'role:super_admin']);
$router->get('/etablissements/creer',            'EtablissementController@create',  'etablissements.create') ->middleware(['auth', 'role:super_admin']);
$router->post('/etablissements',                 'EtablissementController@store')   ->middleware(['auth', 'role:super_admin']);
$router->get('/etablissements/{id}',             'EtablissementController@show',    'etablissements.show')   ->middleware(['auth', 'role:super_admin']);
$router->get('/etablissements/{id}/modifier',    'EtablissementController@edit',    'etablissements.edit')   ->middleware(['auth', 'role:super_admin']);
$router->post('/etablissements/{id}',            'EtablissementController@update')  ->middleware(['auth', 'role:super_admin']);
$router->post('/etablissements/{id}/supprimer',  'EtablissementController@destroy') ->middleware(['auth', 'role:super_admin']);

// -------------------------------------------------------
// Paramètres (admin, directeur)
// -------------------------------------------------------
$router->get('/parametres',                      'ParametreController@index',       'parametres')->middleware(['auth', 'can:parametres.voir']);
$router->post('/parametres/annee-scolaire',      'ParametreController@storeAnnee')  ->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/annee-scolaire/{id}/activer', 'ParametreController@activerAnnee')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/cycle',               'ParametreController@storeCycle')  ->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/cycle/{id}/supprimer','ParametreController@destroyCycle')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/niveau',              'ParametreController@storeNiveau') ->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/matiere',             'ParametreController@storeMatiere')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/salle',               'ParametreController@storeSalle')  ->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/creneau',             'ParametreController@storeCreneau')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/periode',             'ParametreController@storePeriode')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/type-frais',          'ParametreController@storeTypeFrais')->middleware(['auth', 'can:parametres.modifier']);
$router->post('/parametres/type-evaluation',     'ParametreController@storeTypeEval')->middleware(['auth', 'can:parametres.modifier']);

// -------------------------------------------------------
// Élèves
// -------------------------------------------------------
$router->get('/eleves',                          'EleveController@index',   'eleves.index')  ->middleware(['auth', 'can:eleves.voir']);
$router->get('/eleves/inscrire',                 'EleveController@create',  'eleves.create') ->middleware(['auth', 'can:eleves.creer']);
$router->post('/eleves',                         'EleveController@store')   ->middleware(['auth', 'can:eleves.creer']);
$router->get('/eleves/export/excel',             'EleveController@export')       ->middleware(['auth', 'can:eleves.exporter']);
$router->get('/eleves/import',                   'EleveController@showImport')   ->middleware(['auth', 'can:eleves.creer']);
$router->post('/eleves/import',                  'EleveController@importCsv')    ->middleware(['auth', 'can:eleves.creer']);
$router->get('/eleves/{id}',                     'EleveController@show',    'eleves.show')   ->middleware(['auth', 'can:eleves.voir']);
$router->get('/eleves/{id}/modifier',            'EleveController@edit',    'eleves.edit')   ->middleware(['auth', 'can:eleves.modifier']);
$router->post('/eleves/{id}',                    'EleveController@update')  ->middleware(['auth', 'can:eleves.modifier']);
$router->post('/eleves/{id}/supprimer',          'EleveController@destroy') ->middleware(['auth', 'can:eleves.supprimer']);

// -------------------------------------------------------
// Personnel
// -------------------------------------------------------
$router->get('/personnel',                       'PersonnelController@index',   'personnel.index') ->middleware(['auth', 'can:personnel.voir']);
$router->get('/personnel/creer',                 'PersonnelController@create',  'personnel.create')->middleware(['auth', 'can:personnel.creer']);
$router->post('/personnel',                      'PersonnelController@store')   ->middleware(['auth', 'can:personnel.creer']);
$router->get('/personnel/{id}',                  'PersonnelController@show',    'personnel.show')  ->middleware(['auth', 'can:personnel.voir']);
$router->get('/personnel/{id}/modifier',         'PersonnelController@edit',    'personnel.edit')  ->middleware(['auth', 'can:personnel.modifier']);
$router->post('/personnel/{id}',                 'PersonnelController@update')  ->middleware(['auth', 'can:personnel.modifier']);
$router->post('/personnel/{id}/supprimer',       'PersonnelController@destroy') ->middleware(['auth', 'can:personnel.supprimer']);

// -------------------------------------------------------
// Classes
// -------------------------------------------------------
$router->get('/classes',                         'ClasseController@index',   'classes.index')  ->middleware(['auth', 'can:classes.voir']);
$router->get('/classes/creer',                   'ClasseController@create',  'classes.create') ->middleware(['auth', 'can:classes.creer']);
$router->post('/classes',                        'ClasseController@store')   ->middleware(['auth', 'can:classes.creer']);
$router->get('/classes/{id}',                    'ClasseController@show',    'classes.show')   ->middleware(['auth', 'can:classes.voir']);
$router->get('/classes/{id}/modifier',           'ClasseController@edit',    'classes.edit')   ->middleware(['auth', 'can:classes.modifier']);
$router->post('/classes/{id}',                   'ClasseController@update')  ->middleware(['auth', 'can:classes.modifier']);
$router->post('/classes/{id}/supprimer',         'ClasseController@destroy') ->middleware(['auth', 'can:classes.supprimer']);
$router->post('/classes/{id}/affecter-cours',    'ClasseController@affecterCours')->middleware(['auth', 'can:classes.modifier']);

// -------------------------------------------------------
// Emploi du Temps
// -------------------------------------------------------
$router->get('/emploi-du-temps',                 'EmploiDuTempsController@index',   'edt.index')  ->middleware(['auth', 'can:emploi_temps.voir']);
$router->get('/emploi-du-temps/{classeId}',      'EmploiDuTempsController@show',    'edt.show')   ->middleware(['auth', 'can:emploi_temps.voir']);
$router->post('/emploi-du-temps',                'EmploiDuTempsController@store')   ->middleware(['auth', 'can:emploi_temps.modifier']);
$router->post('/emploi-du-temps/{id}/supprimer', 'EmploiDuTempsController@destroy') ->middleware(['auth', 'can:emploi_temps.modifier']);

// -------------------------------------------------------
// Présences
// -------------------------------------------------------
$router->get('/presences',                       'PresenceController@index',   'presences.index') ->middleware(['auth', 'can:presences.voir']);
$router->get('/presences/appel/{affectationId}', 'PresenceController@appel',   'presences.appel') ->middleware(['auth', 'can:presences.saisir']);
$router->post('/presences/appel',                'PresenceController@storeAppel')->middleware(['auth', 'can:presences.saisir']);
$router->get('/presences/rapport',               'PresenceController@rapport',  'presences.rapport')->middleware(['auth', 'can:presences.voir']);

// -------------------------------------------------------
// Notes
// -------------------------------------------------------
$router->get('/notes',                           'NoteController@index',     'notes.index')    ->middleware(['auth', 'can:notes.voir']);
$router->get('/notes/saisir/{affectationId}',    'NoteController@saisir',    'notes.saisir')   ->middleware(['auth', 'can:notes.saisir']);
$router->post('/notes/saisir',                   'NoteController@storeBulk') ->middleware(['auth', 'can:notes.saisir']);
$router->get('/notes/evaluation/{id}',           'NoteController@byEvaluation')->middleware(['auth', 'can:notes.voir']);
$router->post('/notes/evaluation',               'NoteController@createEvaluation')->middleware(['auth', 'can:notes.saisir']);
$router->get('/notes/classe/{classeId}',         'NoteController@byClasse')  ->middleware(['auth', 'can:notes.voir']);

// -------------------------------------------------------
// Bulletins
// -------------------------------------------------------
$router->get('/bulletins',                       'BulletinController@index',   'bulletins.index')  ->middleware(['auth', 'can:bulletins.voir']);
$router->get('/bulletins/generer/{classeId}/{periodeId}', 'BulletinController@generer')->middleware(['auth', 'can:bulletins.generer']);
$router->post('/bulletins/generer',              'BulletinController@generate')->middleware(['auth', 'can:bulletins.generer']);
$router->get('/bulletins/{id}/pdf',              'BulletinController@pdf',     'bulletins.pdf')    ->middleware(['auth', 'can:bulletins.voir']);
$router->get('/bulletins/classe/{classeId}',     'BulletinController@byClasse')->middleware(['auth', 'can:bulletins.voir']);

// -------------------------------------------------------
// Paiements
// -------------------------------------------------------
$router->get('/paiements',                       'PaiementController@index',   'paiements.index')  ->middleware(['auth', 'can:paiements.voir']);
$router->get('/paiements/encaisser/{inscriptionId}','PaiementController@create','paiements.create') ->middleware(['auth', 'can:paiements.encaisser']);
$router->post('/paiements',                      'PaiementController@store')   ->middleware(['auth', 'can:paiements.encaisser']);
$router->get('/paiements/export/excel',          'PaiementController@export')  ->middleware(['auth', 'can:paiements.exporter']);
$router->get('/paiements/{id}',                  'PaiementController@show',    'paiements.show')   ->middleware(['auth', 'can:paiements.voir']);
$router->get('/paiements/{id}/recu',             'PaiementController@recu',    'paiements.recu')   ->middleware(['auth', 'can:paiements.voir']);
$router->post('/paiements/{id}/annuler',         'PaiementController@annuler') ->middleware(['auth', 'can:paiements.annuler']);

// -------------------------------------------------------
// Comptabilité
// -------------------------------------------------------
$router->get('/comptabilite',                    'ComptabiliteController@index',  'compta.index')  ->middleware(['auth', 'can:comptabilite.voir']);
$router->post('/comptabilite',                   'ComptabiliteController@store')  ->middleware(['auth', 'can:comptabilite.creer']);
$router->get('/comptabilite/bilan',              'ComptabiliteController@bilan',  'compta.bilan')  ->middleware(['auth', 'can:comptabilite.voir']);
$router->get('/comptabilite/export',             'ComptabiliteController@export') ->middleware(['auth', 'can:comptabilite.exporter']);

// -------------------------------------------------------
// Examens
// -------------------------------------------------------
$router->get('/examens',                         'ExamenController@index',   'examens.index')  ->middleware(['auth', 'can:examens.voir']);
$router->get('/examens/creer',                   'ExamenController@create',  'examens.create') ->middleware(['auth', 'can:examens.creer']);
$router->post('/examens',                        'ExamenController@store')   ->middleware(['auth', 'can:examens.creer']);
$router->get('/examens/{id}',                    'ExamenController@show',    'examens.show')   ->middleware(['auth', 'can:examens.voir']);
$router->get('/examens/{id}/modifier',           'ExamenController@edit')    ->middleware(['auth', 'can:examens.modifier']);
$router->post('/examens/{id}',                   'ExamenController@update')  ->middleware(['auth', 'can:examens.modifier']);
$router->post('/examens/{id}/planning',          'ExamenController@addPlanning')->middleware(['auth', 'can:examens.modifier']);

// -------------------------------------------------------
// Bibliothèque
// -------------------------------------------------------
$router->get('/bibliotheque',                    'BibliothequeController@index',  'biblio.index')   ->middleware(['auth', 'can:bibliotheque.voir']);
$router->post('/bibliotheque/livre',             'BibliothequeController@storeLivre')->middleware(['auth', 'can:bibliotheque.gerer']);
$router->post('/bibliotheque/emprunter',         'BibliothequeController@emprunter')->middleware(['auth', 'can:bibliotheque.gerer']);
$router->post('/bibliotheque/retour/{id}',       'BibliothequeController@retour')   ->middleware(['auth', 'can:bibliotheque.gerer']);

// -------------------------------------------------------
// Communication
// -------------------------------------------------------
$router->get('/annonces',                        'CommunicationController@index',   'annonces.index')  ->middleware(['auth', 'can:communication.voir']);
$router->get('/annonces/creer',                  'CommunicationController@create',  'annonces.create') ->middleware(['auth', 'can:communication.creer']);
$router->post('/annonces',                       'CommunicationController@store')   ->middleware(['auth', 'can:communication.creer']);
$router->get('/annonces/{id}',                   'CommunicationController@show')    ->middleware(['auth', 'can:communication.voir']);
$router->get('/messages',                        'CommunicationController@messages',     'messages.index') ->middleware(['auth']);
$router->get('/messages/{id}',                   'CommunicationController@showMessage',  'messages.show')  ->middleware(['auth']);
$router->post('/messages/envoyer',               'CommunicationController@sendMessage')                    ->middleware(['auth']);
$router->get('/notifications',                   'CommunicationController@notifications')->middleware(['auth']);
$router->post('/notifications/lire/{id}',        'CommunicationController@markRead')->middleware(['auth']);

// -------------------------------------------------------
// Rapports
// -------------------------------------------------------
$router->get('/rapports',                        'RapportController@index',       'rapports.index')->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/eleves',                 'RapportController@eleves')      ->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/eleves/export',          'RapportController@exportEleves')->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/notes',                  'RapportController@notes')       ->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/notes/export',           'RapportController@exportNotes') ->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/paiements',              'RapportController@paiements')   ->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/paiements/export',       'RapportController@exportPaiements')->middleware(['auth', 'can:rapports.voir']);
$router->get('/rapports/presences',              'RapportController@presences')   ->middleware(['auth', 'can:rapports.voir']);

// -------------------------------------------------------
// Profil utilisateur
// -------------------------------------------------------
$router->get('/profil',                          'ProfilController@show',   'profil') ->middleware(['auth']);
$router->post('/profil',                         'ProfilController@update')           ->middleware(['auth']);
$router->post('/profil/mot-de-passe',            'ProfilController@changePassword')   ->middleware(['auth']);
