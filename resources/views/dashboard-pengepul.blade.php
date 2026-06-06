<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trashify Dashboard Pengepul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3D5524',
                        gradStart: '#709867',
                        gradEnd: '#9BB863'
                    },
                    fontFamily: {
                        jakarta: ['Plus Jakarta Sans']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#e9f0e4]/50 flex font-jakarta">

    <aside class="w-[280px] bg-primary text-white h-screen fixed p-10 flex flex-col justify-between shadow-xl">
        <div>
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg text-white border border-white/30">
                    {{ strtoupper(substr(Auth::guard('pengepul')->user()->username, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold">{{ Auth::guard('pengepul')->user()->nama_toko }}</p>
                    <small class="text-gray-300">Mitra Pengepul</small>
                </div>
            </div>

            <nav class="flex flex-col gap-2">
                <a href="{{ route('pengepul.dashboard') }}" class="bg-white/20 px-5 py-3 rounded-xl flex items-center gap-3 font-semibold transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                <a href="#" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-history"></i> Riwayat
                </a>

                <a href="{{ route('pengepul.poin.tukar') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-wallet"></i> Wallet
                </a>

                <a href="{{ route('pengepul.profil') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-user"></i> Profil Toko
                </a>
            </nav>
        </div>

        <div>
            <form action="{{ route('pengepul.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-5 py-3 rounded-xl text-red-200 hover:bg-red-900/30 hover:text-white transition font-semibold">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 ml-[280px] p-10 h-screen overflow-y-auto">

        <header class="flex justify-between items-center mb-10">
            <h2 class="text-xl font-extrabold text-primary">
                Dashboard Pengepul <span class="text-gray-400 font-normal text-sm">/ Beranda</span>
            </h2>

            <div class="bg-white flex items-center gap-3 rounded-full pl-4 pr-5 py-2 border border-gray-100 shadow-sm">
                <div id="statusDot" class="w-3 h-3 rounded-full {{ Auth::guard('pengepul')->user()->is_buka ? 'bg-green-500' : 'bg-red-500' }}"></div>
                <span class="text-xs font-bold text-gray-700" id="statusText">
                    Toko: {{ Auth::guard('pengepul')->user()->is_buka ? 'BUKA' : 'TUTUP' }}
                </span>
            </div>
        </header>

        <div class="bg-gradient-to-r from-gradStart to-gradEnd text-white p-6 rounded-2xl shadow-lg mb-6">
            <h2 class="text-xl font-bold">Laporan Transaksi Harian</h2>
            <p class="text-white/90 text-sm mt-1">Pantau perkembangan pasokan sampah masuk hari ini 📈</p>

            <div class="flex gap-4 mt-4">
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl min-w-[140px]">
                    <p class="text-xs text-white/80">Sampah Diterima</p>
                    <h3 class="text-xl font-bold mt-1">48.2 Kg</h3>
                </div>
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl min-w-[140px]">
                    <p class="text-xs text-white/80">Total Pengeluaran</p>
                    <h3 class="text-xl font-bold mt-1">Rp 185.000</h3>
                </div>
            </div>
        </div>

        <div class="mb-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-primary text-lg">Status Operasional</h3>
                <p class="text-gray-400 text-xs mt-0.5">Aktifkan untuk menampilkan lapak Anda pada dashboard pengguna.</p>
            </div>
            
            <label class="relative inline-flex items-center cursor-pointer select-none">
                <input type="checkbox" id="toggleToko" class="sr-only peer" {{ Auth::guard('pengepul')->user()->is_buka ? 'checked' : '' }}>
                <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#2d3f1a]"></div>
            </label>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-primary">Daftar Transaksi Sampah Masuk</h2>
            @if(session('success'))
                <span class="text-xs bg-green-100 text-green-800 font-bold px-3 py-1 rounded-lg">
                    {{ session('success') }}
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-bold">Pengirim</th>
                        <th scope="col" class="px-6 py-3 font-bold">Nama Sampah</th>
                        <th scope="col" class="px-6 py-3 font-bold">Jenis Sampah</th>
                        <th scope="col" class="px-6 py-3 font-bold">Berat</th>
                        <th scope="col" class="px-6 py-3 font-bold">Estimasi Bayar</th>
                        <th scope="col" class="px-6 py-3 font-bold text-center">Informasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($setoranMasuk as $sm)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $sm->user->username ?? 'Eco User' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $sm->nama }}</td>
                        <td class="px-6 py-4">
                            @if($sm->jenis == 'plastik')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Plastik</span>
                            @elseif($sm->jenis == 'kertas')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Kertas</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Logam</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $sm->berat }} Kg</td>
                        <td class="px-6 py-4 font-bold text-primary"> {{ number_format($sm->harga, 0, ',', '.') }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('pengepul.setoran.detail', $sm->id) }}" class="inline-flex items-center gap-1 bg-primary/10 hover:bg-primary/20 text-primary font-bold text-xs px-3 py-1.5 rounded-lg transition">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 font-medium">
                            📦 Belum ada pengajuan setoran sampah masuk yang perlu diproses.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('toggleToko').addEventListener('change', function() {
            let status = this.checked ? 1 : 0;

            fetch("{{ route('pengepul.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')?.value || "{{ csrf_token() }}"
                },
                body: JSON.stringify({ is_buka: status })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('HTTP error ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                if(data.status === 'success') {
                    let textEl = document.getElementById('statusText');
                    let dotEl = document.getElementById('statusDot');
                    
                    if(status === 1) {
                        textEl.innerText = "Toko: BUKA";
                        if(dotEl) dotEl.className = "w-3 h-3 rounded-full bg-green-500";
                    } else {
                        textEl.innerText = "Toko: TUTUP";
                        if(dotEl) dotEl.className = "w-3 h-3 rounded-full bg-red-500";
                    }
                } else {
                    alert("Gagal memperbarui status toko.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi gangguan jaringan atau sesi Anda telah berakhir. Silakan refresh halaman.");
            });
        });
    </script>
</body>
</html>