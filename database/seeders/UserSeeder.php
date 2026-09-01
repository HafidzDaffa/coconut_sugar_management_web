<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = Role::where('code', 'superadmin')->first();

        User::updateOrCreate(
            ['email' => 'superadmin@coconutsugar.com'],
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@coconutsugar.com',
                'password'          => Hash::make('password'),
                'role_id'           => $superadminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Superadmin user ready: superadmin@coconutsugar.com / password');
    }
}
