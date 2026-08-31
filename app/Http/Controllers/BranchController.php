<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $branches = Branch::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('branch_code', 'like', "%{$search}%")
                      ->orWhere('legal_entity_number', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%")
                      ->orWhere('person_in_charge', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Branches/Index', [
            'branches' => $branches,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branch_code'         => ['required', 'string', 'max:20', 'unique:branches,branch_code'],
            'name'                => ['required', 'string', 'max:100'],
            'legal_entity_number' => ['nullable', 'string', 'max:100'],
            'established_date'    => ['nullable', 'date'],
            'address'             => ['required', 'string'],
            'city'                => ['required', 'string', 'max:100'],
            'province'            => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:10'],
            'latitude'            => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'           => ['nullable', 'numeric', 'between:-180,180'],
            'person_in_charge'    => ['required', 'string', 'max:100'],
            'phone'               => ['required', 'string', 'max:25'],
            'email'               => ['nullable', 'email', 'max:100'],
            'status'              => ['required', 'in:active,inactive'],
            'daily_capacity_kg'   => ['nullable', 'numeric', 'min:0'],
            'notes'               => ['nullable', 'string'],
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'branch_code'         => ['required', 'string', 'max:20', 'unique:branches,branch_code,' . $branch->id],
            'name'                => ['required', 'string', 'max:100'],
            'legal_entity_number' => ['nullable', 'string', 'max:100'],
            'established_date'    => ['nullable', 'date'],
            'address'             => ['required', 'string'],
            'city'                => ['required', 'string', 'max:100'],
            'province'            => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:10'],
            'latitude'            => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'           => ['nullable', 'numeric', 'between:-180,180'],
            'person_in_charge'    => ['required', 'string', 'max:100'],
            'phone'               => ['required', 'string', 'max:25'],
            'email'               => ['nullable', 'email', 'max:100'],
            'status'              => ['required', 'in:active,inactive'],
            'daily_capacity_kg'   => ['nullable', 'numeric', 'min:0'],
            'notes'               => ['nullable', 'string'],
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
