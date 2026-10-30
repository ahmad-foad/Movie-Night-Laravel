<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['username' => 'sara', 'email' => 'sara@example.com'],
            ['username' => 'karwan', 'email' => 'karwan@example.com'],
            ['username' => 'lana', 'email' => 'lana@example.com'],
        ] as $sample) {
            $user = User::firstOrNew(['email' => $sample['email']]);
            $user->username = $sample['username'];
            // Demo password only. The "hashed" cast on the User model hashes it before saving.
            $user->password = 'password';
            $user->save();
        }
    }
}
