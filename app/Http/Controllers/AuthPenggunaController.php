<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'poin'     => 0, // Nilai awal poin diset 0
        ]);

        // AUTOMATIS LOGIN: Setelah daftar berhasil, langsung buat sesi login untuk user ini
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

    // 4. MENAMPILKAN HALAMAN PROFIL
    public function showProfil()
    {
        $user = Auth::user(); 
        return view('profil', compact('user')); 
    }

    // 5. PROSES UPDATE DATA PROFIL SECARA DINAMIS
    public function updateProfil(Request $request)
    {
        $request->validate([
            'field' => 'required|in:username,email,password,phone,location',
            'value' => 'required'
        ]);

        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();
        $field = $request->field;
        $value = $request->value;

        // Validasi tambahan agar data unik
        if ($field === 'username' && $value !== $user->username) {
            $request->validate(['value' => 'unique:penggunas,username']);
        }
        if ($field === 'email' && $value !== $user->email) {
            $request->validate(['value' => 'email|unique:penggunas,email']);
        }

        // Jika mengubah password, lakukan enkripsi (Hash)
        if ($field === 'password') {
            if (strlen($value) < 6) {
                return back()->withErrors(['updateError' => 'Password minimal harus 6 karakter.']);
            }
            $value = Hash::make($value);
        }

        // Update data pengguna ke database
        $user->update([
            $field => $value
        ]);

        // Mengembalikan rute dengan membawa session success dinamis (Contoh: "Data Location berhasil diperbarui!")
        return redirect()->back()->with('success', 'Data ' . ucfirst($field) . ' berhasil diperbarui!');
    }

    // 6. MENAMPILKAN HALAMAN TUKAR POIN
    public function showTukarPoin()
    {
        $poin = Auth::user()->poin ?? 0; 
        return view('tukar-poin', compact('poin'));
    }

    // 7. MEMPROSES TRANSAKSI PENUKARAN POIN
    public function prosesTukarPoin(Request $request)
    {
        $request->validate([
            'no_hp'    => 'required',
            'provider' => 'required',
            'nominal'  => 'required|numeric|min:1',
        ]);

        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();
        $nominal = (int) $request->nominal;

        // Validasi kecukupan poin
        if ($user->poin < $nominal) {
            return back()->withErrors(['tukarError' => 'Poin Anda tidak mencukupi untuk penukaran ini.'])->withInput();
        }

        // Menggunakan Database Transaction agar jika salah satu query gagal, data otomatis aman (Rollback)
        DB::transaction(function () use ($user, $request, $nominal) {
            // Kurangi poin pengguna di tabel penggunas
            $user->decrement('poin', $nominal);

            // Catat data ke tabel transaksi_tukar
            DB::table('transaksi_tukar')->insert([
                'user_id'    => $user->id,
                'provider'   => $request->provider,
                'nominal'    => $nominal,
                'no_hp'      => $request->no_hp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Penukaran poin sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil!');
    }
    public function showDashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        // 1. Koordinat lokasi user
        $userLoc = explode(',', $user->location ?? '0,0');
        $userLat = isset($userLoc[0]) ? (float)$userLoc[0] : 0;
        $userLng = isset($userLoc[1]) ? (float)$userLoc[1] : 0;

        // 2. Ambil data pengepul terdekat yang BUKA (Rumus Haversine)
        $daftarToko = DB::table('pengepuls')
            ->where('is_buka', 1)
            ->select('*')
            ->selectRaw(
                "( 6371 * acos( cos( radians(?) ) * cos( radians( SUBSTRING_INDEX(location, ',', 1) ) ) * cos( radians( SUBSTRING_INDEX(location, ',', -1) ) - radians(?) ) + sin( radians(?) ) * sin( radians( SUBSTRING_INDEX(location, ',', 1) ) ) ) ) AS jarak", 
                [$userLat, $userLng, $userLat]
            )
            ->orderBy('jarak', 'asc')
            ->get();

        // 3. HITUNG METRIK RINGKASAN TRANSAKSI USER DARI DATABASE
        $userTotalSampah = DB::table('setor_sampahs')
            ->where('user_id', $userId)
            ->where('status', 'selesai_transaksi')
            ->sum('berat');

        $userTotalPoin = DB::table('setor_sampahs')
            ->where('user_id', $userId)
            ->where('status', 'selesai_transaksi')
            ->sum('harga');

        $userTotalTransaksi = DB::table('setor_sampahs')
            ->where('user_id', $userId)
            ->count();

        $userTransaksiDisetujui = DB::table('setor_sampahs')
            ->where('user_id', $userId)
            ->where('status', 'selesai_transaksi')
            ->count();

        // 4. DATA BARU: Tarik data tren kontribusi harian selama 7 hari terakhir
        $userTrenHarian = DB::table('setor_sampahs')
            ->where('user_id', $userId)
            ->where('status', 'selesai_transaksi')
            ->selectRaw("DATE_FORMAT(updated_at, '%d %b') as tanggal, SUM(harga) as total_poin, SUM(berat) as total_berat")
            ->groupBy('tanggal')
            ->orderBy(DB::raw("MIN(updated_at)"), 'asc')
            ->take(7)
            ->get();

        // Ekstrak data tren ke bentuk array untuk Chart.js
        $userChartLabels = $userTrenHarian->pluck('tanggal')->toArray();
        $userChartPoin = $userTrenHarian->pluck('total_poin')->map(function($item) { return (int)$item; })->toArray();
        $userChartBerat = $userTrenHarian->pluck('total_berat')->map(function($item) { return (float)$item; })->toArray();

        // Kirim semua variabel ke view dashboard pengguna
        return view('dashboard', compact(
            'daftarToko', 
            'userTotalSampah', 
            'userTotalPoin', 
            'userTotalTransaksi', 
            'userTransaksiDisetujui',
            'userChartLabels', 
            'userChartPoin',   
            'userChartBerat'   
        ));
    }
}