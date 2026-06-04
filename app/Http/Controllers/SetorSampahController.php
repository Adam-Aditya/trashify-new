<?php

namespace App\Http\Controllers;

use App\Models\SetorSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 🛠️ WAJIB DIAWALI IMPORT INI AGAR AUTH::ID() BERFUNGSI

class SetorSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🛠️ SINKRONISASI PRIVASI: Hanya mengambil data sampah milik user yang sedang login
        $data = SetorSampah::where('user_id', Auth::id())
                            ->latest()
                            ->get();

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
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'berat' => 'required|numeric',
        ]);

        // Menggunakan strtolower() agar aman jika value form bernilai huruf kecil maupun kapital
        $hargaPerKg = match (strtolower($request->jenis)) {
            'plastik' => 3000,
            'kertas'  => 5000,
            'logam'   => 6000,
            default   => 0
        };

        // Hitung total harga
        $totalHarga = $request->berat * $hargaPerKg;

        // Simpan data dengan mengikat id user yang sedang login
        SetorSampah::create([
            'user_id'   => Auth::id(), // 🛠️ KUNCI UTAMA: Mengunci kepemilikan data sampah
            'nama'      => $request->nama,
            'jenis'     => $request->jenis,
            'berat'     => $request->berat,
            'harga'     => $totalHarga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/dashboard')->with('success', 'Setor sampah berhasil dicatat!');
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
        // Memastikan user tidak bisa mengintip/mengedit data sampah milik user lain lewat URL id
        $data = SetorSampah::where('user_id', Auth::id())->findOrFail($id);

        return view('history.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'berat' => 'required|numeric',
        ]);

        // Memastikan data yang diupdate adalah mutlak milik user yang login
        $data = SetorSampah::where('user_id', Auth::id())->findOrFail($id);

        $hargaPerKg = match (strtolower($request->jenis)) {
            'plastik' => 3000,
            'kertas'  => 5000,
            'logam'   => 6000,
            default   => 0
        };

        $data->update([
            'nama'      => $request->nama,
            'jenis'     => $request->jenis,
            'berat'     => $request->berat,
            'harga'     => $request->berat * $hargaPerKg,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/history')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Memastikan data yang dihapus adalah mutlak milik user yang login
        $data = SetorSampah::where('user_id', Auth::id())->findOrFail($id);
        $data->delete();

        return redirect('/history')->with('success', 'Data berhasil dihapus');
    }
}