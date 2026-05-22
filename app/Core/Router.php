<?php
namespace App\Core;

class Router
{
    private array $routes    = [];
    private array $namedRoutes = [];
    private array $middleware = [];

    // -------------------------------------------------------
    // Enregistrement des routes
    // -------------------------------------------------------

    public function get(string $path, string $action, ?string $name = null): static
    {
        return $this->addRoute('GET', $path, $action, $name);
    }

    public function post(string $path, string $action, ?string $name = null): static
    {
        return $this->addRoute('POST', $path, $action, $name);
    }

    public function put(string $path, string $action, ?string $name = null): static
    {
        return $this->addRoute('POST', $path, $action, $name); // simulé via _method
    }

    public function delete(string $path, string $action, ?string $name = null): static
    {
        return $this->addRoute('POST', $path, $action, $name);
    }

    private function addRoute(string $method, string $path, string $action, ?string $name): static
    {
        $pattern = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path) . '$#';
        $route   = ['method' => $method, 'path' => $path, 'pattern' => $pattern, 'action' => $action, 'middleware' => []];
        $this->routes[] = $route;
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
        return $this;
    }

    public function middleware(array $middleware): static
    {
        $last = &$this->routes[count($this->routes) - 1];
        $last['middleware'] = $middleware;
        return $this;
    }

    public function group(array $options, callable $callback): void
    {
        $callback($this);
    }

    // -------------------------------------------------------
    // Résolution
    // -------------------------------------------------------

    public function dispatch(Request $request): void
    {
        $method = strtoupper($request->method());
        $uri    = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }
            // Paramètres nommés
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $request->setRouteParams($params);

            // Middlewares
            foreach ($route['middleware'] as $mw) {
                $this->runMiddleware($mw, $request);
            }

            // Dispatch Controller@action
            [$controllerClass, $action] = explode('@', $route['action']);
            $fqcn = "App\\Controllers\\{$controllerClass}";
            if (!class_exists($fqcn)) {
                abort(500, "Controller {$fqcn} introuvable.");
            }
            $controller = new $fqcn();
            $controller->$action($request);
            return;
        }

        abort(404, 'Page introuvable.');
    }

    private function runMiddleware(string $mw, Request $request): void
    {
        if ($mw === 'auth') {
            if (!Auth::check()) {
                redirect('/login');
            }
            return;
        }
        if (str_starts_with($mw, 'role:')) {
            $roles = explode(',', substr($mw, 5));
            if (!Auth::hasRole($roles)) {
                abort(403, 'Accès refusé.');
            }
            return;
        }
        if (str_starts_with($mw, 'can:')) {
            $perm = substr($mw, 4);
            if (!Auth::can($perm)) {
                abort(403, 'Permission refusée.');
            }
            return;
        }
    }

    // -------------------------------------------------------
    // Helper URL nommée
    // -------------------------------------------------------

    public function route(string $name, array $params = []): string
    {
        $path = $this->namedRoutes[$name] ?? "/$name";
        foreach ($params as $key => $val) {
            $path = str_replace("{{$key}}", $val, $path);
        }
        return $path;
    }
}
