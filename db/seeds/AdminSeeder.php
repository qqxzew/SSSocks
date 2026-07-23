<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class AdminSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'email' =>'admin@gmail.com',
                'password_hash' => password_hash('admin', PASSWORD_BCRYPT, ['cost' => 12]),
                'is_admin' => 1
            ]
        ];
        $this->table('users')->insert($data)->saveData();
    }
}