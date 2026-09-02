<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search   = $request->input('search');
        $roleId   = $request->input('role_id');
        $branchId = $request->input('branch_id');
        $status   = $request->input('status');

        $users = User::query()
            ->with(['role', 'branch'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('employee_code', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->when($roleId, fn ($q, $roleId) => $q->where('role_id', $roleId))
            ->when($branchId, fn ($q, $branchId) => $q->where('branch_id', $branchId))
            ->when($status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->get();

        $roles    = Role::select('id', 'name', 'code', 'description')->get();
        $branches = Branch::select('id', 'name', 'branch_code', 'city')->get();

        return Inertia::render('Users/Index', [
            'users'    => $users,
            'roles'    => $roles,
            'branches' => $branches,
            'filters'  => [
                'search'    => $search,
                'role_id'   => $roleId,
                'branch_id' => $branchId,
                'status'    => $status,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['nullable', 'string', 'max:30', 'unique:users,employee_code'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number'  => ['nullable', 'string', 'max:25'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'       => ['required', 'exists:roles,id'],
            'branch_id'     => ['nullable', 'exists:branches,id'],
            'gender'        => ['nullable', 'in:male,female'],
            'address'       => ['nullable', 'string'],
            'status'        => ['required', 'in:active,inactive,suspended'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', "User {$validated['name']} created successfully.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['nullable', 'string', 'max:30', 'unique:users,employee_code,' . $user->id],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number'  => ['nullable', 'string', 'max:25'],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'       => ['required', 'exists:roles,id'],
            'branch_id'     => ['nullable', 'exists:branches,id'],
            'gender'        => ['nullable', 'in:male,female'],
            'address'       => ['nullable', 'string'],
            'status'        => ['required', 'in:active,inactive,suspended'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', "User {$user->name} updated successfully.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user() && $request->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
