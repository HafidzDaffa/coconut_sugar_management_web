<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected Role $adminRole;
    protected Branch $testBranch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superadmin = User::where('email', 'superadmin@coconutsugar.com')->first();
        $this->adminRole  = Role::where('code', 'admin')->first();
        $this->testBranch = Branch::create([
            'branch_code'      => 'BR-TEST-01',
            'name'             => 'Test Branch Central',
            'address'          => 'Jl. Test No. 123',
            'city'             => 'Purwokerto',
            'person_in_charge' => 'Budi Santoso',
            'phone'            => '08123456789',
            'status'           => 'active',
        ]);
    }

    public function test_guest_cannot_access_user_management(): void
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_users_list(): void
    {
        $response = $this->actingAs($this->superadmin)->get('/users');

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => $page
                     ->component('Users/Index')
                     ->has('users')
                     ->has('roles')
                     ->has('branches')
                     ->has('filters')
                 );
    }

    public function test_can_create_a_new_user(): void
    {
        $payload = [
            'employee_code'         => 'EMP-100',
            'name'                  => 'Ahmad Staff',
            'email'                 => 'ahmad@coconutsugar.com',
            'phone_number'          => '081299887766',
            'password'              => 'secretpassword123',
            'password_confirmation' => 'secretpassword123',
            'role_id'               => $this->adminRole->id,
            'branch_id'             => $this->testBranch->id,
            'gender'                => 'male',
            'address'               => 'Jl. Jenderal Sudirman No. 45',
            'status'                => 'active',
        ];

        $response = $this->actingAs($this->superadmin)->post('/users', $payload);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'employee_code' => 'EMP-100',
            'name'          => 'Ahmad Staff',
            'email'         => 'ahmad@coconutsugar.com',
            'role_id'       => $this->adminRole->id,
            'branch_id'     => $this->testBranch->id,
            'status'        => 'active',
        ]);
    }

    public function test_user_creation_fails_when_password_confirmation_mismatches(): void
    {
        $response = $this->actingAs($this->superadmin)->post('/users', [
            'name'                  => 'Test Mismatch',
            'email'                 => 'mismatch@coconutsugar.com',
            'password'              => 'password123',
            'password_confirmation' => 'differentpassword123',
            'role_id'               => $this->adminRole->id,
            'status'                => 'active',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_user_creation_validation_errors(): void
    {
        $response = $this->actingAs($this->superadmin)->post('/users', [
            'name'     => '',
            'email'    => 'invalid-email',
            'password' => 'short',
            'role_id'  => 99999, // non-existent
            'status'   => 'invalid-status',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role_id', 'status']);
    }

    public function test_can_update_an_existing_user(): void
    {
        $user = User::create([
            'employee_code' => 'EMP-200',
            'name'          => 'Original Name',
            'email'         => 'original@coconutsugar.com',
            'password'      => 'password123',
            'role_id'       => $this->adminRole->id,
            'status'        => 'active',
        ]);

        $updatePayload = [
            'employee_code'         => 'EMP-200-EDITED',
            'name'                  => 'Updated Name',
            'email'                 => 'updated@coconutsugar.com',
            'phone_number'          => '081122334455',
            'password'              => '', // keep old password
            'password_confirmation' => '',
            'role_id'               => $this->adminRole->id,
            'branch_id'             => $this->testBranch->id,
            'gender'                => 'female',
            'address'               => 'Jl. Pemuda No. 10',
            'status'                => 'inactive',
        ];

        $response = $this->actingAs($this->superadmin)->put("/users/{$user->id}", $updatePayload);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id'            => $user->id,
            'employee_code' => 'EMP-200-EDITED',
            'name'          => 'Updated Name',
            'email'         => 'updated@coconutsugar.com',
            'status'        => 'inactive',
        ]);
    }

    public function test_can_update_user_password_with_confirmation(): void
    {
        $user = User::create([
            'employee_code' => 'EMP-201',
            'name'          => 'Password Change Test',
            'email'         => 'pwdchange@coconutsugar.com',
            'password'      => 'oldpassword123',
            'role_id'       => $this->adminRole->id,
            'status'        => 'active',
        ]);

        $response = $this->actingAs($this->superadmin)->put("/users/{$user->id}", [
            'name'                  => 'Password Change Test',
            'email'                 => 'pwdchange@coconutsugar.com',
            'password'              => 'brandnewpassword123',
            'password_confirmation' => 'brandnewpassword123',
            'role_id'               => $this->adminRole->id,
            'status'                => 'active',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success');
    }

    public function test_can_delete_a_user(): void
    {
        $user = User::create([
            'employee_code' => 'EMP-300',
            'name'          => 'Delete Me',
            'email'         => 'deleteme@coconutsugar.com',
            'password'      => 'password123',
            'role_id'       => $this->adminRole->id,
            'status'        => 'active',
        ]);

        $response = $this->actingAs($this->superadmin)->delete("/users/{$user->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->superadmin)->delete("/users/{$this->superadmin->id}");

        $response->assertRedirect('/users');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id'         => $this->superadmin->id,
            'deleted_at' => null,
        ]);
    }

    public function test_can_filter_users_by_search_query(): void
    {
        User::create([
            'name'     => 'Special Unique Person',
            'email'    => 'special@coconutsugar.com',
            'password' => 'password123',
            'role_id'  => $this->adminRole->id,
            'status'   => 'active',
        ]);

        $response = $this->actingAs($this->superadmin)->get('/users?search=Unique');

        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => $page
                     ->has('users', 1)
                     ->where('users.0.name', 'Special Unique Person')
                 );
    }
}
