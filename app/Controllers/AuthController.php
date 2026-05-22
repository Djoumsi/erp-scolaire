<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 300; // 5 minutes

    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        $this->view('auth/login', ['pageTitle' => 'Connexion'], 'auth');
    }

    public function login(Request $request): void
    {
        $login    = trim($request->post('login', ''));
        $password = $request->post('password', '');

        if (empty($login) || empty($password)) {
            Session::flash('error', 'Identifiant et mot de passe obligatoires.');
            redirect('/login');
        }

        // Rate limiting
        $ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateKey = '_rl_' . md5($ip . $login);
        $count   = Session::get($rateKey . '_n', 0);
        $since   = Session::get($rateKey . '_t', 0);

        if ($count >= self::MAX_ATTEMPTS && (time() - $since) < self::LOCKOUT_TIME) {
            $wait = ceil((self::LOCKOUT_TIME - (time() - $since)) / 60);
            Logger::security('Login bloqué — trop de tentatives', ['login' => $login, 'ip' => $ip]);
            Session::flash('error', "Trop de tentatives échouées. Réessayez dans {$wait} minute(s).");
            redirect('/login');
        }

        // Réinitialiser le compteur si le blocage est expiré
        if ($count >= self::MAX_ATTEMPTS && (time() - $since) >= self::LOCKOUT_TIME) {
            Session::delete($rateKey . '_n');
            Session::delete($rateKey . '_t');
            $count = 0;
        }

        if (Auth::attempt($login, $password)) {
            Session::delete($rateKey . '_n');
            Session::delete($rateKey . '_t');
            Logger::info('Connexion réussie', ['login' => $login]);
            redirect('/dashboard');
        }

        $count++;
        Session::set($rateKey . '_n', $count);
        Session::set($rateKey . '_t', time());
        $restant = self::MAX_ATTEMPTS - $count;
        Logger::security('Échec de connexion', ['login' => $login, 'ip' => $ip, 'attempts' => $count]);

        $msg = 'Identifiant ou mot de passe incorrect.';
        if ($restant > 0 && $count >= 3) {
            $msg .= " ({$restant} tentative(s) restante(s) avant blocage)";
        }
        Session::flash('error', $msg);
        redirect('/login');
    }

    public function logout(Request $request): void
    {
        Logger::info('Déconnexion');
        Auth::logout();
        redirect('/login');
    }

    // -------------------------------------------------------
    // Réinitialisation mot de passe
    // -------------------------------------------------------

    public function showReset(Request $request): void
    {
        $this->view('auth/forgot-password', ['pageTitle' => 'Mot de passe oublié'], 'auth');
    }

    public function sendReset(Request $request): void
    {
        $login = trim($request->post('login', ''));

        if (empty($login)) {
            Session::flash('error', 'Veuillez saisir votre identifiant.');
            redirect('/mot-de-passe');
        }

        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT id, email, prenoms, nom FROM users WHERE login=? AND actif=1 AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        // Réponse générique pour ne pas révéler l'existence du login
        Session::flash('success', 'Si cet identifiant existe, un email de réinitialisation a été envoyé.');

        if (!$user || empty($user['email'])) {
            Logger::warning('Reset demandé pour login inconnu ou sans email', ['login' => $login]);
            redirect('/mot-de-passe');
        }

        // Créer/remplacer le token
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1h

        $db->prepare("DELETE FROM password_resets WHERE user_id=?")->execute([$user['id']]);
        $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?,?,?)")
           ->execute([$user['id'], hash('sha256', $token), $expires]);

        $resetUrl = url('/mot-de-passe/nouveau/' . $token);

        try {
            $this->sendResetEmail($user['email'], $user['prenoms'] . ' ' . $user['nom'], $resetUrl);
            Logger::info('Email reset envoyé', ['user_id' => $user['id']]);
        } catch (\Throwable $e) {
            Logger::error('Échec envoi email reset', ['error' => $e->getMessage(), 'user_id' => $user['id']]);
        }

        redirect('/mot-de-passe');
    }

    public function showNewPassword(Request $request): void
    {
        $token = $request->param('token');

        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT pr.*, u.login FROM password_resets pr JOIN users u ON u.id=pr.user_id WHERE pr.token=? AND pr.expires_at > NOW() AND pr.used=0 LIMIT 1");
        $stmt->execute([hash('sha256', $token)]);
        $reset = $stmt->fetch();

        if (!$reset) {
            Session::flash('error', 'Ce lien est invalide ou expiré. Veuillez refaire une demande.');
            redirect('/mot-de-passe');
        }

        $this->view('auth/reset-password', [
            'pageTitle' => 'Nouveau mot de passe',
            'token'     => $token,
        ], 'auth');
    }

    public function resetPassword(Request $request): void
    {
        $token    = trim($request->post('token', ''));
        $password = $request->post('nouveau_mdp', '');
        $confirm  = $request->post('confirmation_mdp', '');

        if (strlen($password) < 8) {
            Session::flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            redirect('/mot-de-passe/nouveau/' . $token);
        }

        if ($password !== $confirm) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            redirect('/mot-de-passe/nouveau/' . $token);
        }

        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token=? AND expires_at > NOW() AND used=0 LIMIT 1");
        $stmt->execute([hash('sha256', $token)]);
        $reset = $stmt->fetch();

        if (!$reset) {
            Session::flash('error', 'Lien invalide ou expiré.');
            redirect('/mot-de-passe');
        }

        $db->prepare("UPDATE users SET password=? WHERE id=?")->execute([Auth::hashPassword($password), $reset['user_id']]);
        $db->prepare("UPDATE password_resets SET used=1 WHERE id=?")->execute([$reset['id']]);

        Logger::info('Mot de passe réinitialisé', ['user_id' => $reset['user_id']]);
        Session::flash('success', 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
        redirect('/login');
    }

    // -------------------------------------------------------
    // Helper mail
    // -------------------------------------------------------

    private function sendResetEmail(string $to, string $name, string $url): void
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'localhost';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = (int) ($_ENV['MAIL_PORT'] ?? 587);
        $mail->CharSet    = 'UTF-8';

        $from = $_ENV['MAIL_USERNAME'] ?? 'noreply@erp-scolaire.local';
        $mail->setFrom($from, $_ENV['MAIL_FROM_NAME'] ?? 'ERP Scolaire');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe — ERP Scolaire';
        $mail->Body    = "
            <p>Bonjour <strong>{$name}</strong>,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p><a href='{$url}' style='background:#3b82f6;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;display:inline-block'>
                Réinitialiser mon mot de passe
            </a></p>
            <p>Ce lien est valable <strong>1 heure</strong>. Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
            <hr>
            <p style='color:#64748b;font-size:12px'>ERP Scolaire — Système de gestion scolaire</p>
        ";
        $mail->AltBody = "Réinitialisez votre mot de passe via ce lien (valable 1h) : {$url}";

        $mail->send();
    }
}
