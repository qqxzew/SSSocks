<?php
declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Nette\Security\Authenticator as NetteAuthenticator;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;

final class Authenticator implements NetteAuthenticator
{
    public function __construct(
        private UserRepository $users,
        private PasswordManager $passwords
    ) {}

    public function authenticate(string $user, string $password): IIdentity
    {
        $row = $this->users->findByEmail($user);

        if ($row === null || !$this->passwords->verify($password, $row->password_hash)) {
            throw new AuthenticationException('Wrong email or password.');
        }

        $roles = $row->is_admin ? ['admin'] : ['user'];
        return new SimpleIdentity($row->id, $roles, ['email' => $row->email]);
    }
}