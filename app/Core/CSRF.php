<?php
namespace App\Core;

class CSRF
{
    public static function token(): string
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }

    public static function verify(string $token): bool
    {
        $stored = Session::get('csrf_token', '');
        return hash_equals($stored, $token);
    }

    public static function check(Request $request): void
    {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            $token = $request->post('_token', '');
            if (!self::verify($token)) {
                abort(419, 'Token CSRF invalide. Veuillez recharger la page.');
            }
        }
    }
}
