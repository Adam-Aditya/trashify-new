<?php

namespace App\Http\Controllers;

use App\Models\Pengepul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthPengepulController extends Controller
{
    public function showForm() {
        return view('auth.login-signup-pengepul');
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:pengepuls,username',
            'email'    => 'required|email|unique:pengepuls,email',
            'password' => 'required|min:6',
            'phone'    => 'required',
        ]);

        $pengepul = Pengepul::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
        ]);

        // Login menggunakan guard pengepul
        Auth::guard('pengepul')->login($pengepul);

        return response()->json(['status' => 'success', 'redirect' => route('pengepul.toko')]);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek login via username atau email
        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('pengepul')->attempt([$field => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            // DIUBAH: Jika sudah ada nama toko, arahkan menggunakan named route dashboard pengepul
            if (Auth::guard('pengepul')->user()->nama_toko) {
                return response()->json(['status' => 'success', 'redirect' => route('pengepul.dashboard')]);
            }
            return response()->json(['status' => 'success', 'redirect' => route('pengepul.toko')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Username atau password salah.']);
    }

    public function showKategoriToko() {
        return view('auth.kategori-toko');
    }

    public function simpanKategoriToko(Request $request) {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'kategori'  => 'required|array|min:1'
        ]);

        /** @var \App\Models\Pengepul $pengepul */
        $pengepul = Auth::guard('pengepul')->user();
        
        $pengepul->update([
            'nama_toko' => $request->nama_toko,
            'kategori_sampah' => implode(',', $request->kategori) // disimpan berupa string koma: plastik,kertas
        ]);

        // DIUBAH: Setelah simpan, alihkan resmi ke named route dashboard pengepul
        return redirect()->route('pengepul.dashboard')->with('success', 'Profil Toko Berhasil Disimpan!');
    }

    public function showDashboard() {
        return view('dashboard-pengepul');
    }

    public function toggleStatus(Request $request) {
        $request->validate([
            'is_buka' => 'required|in:0,1'
        ]);

        /** @var \App\Models\Pengepul $pengepul */
        $pengepul = Auth::guard('pengepul')->user();
        
        $pengepul->update([
            'is_buka' => $request->is_buka
        ]);

        return response()->json(['status' => 'success']);
    }
}