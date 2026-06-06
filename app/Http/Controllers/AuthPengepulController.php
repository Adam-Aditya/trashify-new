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
        $pengepulId = Auth::guard('pengepul')->id();

        // 1. Antrean tabel depan
        $setoranMasuk = \App\Models\SetorSampah::with('user')
                        ->where('pengepul_id', $pengepulId)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->latest()
                        ->get();

        // 2. Metrik Ringkasan
        $totalBerat = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->whereIn('status', ['diterima', 'selesai_transaksi'])
                        ->sum('berat');

        $totalPenjemputan = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->whereIn('status', ['selesai_transaksi', 'ditolak'])
                        ->count();

        $totalPoinKeluar = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->where('status', 'selesai_transaksi')
                        ->sum('harga');

        // 3. Data Bar Chart (Tren Mingguan)
        $trenHarian = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->whereIn('status', ['selesai_transaksi', 'ditolak'])
                        ->selectRaw("DATE_FORMAT(updated_at, '%d %b') as tanggal, COUNT(*) as jumlah")
                        ->groupBy('tanggal')
                        ->orderBy(DB::raw("MIN(updated_at)"), 'asc')
                        ->take(7)
                        ->get();

        $chartLabels = $trenHarian->pluck('tanggal')->toArray();
        $chartData = $trenHarian->pluck('jumlah')->toArray();

        // 4. Hitung Total Akumulasi Berdasarkan Jenis Sampah (Untuk Pie Chart 1/3)
        $jenisSampahData = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->where('status', 'selesai_transaksi')
                        ->selectRaw("jenis, SUM(berat) as total_berat")
                        ->groupBy('jenis')
                        ->get()
                        ->pluck('total_berat', 'jenis')
                        ->toArray();

        $kategoriLabels = ['plastik', 'kertas', 'logam'];
        $kategoriData = [
            (float) ($jenisSampahData['plastik'] ?? 0),
            (float) ($jenisSampahData['kertas'] ?? 0),
            (float) ($jenisSampahData['logam'] ?? 0)
        ];

        // 5. Data Line Chart Finansial (Nominal Poin Keluar)
        $trenPoinSelesai = \App\Models\SetorSampah::where('pengepul_id', $pengepulId)
                        ->where('status', 'selesai_transaksi')
                        ->orderBy('updated_at', 'asc')
                        ->take(7)
                        ->get();

        $poinLabels = $trenPoinSelesai->map(function($item, $key) { return 'Trx ' . ($key + 1); })->toArray();
        $poinData = $trenPoinSelesai->pluck('harga')->map(function($value) { return (int) $value; })->toArray();

        return view('dashboard-pengepul', compact(
            'setoranMasuk', 
            'totalBerat', 
            'totalPenjemputan', 
            'totalPoinKeluar',
            'chartLabels',
            'chartData',
            'kategoriLabels',
            'kategoriData',
            'poinLabels',
            'poinData'
        ));
    }

    /**
     * 🛠️ KODE INTEGRASI: Mengubah status operasional lapak buka/tutup via Fetch API JSON
     */
    public function toggleStatus(Request $request)
    {
        // 1. Ambil data pengepul yang sedang login aktif lewat guard pengepul
        $pengepulModel = Auth::guard('pengepul')->user();

        if (!$pengepulModel) {
            return response()->json(['status' => 'error', 'message' => 'Sesi Anda telah berakhir.'], 401);
        }

        // 2. Ambil instance model murni dari database agar fungsi save aman
        $pengepul = Pengepul::find($pengepulModel->id);
        
        // 3. Tangkap status is_buka (1 atau 0) dari kiriman JavaScript body
        $pengepul->is_buka = $request->input('is_buka');
        $pengepul->save();

        // 4. Kirim respon balik berformat JSON murni agar dipahami oleh skrip AJAX (.then)
        return response()->json([
            'status' => 'success',
            'is_buka' => (int) $pengepul->is_buka
        ]);
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

        $pengepul->update([
            $field => $value
        ]);

        return redirect()->route('pengepul.profil')->with('success', 'Informasi lapak berhasil diperbarui!');
    }

    public function showTopupForm() 
    {
        return view('topup-poin');
    }

    public function prosesTopup(Request $request)
    {
        $request->validate([
            'nomor_rekening' => 'required|string',
            'metode'         => 'required|string',
            'nominal'        => 'required|numeric|min:50000'
        ]);

        $userLogin = Auth::guard('pengepul')->user();
        $jumlahTopup = (int) $request->nominal;

        $pengepul = Pengepul::find($userLogin->id);
        $pengepul->poin = ($pengepul->poin ?? 0) + $jumlahTopup;
        $pengepul->save(); 

        $poinFormat = number_format($jumlahTopup, 0, ',', '.');

        return redirect()->back()->with(
            'success', 
            'Selamat! Top up saldo sebesar ' . $poinFormat . ' Poin berhasil ditambahkan ke akun Anda.'
        );
    }

    public function logout(Request $request) 
    {
        Auth::guard('pengepul')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    public function showRiwayat()
    {
        $pengepulId = Auth::guard('pengepul')->id();

        $riwayatTransaksi = \App\Models\SetorSampah::with('user')
                            ->where('pengepul_id', $pengepulId)
                            ->whereIn('status', ['selesai_transaksi', 'ditolak'])
                            ->latest()
                            ->get();

        return view('riwayat-pengepul', compact('riwayatTransaksi'));
    }
}