<?php
/**
 * Newsletter Handler - Gestion des inscriptions
 * Tech Solutions DDTP - MBOA School
 * RGPD Compliant - Double Opt-In
 */

header('Content-Type: application/json');

// ============================================
// 1. CONFIGURATION
// ============================================

define('NEWSLETTER_DB', 'logs/subscribers.json');
define('NEWSLETTER_TOKENS', 'logs/confirmation_tokens.json');
define('MAX_SUBSCRIBERS_PER_DAY', 100); // Anti-spam

// ============================================
// 2. VALIDATION & SÉCURITÉ
// ============================================

function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Vérifier domaine MX existe
    $domain = substr(strrchr($email, "@"), 1);
    return checkdnsrr($domain, "MX");
}

function sanitizeEmail($email) {
    return strtolower(trim(filter_var($email, FILTER_SANITIZE_EMAIL)));
}

function rateLimit($ip) {
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }

    $rateFile = 'logs/newsletter_rate_limit.json';
    $rateData = file_exists($rateFile) ? json_decode(file_get_contents($rateFile), true) : [];

    // Nettoyer données > 24h
    $rateData = array_filter($rateData, function($entry) {
        return (time() - $entry['timestamp']) < 86400;
    });

    $ipKey = md5($ip);
    $count = 0;

    if (isset($rateData[$ipKey])) {
        $count = $rateData[$ipKey]['count'];
    }

    if ($count >= MAX_SUBSCRIBERS_PER_DAY) {
        return false;
    }

    $rateData[$ipKey] = [
        'count' => $count + 1,
        'timestamp' => time()
    ];

    file_put_contents($rateFile, json_encode($rateData, JSON_PRETTY_PRINT));
    return true;
}

// ============================================
// 3. GESTION ABONNÉS (JSON Storage)
// ============================================

