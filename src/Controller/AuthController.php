<?php
declare(strict_types=1);

namespace App\Controller;

use Nette\Security\User;
use Nette\Security\AuthenticationException;

final class AuthController
{
    public function __construct(
        private User $user
    ) {}

    public function login(): void
    {
        header('Content-type: application/json');

        $data = json_decode((string) file_get_contents('php://input'), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!is_string($email) || !is_string($password)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            return;
        }

        try {
            $this->user->login($email, $password);
            http_response_code(200);
            echo json_encode(['status' => 'logged_in', 'is_admin' => $this->user->isInRole('admin')]);
        } catch (AuthenticationException $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }

    }
}