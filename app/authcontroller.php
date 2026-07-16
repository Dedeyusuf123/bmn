<?php
require_once __DIR__ . '/../helpers.php';

class AuthController
{
    public function login(): void
    {
        if (!empty($_SESSION['login'])) {
            header('Location: ' . home_url());
            exit;
        }

        require_once __DIR__ . '/../view/login.php';
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: ' . route_url());
        exit;
    }
}
