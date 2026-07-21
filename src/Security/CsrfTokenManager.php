<?php
declare(strict_types=1);

namespace App\Security;

use Nette\Http\Session;
final class CsrfTokenManager
{
    private const SESSION_SECTION = 'csrf';
    private const SESSION_KEY = 'token';

    public function __construct(
        private Session $session
    ) {}

    public function getOrCreateToken(): string
    {
        $section = $this->session->getSection(self::SESSION_SECTION);
        if (!isset($section[self::SESSION_KEY])) {
            $section[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $section[self::SESSION_KEY];
    }

    public function validateToken(?string $providedToken): bool
    {
        if ($providedToken === null) {
            return false;
        }
        return hash_equals($this->getOrCreateToken(), $providedToken);
    }


}