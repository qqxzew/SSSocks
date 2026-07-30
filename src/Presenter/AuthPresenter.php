<?php
declare(strict_types=1);

namespace App\Presenter;

use Nette\Security\AuthenticationException;
use Nette\Security\User;
use App\Security\CsrfTokenManager;
use Nette\Application\UI\Presenter;
final class AuthPresenter extends Presenter
{
    public function __construct(
        private User $user,
        private CsrfTokenManager $csrfManager
    ) {}
    public function actionLogin(): void
    {
        $data = json_decode((string) file_get_contents('php://input'), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!is_string($email) || !is_string($password)) {
            $this->getHttpResponse()->setCode(400);
            $this->sendJson(['error' => 'Email and password are required']);
        }

        try {
            $this->user->login($email, $password);
            $this->getHttpResponse()->setCode(200);
            $this->sendJson(['status' => 'logged_in', 'is_admin' => $this->user->isInRole('admin')]);
        } catch (AuthenticationException $e) {
            $this->getHttpResponse()->setCode(401);
            $this->sendJson(['error' => 'Invalid credentials']);
        }

    }
    public function actionLogout(): void
    {
        $token = $this->getHttpRequest()->getHeader('X-CSRF-Token');

        if ($this->csrfManager->validateToken($token) === false) {
            $this->getHttpResponse()->setCode(403);
            $this->sendJson(['error' => 'Invalid CSRF token']);
        }

        $this->user->logout(clearIdentity: true);
        $this->getHttpResponse()->setCode(200);
        $this->sendJson(['status' => 'logged_out']);
    }

}