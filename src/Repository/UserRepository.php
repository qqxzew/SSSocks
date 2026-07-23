<?php
declare(strict_types=1);

namespace App\Repository;

use Nette\Database\Connection;
use Nette\Database\Row;

final class UserRepository
{
    public function __construct(
        private Connection $db
    ) {}

    public function findByEmail(string $email): ?Row
    {
        $fetch = $this->db->query('SELECT * FROM users WHERE email = ?', $email)->fetch();
        return $fetch;
    }
}