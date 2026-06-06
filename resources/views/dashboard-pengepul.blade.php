<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengepul - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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

                <a href="{{ route('pengepul.riwayat') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
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
            <h2 class="text-xl font-bold">Ringkasan Transaksi</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-weight-hanging text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Berat Sampah Diterima</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            {{ number_format($totalBerat ?? 0, 1, ',', '.') }} 
                            <span class="text-sm font-normal text-white/80">Kg</span>
                        </h3>
                    </div>
                </div>
                
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-truck text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Total Penjemputan</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            {{ $totalPenjemputan ?? 0 }} 
                            <span class="text-sm font-normal text-white/80">History</span>
                        </h3>
                    </div>
                </div>

                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-coins text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Total Poin Keluar</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1 text-red-500">
                            <span class="text-sm font-bold text-red-500/80">-</span> 
                            {{ number_format($totalPoinKeluar ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-wallet text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Poin Saat Ini</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            <span class="text-sm font-bold text-white/80">Pts</span> 
                            {{ number_format(Auth::guard('pengepul')->user()->poin ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mb-6">
            
            <div class="w-full lg:w-2/3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-primary text-lg flex items-center gap-2">
                            <i class="fas fa-chart-bar text-gradStart"></i> Laporan Penjemputan Mingguan
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">Analisis kuantitas aktivitas penjemputan sampah.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-[#e9f0e4] text-primary rounded-lg">Kuantitas</span>
                </div>
                <div class="w-full relative h-[260px]">
                    <canvas id="pickupChart"></canvas>
                </div>
            </div>

            <div class="w-full lg:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-primary text-lg flex items-center gap-2">
                            <i class="fas fa-chart-line text-gradEnd"></i> Distribusi Jenis
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">Total tonase masuk per kategori (Kg).</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-gray-100 text-gray-600 rounded-lg">Kategori</span>
                </div>
                <div class="w-full relative h-[260px]">
                    <canvas id="categoryLineChart"></canvas>
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
                            <td class="px-6 py-4 font-bold text-primary">Rp {{ number_format($sm->harga, 0, ',', '.') }}</td>
                            
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
    </div>

    <script>
        // 1. Inisialisasi Bar Chart (Tren Penjemputan)
        const ctxBar = document.getElementById('pickupChart').getContext('2d');
        const pickupChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: JSON.parse('{!! json_encode($chartLabels ?? []) !!}'),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: JSON.parse('{!! json_encode($chartData ?? []) !!}'),
                    backgroundColor: '#709867',
                    hoverBackgroundColor: '#3D5524',
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#9ca3af' } },
                    y: { grid: { color: '#f3f4f6' }, border: { dash: [5, 5] }, ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#9ca3af' } }
                }
            }
        });

        // 2. Inisialisasi Pie Chart Jenis Sampah Terintegrasi
        const ctxPie = document.getElementById('categoryLineChart').getContext('2d');
        const categoryPieChart = new Chart(ctxPie, {
            type: 'pie', // Diubah menjadi pie
            data: {
                labels: JSON.parse('{!! json_encode($kategoriLabels ?? ["plastik", "kertas", "logam"]) !!}').map(w => w.charAt(0).toUpperCase() + w.slice(1)),
                datasets: [{
                    label: 'Total Berat (Kg)',
                    data: JSON.parse('{!! json_encode($kategoriData ?? [0,0,0]) !!}'),
                    // 🎨 Variasi warna tema alam & material sesuai identitas Trashify
                    backgroundColor: [
                        '#3D5524', // Hijau Tua Utama (Plastik)
                        '#709867', // Hijau Daun GradStart (Kertas)
                        '#9ca3af'  // Abu-abu Logam Muted (Logam)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff', // Garis pembatas putih antar irisan agar clean
                    hoverOffset: 12 // Efek pop-out melayang yang sedikit membesar saat kursor menyorot irisan pie
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true, // Aktifkan kembali legenda khusus pie chart
                        position: 'bottom', // Letakkan petunjuk warna di bagian bawah chart
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: '#3D5524',
                        titleFont: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.raw} Kg`;
                            }
                        }
                    }
                }
            }
        });

        // Script Asinkron Toggle Status Operasional Buka/Tutup Toko
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
                if (!res.ok) throw new Error('HTTP error ' + res.status);
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
                alert("Terjadi gangguan jaringan. Silakan refresh halaman.");
            });
        });
    </script>
</body>
</html>