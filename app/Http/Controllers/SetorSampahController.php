<?php

namespace App\Http\Controllers;

use App\Models\SetorSampah;
use Illuminate\Http\Request;

class SetorSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = \App\Models\SetorSampah::latest()->get();
        return view('history.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hargaPerKg = match ($request->jenis) {
            'plastik' => 3000,
            'kertas' => 5000,
            'logam' => 6000,
        };

        // Hitung total harga
        $totalHarga = $request->berat * $hargaPerKg;

        SetorSampah::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $totalHarga,
            'deskripsi' => $request->deskripsi,
        ]);
        return redirect('/dashboard')->with('success', 'Setor berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SetorSampah $setorSampah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = \App\Models\SetorSampah::findOrFail($id);
        return view('history.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = \App\Models\SetorSampah::findOrFail($id);

        $hargaPerKg = match ($request->jenis) {
            'plastik' => 3000,
            'kertas' => 5000,
            'logam' => 6000,
        };

        $data->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $request->berat * $hargaPerKg,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/history')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        \App\Models\SetorSampah::destroy($id);
        return redirect('/history')->with('success', 'Data berhasil dihapus');
    }
}
