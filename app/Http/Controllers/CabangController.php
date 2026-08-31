<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CabangController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $cabangs = Cabang::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_cabang', 'like', "%{$search}%")
                      ->orWhere('kode_cabang', 'like', "%{$search}%")
                      ->orWhere('no_badan_hukum', 'like', "%{$search}%")
                      ->orWhere('kota', 'like', "%{$search}%")
                      ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Cabang/Index', [
            'cabangs' => $cabangs,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_cabang'         => ['required', 'string', 'max:20', 'unique:cabangs,kode_cabang'],
            'nama_cabang'         => ['required', 'string', 'max:100'],
            'no_badan_hukum'      => ['nullable', 'string', 'max:100'],
            'tanggal_berdiri'     => ['nullable', 'date'],
            'alamat'              => ['required', 'string'],
            'kota'                => ['required', 'string', 'max:100'],
            'provinsi'            => ['nullable', 'string', 'max:100'],
            'kode_pos'            => ['nullable', 'string', 'max:10'],
            'latitude'            => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'           => ['nullable', 'numeric', 'between:-180,180'],
            'penanggung_jawab'    => ['required', 'string', 'max:100'],
            'telepon'             => ['required', 'string', 'max:25'],
            'email'               => ['nullable', 'email', 'max:100'],
            'status'              => ['required', 'in:aktif,nonaktif'],
            'kapasitas_harian_kg' => ['nullable', 'numeric', 'min:0'],
            'keterangan'          => ['nullable', 'string'],
        ]);

        Cabang::create($validated);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, Cabang $cabang): RedirectResponse
    {
        $validated = $request->validate([
            'kode_cabang'         => ['required', 'string', 'max:20', 'unique:cabangs,kode_cabang,' . $cabang->id],
            'nama_cabang'         => ['required', 'string', 'max:100'],
            'no_badan_hukum'      => ['nullable', 'string', 'max:100'],
            'tanggal_berdiri'     => ['nullable', 'date'],
            'alamat'              => ['required', 'string'],
            'kota'                => ['required', 'string', 'max:100'],
            'provinsi'            => ['nullable', 'string', 'max:100'],
            'kode_pos'            => ['nullable', 'string', 'max:10'],
            'latitude'            => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'           => ['nullable', 'numeric', 'between:-180,180'],
            'penanggung_jawab'    => ['required', 'string', 'max:100'],
            'telepon'             => ['required', 'string', 'max:25'],
            'email'               => ['nullable', 'email', 'max:100'],
            'status'              => ['required', 'in:aktif,nonaktif'],
            'kapasitas_harian_kg' => ['nullable', 'numeric', 'min:0'],
            'keterangan'          => ['nullable', 'string'],
        ]);

        $cabang->update($validated);

        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang): RedirectResponse
    {
        $cabang->delete();

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
