<?php
/**
 * Configuration Email & SMTP
 * Tech Solutions DDTP - MBOA School
 * Support : SendGrid, Mailgun, SMTP classique
 */

// ============================================
// 1. CONFIGURATION SENDGRID (Recommandé)
// ============================================

class SendGridEmail {
    private $apiKey;
    private $fromEmail;
    private $fromName;

    public function __construct($apiKey, $fromEmail, $fromName = 'Tech Solutions DDTP') {
        $this->apiKey = $apiKey;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * Envoyer un email via SendGrid API
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @param string $textContent
     * @return bool
     */
    public function send($toEmail, $toName, $subject, $htmlContent, $textContent = '') {
        $emailData = [
            'personalizations' => [
                [
                    'to' => [['email' => $toEmail, 'name' => $toName]],
                    'subject' => $subject
                ]
            ],
            'from' => [
                'email' => $this->fromEmail,
                'name' => $this->fromName
            ],
            'content' => [
                ['type' => 'text/html', 'value' => $htmlContent]
            ]
        ];

        if (!empty($textContent)) {
            array_unshift($emailData['content'], ['type' => 'text/plain', 'value' => $textContent]);
        }

        return $this->sendRequest($emailData);
    }

    /**
     * Envoyer une email de newsletter
     */
    public function sendNewsletter($toEmail, $toName, $subject, $templateContent) {
        $htmlContent = $this->getNewsletterTemplate($subject, $templateContent);
        return $this->send($toEmail, $toName, $subject, $htmlContent);
    }

    /**
     * Envoyer réponse automatique
     */
    public function sendAutoReply($toEmail, $toName, $originalSubject) {
        $subject = 'Re: ' . $originalSubject;
        $htmlContent = $this->getAutoReplyTemplate($toName);
        return $this->send($toEmail, $toName, $subject, $htmlContent);
    }

    private function sendRequest($emailData) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.sendgrid.com/v3/mail/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($emailData),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode === 202;
    }

    private function getNewsletterTemplate($subject, $content) {
        return "
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; }
                    .header { background: linear-gradient(135deg, #0066cc 0%, #00a651 100%); color: white; padding: 2rem; text-align: center; }
                    .content { padding: 2rem; background: white; }
                    .footer { background: #f8f9fa; padding: 1rem; text-align: center; font-size: 12px; color: #999; }
                    a { color: #0066cc; text-decoration: none; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>MBOA School Newsletter</h2>
                    </div>
                    <div class='content'>
                        <h3>$subject</h3>
                        $content
                        <hr style='margin: 2rem 0; border: none; border-top: 1px solid #eee;'>
                        <p style='font-size: 0.9rem; color: #999;'>
                            Vous recevez cet email car vous êtes abonné à notre newsletter.
                            <br>
                            <a href='https://erpscolaire.cm/newsletter-unsubscribe.html'>Vous désabonner</a>
                        </p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2026 Tech Solutions DDTP. Tous droits réservés.</p>
                        <p>Douala, Logbessou, Cameroon</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    private function getAutoReplyTemplate($toName) {
        return "
            <html>
            <body style='font-family: Arial; color: #333;'>
                <p>Bonjour $toName,</p>
                <p>
                    Merci de nous avoir contacté ! Nous avons bien reçu votre message.
                </p>
                <p>
                    Notre équipe reviendra vers vous dans un délai de <strong>24 heures</strong>
                    via email ou WhatsApp (+237 655 454 994).
                </p>
                <p>
                    Cordialement,<br>
                    <strong>Tech Solutions DDTP</strong><br>
                    MBOA School Support Team
                </p>
            </body>
            </html>
        ";
    }
}

// ============================================
// 2. CONFIGURATION SMTP CLASSIQUE (Alternative)
// ============================================

class SMTPEmail {
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;

    public function __construct($host, $port, $username, $password, $fromEmail, $fromName = 'Tech Solutions DDTP') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * Envoyer email via SMTP (PHPMailer)
     */
    public function send($toEmail, $toName, $subject, $htmlContent, $textContent = '') {
        // Utiliser PHPMailer si disponible
        if (!class_exists('PHPMailer')) {
            return $this->sendViaHeaders($toEmail, $toName, $subject, $htmlContent);
        }

        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = 'tls';

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlContent;
            if (!empty($textContent)) {
                $mail->AltBody = $textContent;
            }

            return $mail->send();
        } catch (\Exception $e) {
            error_log('SMTP Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fallback avec mail() classique
     */
    private function sendViaHeaders($toEmail, $toName, $subject, $htmlContent) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $this->fromName . " <" . $this->fromEmail . ">\r\n";
        $headers .= "Reply-To: " . $this->fromEmail . "\r\n";

        return mail($toEmail, $subject, $htmlContent, $headers);
    }
}

// ============================================
// 3. INSTANCE GLOBALE
// ============================================

/**
 * Factory pour créer instance email selon config
 */
function getEmailService() {
    // Déterminer quelle méthode utiliser
    $emailService = getenv('EMAIL_SERVICE') ?? 'sendgrid';

    if ($emailService === 'sendgrid') {
        return new SendGridEmail(
            getenv('SENDGRID_API_KEY'),
            getenv('EMAIL_FROM') ?? 'noreply@techsolutionsddtp.cm',
            'Tech Solutions DDTP'
        );
    } else {
        return new SMTPEmail(
            getenv('SMTP_HOST') ?? 'smtp.gmail.com',
            getenv('SMTP_PORT') ?? 587,
            getenv('SMTP_USERNAME'),
            getenv('SMTP_PASSWORD'),
            getenv('EMAIL_FROM') ?? 'noreply@techsolutionsddtp.cm',
            'Tech Solutions DDTP'
        );
    }
}

// ============================================
// 4. VARIABLES D'ENVIRONNEMENT (.env)
// ============================================

/*
// Créer fichier .env dans site-vitrine/:

// === SENDGRID (Recommandé) ===
EMAIL_SERVICE=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
EMAIL_FROM=contact@techsolutionsddtp.cm

// === SMTP CLASSIQUE (Alternative) ===
EMAIL_SERVICE=smtp
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=votre-email@gmail.com
SMTP_PASSWORD=votre-mot-de-passe-app

// === ALTERNATIVE : Mailgun ===
MAILGUN_API_KEY=...
MAILGUN_DOMAIN=...
*/

// ============================================
// 5. EXEMPLE D'UTILISATION
// ============================================

/*
$emailService = getEmailService();

// Envoyer email contact
$emailService->send(
    'contact@example.com',
    'John Doe',
    'Merci de nous avoir contacté',
    '<p>Bonjour John,</p><p>Nous avons reçu votre message...</p>',
    'Texte alternatif pour clients mail texte'
);

// Réponse automatique
$emailService->sendAutoReply('user@example.com', 'User', 'Question');

// Newsletter
$emailService->sendNewsletter(
    'subscriber@example.com',
    'Subscriber',
    'Actualités mai 2026',
    '<h2>Nouvelles fonctionnalités MBOA School 2.1</h2><p>...</p>'
);
*/

// ============================================
// 6. LOG D'EMAILS (pour audit)
// ============================================

function logEmailSent($toEmail, $subject, $status = 'sent') {
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }

    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'to_email' => $toEmail,
        'subject' => $subject,
        'status' => $status,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'cli'
    ];

    $logFile = 'logs/emails_' . date('Y-m-d') . '.json';
    $logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $logs[] = $logEntry;

    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

?>