function addSubscriber($email, $source = 'website') {
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }

    $subscribers = [];
    if (file_exists(NEWSLETTER_DB)) {
        $subscribers = json_decode(file_get_contents(NEWSLETTER_DB), true);
    }

    // Vérifier si déjà abonné
    foreach ($subscribers as $sub) {
        if (strtolower($sub['email']) === strtolower($email)) {
            return 'already_subscribed';
        }
    }

    // Ajouter nouvel abonné
    $subscriber = [
        'email' => sanitizeEmail($email),
        'subscribed_at' => date('Y-m-d H:i:s'),
        'source' => $source,
        'status' => 'pending', // pending = awaiting confirmation
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 255)
    ];

    $subscribers[] = $subscriber;
    file_put_contents(NEWSLETTER_DB, json_encode($subscribers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    return 'subscribed';
}

function confirmSubscriber($token) {
    if (!file_exists(NEWSLETTER_DB) || !file_exists(NEWSLETTER_TOKENS)) {
        return false;
    }

    $tokens = json_decode(file_get_contents(NEWSLETTER_TOKENS), true);

    // Vérifier token
    $email = null;
    foreach ($tokens as $t) {
        if ($t['token'] === $token && (time() - $t['created_at']) < 86400) {
            $email = $t['email'];
            break;
        }
    }

    if (!$email) {
        return false;
    }

    // Marquer comme confirmed
    $subscribers = json_decode(file_get_contents(NEWSLETTER_DB), true);
    foreach ($subscribers as &$sub) {
        if ($sub['email'] === $email) {
            $sub['status'] = 'active';
            $sub['confirmed_at'] = date('Y-m-d H:i:s');
            break;
        }
    }

    file_put_contents(NEWSLETTER_DB, json_encode($subscribers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    return true;
}

function unsubscribeEmail($email) {
    if (!file_exists(NEWSLETTER_DB)) {
        return false;
    }

    $subscribers = json_decode(file_get_contents(NEWSLETTER_DB), true);
    foreach ($subscribers as &$sub) {
        if ($sub['email'] === sanitizeEmail($email)) {
            $sub['status'] = 'unsubscribed';
            $sub['unsubscribed_at'] = date('Y-m-d H:i:s');
            break;
        }
    }

    file_put_contents(NEWSLETTER_DB, json_encode($subscribers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    return true;
}

// ============================================
// 4. TOKENS DE CONFIRMATION
// ============================================

function generateConfirmationToken($email) {
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }

    $token = bin2hex(random_bytes(32));

    $tokens = [];
    if (file_exists(NEWSLETTER_TOKENS)) {
        $tokens = json_decode(file_get_contents(NEWSLETTER_TOKENS), true);
    }

    // Nettoyer tokens expirés
    $tokens = array_filter($tokens, function($t) {
        return (time() - $t['created_at']) < 86400; // 24h
    });

    $tokens[] = [
        'email' => sanitizeEmail($email),
        'token' => $token,
        'created_at' => time()
    ];

    file_put_contents(NEWSLETTER_TOKENS, json_encode($tokens, JSON_PRETTY_PRINT), LOCK_EX);
    return $token;
}

// ============================================
// 5. ENVOI EMAIL DE CONFIRMATION
// ============================================

function sendConfirmationEmail($email) {
    require_once 'email-config.php';

    $token = generateConfirmationToken($email);
    $confirmUrl = 'https://erpscolaire.cm/newsletter-confirm.php?token=' . $token;

    $emailService = getEmailService();
    $htmlContent = "
        <html>
        <body style='font-family: Arial; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #0066cc 0%, #00a651 100%); color: white; padding: 2rem; text-align: center; border-radius: 10px 10px 0 0;'>
                    <h2>Confirmez votre abonnement MBOA School</h2>
                </div>
                <div style='padding: 2rem; background: white; border-radius: 0 0 10px 10px;'>
                    <p>Bonjour,</p>
                    <p>
                        Merci de votre intérêt pour la newsletter MBOA School !
                        Veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous.
                    </p>
                    <div style='text-align: center; margin: 2rem 0;'>
                        <a href='$confirmUrl' style='
                            display: inline-block;
                            background: #0066cc;
                            color: white;
                            padding: 12px 40px;
                            text-decoration: none;
                            border-radius: 5px;
                            font-weight: bold;
                        '>Confirmer mon abonnement</a>
                    </div>
                    <p style='color: #999; font-size: 12px;'>
                        Lien valide 24 heures<br>
                        Si le bouton ne fonctionne pas, copiez ce lien : <br>
                        <code style='background: #f0f0f0; padding: 5px;'>$confirmUrl</code>
                    </p>
                    <hr style='border: none; border-top: 1px solid #eee; margin: 2rem 0;'>
                    <p style='color: #999; font-size: 12px;'>
                        © 2026 Tech Solutions DDTP - MBOA School
                    </p>
                </div>
            </div>
        </body>
        </html>
    ";

    return $emailService->send($email, 'Subscriber', 'Confirmez votre abonnement MBOA School', $htmlContent);
}

// ============================================
// 6. HANDLER REQUÊTE
// ============================================

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'subscribe';

$response = ['success' => false, 'message' => ''];

try {
    // Vérifier méthode
    if ($method !== 'POST' && $action !== 'confirm') {
        throw new Exception('Méthode non autorisée');
    }

    // Rate limiting
    if (!rateLimit($_SERVER['REMOTE_ADDR'])) {
        throw new Exception('Trop de tentatives. Réessayez plus tard.');
    }

    if ($action === 'subscribe') {
        // INSCRIPTION
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            throw new Exception('Email requis');
        }

        if (!validateEmail($email)) {
            throw new Exception('Email invalide');
        }

        $result = addSubscriber($email, $_POST['source'] ?? 'website');

        if ($result === 'already_subscribed') {
            throw new Exception('Cet email est déjà abonné');
        }

        // Envoyer email de confirmation
        if (sendConfirmationEmail($email)) {
            $response['success'] = true;
            $response['message'] = 'Vérifiez votre email pour confirmer l\'abonnement';
        } else {
            throw new Exception('Erreur lors de l\'envoi de l\'email');
        }

    } elseif ($action === 'confirm') {
        // CONFIRMATION
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            throw new Exception('Token manquant');
        }

        if (confirmSubscriber($token)) {
            $response['success'] = true;
            $response['message'] = 'Email confirmé ! Vous êtes maintenant abonné.';
        } else {
            throw new Exception('Token invalide ou expiré');
        }

    } elseif ($action === 'unsubscribe') {
        // DÉSINSCRIPTION
        $email = $_GET['email'] ?? '';

        if (empty($email)) {
            throw new Exception('Email requis');
        }

        if (unsubscribeEmail($email)) {
            $response['success'] = true;
            $response['message'] = 'Vous avez été désinscrit';
        } else {
            throw new Exception('Email non trouvé');
        }
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// ============================================
// 7. LOG & RÉPONSE
// ============================================

// Logs
if (!is_dir('logs')) {
    mkdir('logs', 0755, true);
}
$logFile = 'logs/newsletter_actions.json';
$logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
$logs[] = [
    'timestamp' => date('Y-m-d H:i:s'),
    'action' => $action,
    'email' => $email ?? 'N/A',
    'status' => $response['success'] ? 'success' : 'failed',
    'message' => $response['message'],
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];
file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
