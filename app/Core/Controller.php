<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'app'): void
    {
        View::render($template, $data, $layout);
    }

    protected function json(mixed $data, int $status = 200): void
    {
        View::json($data, $status);
    }

    protected function redirect(string $url): void
    {
        redirect($url);
    }

    protected function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
        $this->redirect($ref);
    }

    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            redirect('/login');
        }
    }

    protected function requirePermission(string $perm): void
    {
        $this->requireAuth();
        if (Auth::cannot($perm)) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }
    }

    protected function validate(array $data, array $rules): array
    {
        return Validator::validate($data, $rules);
    }

    protected function success(string $message, ?string $redirect = null): void
    {
        Session::flash('success', $message);
        if ($redirect) {
            $this->redirect($redirect);
        }
    }

    protected function error(string $message, ?string $redirect = null): void
    {
        Session::flash('error', $message);
        if ($redirect) {
            $this->redirect($redirect);
        }
    }

    protected function currentUser(): ?array
    {
        return Auth::user();
    }

    protected function etablissementId(): ?int
    {
        return Auth::etablissementId();
    }
}
