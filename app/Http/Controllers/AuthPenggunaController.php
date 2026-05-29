<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthPenggunaController extends Controller
{
    public function showForm()
    {
        return view('auth.log-register-pengguna');
    }

    // 1. PROSES SIGN UP (DAFTAR)
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:penggunas,username|max:255',
            'email'    => 'required|email|unique:penggunas,email',
            'password' => 'required|min:6',
            'phone'    => 'required',
        ]);

        // Simpan data pengguna baru ke database
        $user = Pengguna::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
        ]);

        // OTOMATIS LOGIN: Setelah daftar berhasil, langsung buat sesi login untuk user ini
        Auth::login($user);

        // Alihkan (Redirect) langsung ke halaman dashboard
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat dan Anda otomatis masuk!');
    }

    // 2. PROSES LOG IN
    public function login(Request $request)
    {
        // 1. Validasi Input Form
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Proses Pengecekan Login ke Database 'new_trashify'
        if (Auth::attempt($credentials)) {
            // Jika sukses cocok, amankan session pengguna
            $request->session()->regenerate();
            
            // ALIKHAN KE DASHBOARD TERBARU KAMU
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali!'); 
        }

        // 3. Jika gagal cocok, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'loginError' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    // 3. PROSES LOG OUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing'); // Kembali ke landing page setelah logout
    }

    // 4. TAMPILKAN PROFIL (Baru dimasukkan ke sini)
    public function showProfil()
    {
        $user = Auth::user(); // Ambil data pengguna yang sedang login
        return view('profil', compact('user')); // Memanggil resources/views/profil.blade.php
    }

    // 5. PROSES UPDATE DATA PROFILsecara Fleksibel (Baru dimasukkan ke sini)
    public function updateProfil(Request $request)
    {
        $request->validate([
            'field' => 'required|in:username,email,password,phone,location',
            'value' => 'required'
        ]);

        $user = Auth::user();
        $field = $request->field;
        $value = $request->value;

        // Validasi tambahan agar tidak kembar dengan pengguna lain
        if ($field === 'username' && $value !== $user->username) {
            $request->validate(['value' => 'unique:penggunas,username']);
        }
        if ($field === 'email' && $value !== $user->email) {
            $request->validate(['value' => 'email|unique:penggunas,email']);
        }

        // Jika mengubah password, enkripsi datanya terlebih dahulu
        if ($field === 'password') {
            if (strlen($value) < 6) {
                return back()->withErrors(['updateError' => 'Password minimal harus 6 karakter.']);
            }
            $value = Hash::make($value);
        }

        // Eksekusi update data ke database 'new_trashify'
        $user->update([
            $field => $value
        ]);

        return redirect()->route('profil.show')->with('success', 'Data ' . ucfirst($field) . ' berhasil diperbarui!');
    }
}