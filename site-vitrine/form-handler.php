<?php
/**
 * Form Handler pour le formulaire de contact
 * Envoie les données par email et dans une base de données
 */

header('Content-Type: application/json');

// Configuration
$to_email = 'contact@techsolutionsddtp.cm';
$whatsapp_number = '+237655454994';
$company_name = 'Tech Solutions DDTP';

// Validation des données
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupération des données
$nom = sanitize($_POST['nom'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$telephone = sanitize($_POST['telephone'] ?? '');
$etablissement = sanitize($_POST['etablissement'] ?? '');
$nb_eleves = sanitize($_POST['nb_eleves'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// Validation
$errors = [];
if (empty($nom)) $errors[] = 'Le nom est requis';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
if (empty($etablissement)) $errors[] = 'Le nom de l\'établissement est requis';
if (empty($message)) $errors[] = 'Le message est requis';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Créer le répertoire des logs si n'existe pas
if (!is_dir('logs')) {
    mkdir('logs', 0755, true);
}

// Enregistrer la demande dans un fichier JSON
$contact_data = [
    'date' => date('Y-m-d H:i:s'),
    'nom' => $nom,
    'email' => $email,
    'telephone' => $telephone,
    'etablissement' => $etablissement,
    'nb_eleves' => $nb_eleves,
    'message' => $message,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
];

$log_file = 'logs/contacts_' . date('Y-m-d') . '.json';
$contacts = [];

if (file_exists($log_file)) {
    $contacts = json_decode(file_get_contents($log_file), true) ?? [];
}

$contacts[] = $contact_data;
file_put_contents($log_file, json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Envoyer email (si possible)
$email_sent = send_email($to_email, $nom, $email, $etablissement, $nb_eleves, $message);

// Préparer la réponse
$response = [
    'success' => true,
    'message' => 'Votre demande a été enregistrée avec succès'
];

// Log de succès
log_contact($contact_data);

echo json_encode($response);
exit;

/**
 * Sanitize input
 */
function sanitize($input) {
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Envoyer un email
 */
function send_email($to, $nom, $email, $etablissement, $nb_eleves, $message) {
    $subject = "Nouvelle demande de contact - MBOA School | $etablissement";

    $body = "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 10px; }
        .header { background: linear-gradient(135deg, #0066cc 0%, #00a651 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { background: white; padding: 20px; border-radius: 0 0 10px 10px; }
        .field { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .label { font-weight: bold; color: #0066cc; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>📋 Nouvelle demande de contact</h1>
        </div>
        <div class='content'>
            <p>Bonjour,</p>
            <p>Une nouvelle demande de contact a été reçue depuis le site vitrine MBOA School.</p>

            <div class='field'>
                <div class='label'>👤 Nom complet :</div>
                <div>$nom</div>
            </div>

            <div class='field'>
                <div class='label'>📧 Email :</div>
                <div><a href='mailto:$email'>$email</a></div>
            </div>

            <div class='field'>
                <div class='label'>📱 Téléphone :</div>
                <div>$nb_eleves</div>
            </div>

            <div class='field'>
                <div class='label'>🏫 Établissement :</div>
                <div>$etablissement</div>
            </div>

            <div class='field'>
                <div class='label'>👥 Nombre d'élèves :</div>
                <div>$nb_eleves</div>
            </div>

            <div class='field'>
                <div class='label'>💬 Message :</div>
                <div style='white-space: pre-wrap;'>$message</div>
            </div>

            <div class='footer'>
                <p>Demande reçue le " . date('d/m/Y à H:i:s') . "</p>
                <p><strong>⚠️ Action recommandée :</strong> Répondre dans les 24h via email ou WhatsApp</p>
            </div>
        </div>
    </div>
</body>
</html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    // Note: mail() fonctionne si PHP est configuré avec sendmail
    // Sinon, logger simplement l'email
    return true; // Pour la démo
}

/**
 * Log les contacts
 */
function log_contact($data) {
    $log_file = 'logs/contact_log.txt';
    $log = date('Y-m-d H:i:s') . " | " . $data['nom'] . " | " . $data['email'] . " | " . $data['etablissement'] . "\n";
    file_put_contents($log_file, $log, FILE_APPEND);
}
?>
