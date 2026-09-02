<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'code' => 'superadmin',
                'name' => 'Superadmin',
                'description' => 'Full system administrator with unrestricted access to all modules, branches, user management, and configuration.',
            ],
            [
                'code' => 'admin',
                'name' => 'Admin',
                'description' => 'Operational administrator responsible for daily branch management, master data, and operational reporting.',
            ],
            [
                'code' => 'qc',
                'name' => 'QC (Quality Control)',
                'description' => 'Quality Control officer managing raw sap inspection, brix level testing, moisture analysis, organic purity verification, and batch release.',
            ],
            [
                'code' => 'ics',
                'name' => 'ICS (Internal Control System)',
                'description' => 'Internal Control System officer overseeing organic certification standards, farmer compliance, farm traceability, and internal audits.',
            ],
            [
                'code' => 'expansi',
                'name' => 'Expansi (Expansion)',
                'description' => 'Expansion officer managing regional development, new farmer acquisition, palm tree cluster surveying, and land suitability assessments.',
            ],
            [
                'code' => 'csr',
                'name' => 'CSR (Corporate Social Responsibility)',
                'description' => 'CSR officer overseeing farmer community empowerment, welfare initiatives, training programs, and sustainability impact tracking.',
            ],
            [
                'code' => 'pk',
                'name' => 'PK',
                'description' => 'Farmer Group Facilitator providing daily field guidance, technical assistance, harvest logging, and organic practice supervision.',
            ],
            [
                'code' => 'pb',
                'name' => 'PB',
                'description' => 'Processing & Collection Unit managing village collection points, sap weighing, cooking/crystallization processes, and inventory handovers.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['code' => $roleData['code']],
                $roleData
            );
        }

        $this->command->info('✅ Roles seeded successfully: ' . implode(', ', array_column($roles, 'code')));
    }
}
