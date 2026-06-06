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
            
            // Jika sudah ada nama toko, arahkan menggunakan named route dashboard pengepul
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

        return redirect()->route('pengepul.dashboard')->with('success', 'Profil Toko Berhasil Disimpan!');
    }

    public function showDashboard() 
    {
        // 1. Ambil ID Pengepul yang sedang login aktif
        $pengepulId = Auth::guard('pengepul')->id();

        // 2. Ambil data setoran berstatus 'pending' DAN 'diterima' (Dalam Penjemputan)
        $setoranMasuk = \App\Models\SetorSampah::with('user')
                        ->where('pengepul_id', $pengepulId)
                        ->whereIn('status', ['pending', 'diterima']) // Menggunakan whereIn agar status penjemputan tidak hilang dari dashboard
                        ->latest()
                        ->get();

        // 3. Pastikan variabel 'setoranMasuk' dikirim ke view dashboard pengepul
        return view('dashboard-pengepul', compact('setoranMasuk'));
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

    public function showProfil() {
        return view('profil-pengepul');
    }

    public function updateProfil(Request $request) {
        $request->validate([
            'field' => 'required|in:username,email,password,phone,location,nama_toko,kategori_sampah',
            'value' => 'required'
        ]);

        /** @var \App\Models\Pengepul $pengepul */
        $pengepul = Auth::guard('pengepul')->user();
        $field = $request->field;
        $value = $request->value;

        // Validasi unik jika mengubah username/email
        if ($field === 'username' && $value !== $pengepul->username) {
            $request->validate(['value' => 'unique:pengepuls,username']);
        }
        if ($field === 'email' && $value !== $pengepul->email) {
            $request->validate(['value' => 'email|unique:pengepuls,email']);
        }
        if ($field === 'password') {
            $request->validate(['value' => 'min:6']);
            $value = Hash::make($value);
        }

        // Eksekusi update ke database
        $pengepul->update([
            $field => $value
        ]);

        return redirect()->route('pengepul.profil')->with('success', 'Informasi lapak berhasil diperbarui!');
    }

    // Fungsi untuk menampilkan halaman form top up poin
    public function showTopupForm() 
    {
        return view('topup-poin');
    }

    // Fungsi untuk memproses data saldo top up masuk
    public function prosesTopup(Request $request)
    {
        $request->validate([
            'nomor_rekening' => 'required|string',
            'metode'         => 'required|string',
            'nominal'        => 'required|numeric|min:50000'
        ]);

        // 1. Ambil data pengepul yang sedang login
        $userLogin = Auth::guard('pengepul')->user();
        $jumlahTopup = (int) $request->nominal;

        // 2. AMBIL MODEL ASLI DARI DATABASE (Agar bisa di-save tanpa eror)
        $pengepul = \App\Models\Pengepul::find($userLogin->id);

        // 3. Hitung dan masukkan poin baru
        $pengepul->poin = ($pengepul->poin ?? 0) + $jumlahTopup;

        // 4. Simpan ke Database
        $pengepul->save(); 

        // 5. Kembali ke halaman dengan notifikasi sukses
        $poinFormat = call_user_func('number_format', $jumlahTopup, 0, ',', '.');

        return redirect()->back()->with(
            'success', 
            'Selamat! Top up saldo sebesar ' . $poinFormat . ' Poin berhasil ditambahkan ke akun Anda.'
        );
    }

    /**
     * Fungsi resmi untuk mengeluarkan sesi login Pengepul
     */
    public function logout(Request $request) 
    {
        Auth::guard('pengepul')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Alihkan kembali ke halaman utama login/register pengepul
        return redirect()->route('landing')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}