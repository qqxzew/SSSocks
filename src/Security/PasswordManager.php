<?php
declare(strict_types=1);

namespace App\Security;

use Nette\Security\Passwords;

final class PasswordManager
{
    private Passwords $passwords;

    public function __construct()
    {
        $this->passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function hash(string $plainPassword): string
    {
    return $this->passwords->hash($plainPassword);
    }

    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return $this->passwords->verify($plainPassword, $hashedPassword);
    }
}