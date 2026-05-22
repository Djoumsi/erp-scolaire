<?php
namespace App\Core;

class View
{
    private string $layout = 'app';
    private array  $data   = [];

    public static function render(string $template, array $data = [], string $layout = 'app'): void
    {
        $view = new self();
        $view->layout = $layout;
        $view->data   = $data;
        $view->display($template);
    }

    public static function renderPartial(string $template, array $data = []): string
    {
        extract($data);
        ob_start();
        $file = BASE_PATH . "/app/Views/{$template}.php";
        if (!file_exists($file)) {
            throw new \RuntimeException("Vue introuvable : {$template}");
        }
        include $file;
        return ob_get_clean();
    }

    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function display(string $template): void
    {
        extract($this->data);
        ob_start();
        $file = BASE_PATH . "/app/Views/{$template}.php";
        if (!file_exists($file)) {
            ob_end_clean();
            throw new \RuntimeException("Vue introuvable : {$template}");
        }
        try {
            include $file;
            $content = ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            // Rethrow pour que l'erreur soit affichée proprement
            throw $e;
        }

        $layoutFile = BASE_PATH . "/app/Views/layouts/{$this->layout}.php";
        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }
        include $layoutFile;
    }
}
