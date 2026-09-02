<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_roles_exist_in_database(): void
    {
        $expectedCodes = ['superadmin', 'admin', 'qc', 'ics', 'expansi', 'csr', 'pk', 'pb'];

        foreach ($expectedCodes as $code) {
            $this->assertDatabaseHas('roles', [
                'code' => $code,
            ]);
        }
    }

    public function test_superadmin_user_has_superadmin_role(): void
    {
        $superadmin = User::where('email', 'superadmin@coconutsugar.com')->first();

        $this->assertNotNull($superadmin);
        $this->assertNotNull($superadmin->role);
        $this->assertEquals('superadmin', $superadmin->role->code);
        $this->assertTrue($superadmin->hasRole('superadmin'));
    }
}
