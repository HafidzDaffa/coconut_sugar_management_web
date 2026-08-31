<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@coconutsugar.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@coconutsugar.com',
                'password' => Hash::make('password'),
                'role'     => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Superadmin created: superadmin@coconutsugar.com / password');
    }
}
