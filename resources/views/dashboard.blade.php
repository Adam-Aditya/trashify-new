<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Trashify</title>
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
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold">{{ Auth::user()->username }}</p>
                    <small class="text-gray-300">Eco User</small>
                </div>
            </div>

            <nav class="flex flex-col gap-2">
                <a href="{{ route('dashboard') }}" class="bg-white/20 px-5 py-3 rounded-xl flex items-center gap-3 font-semibold transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('history.index') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-history"></i> Riwayat
                </a>
                <a href="{{ route('poin.tukar') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-wallet"></i> Wallet
                </a>
                <a href="{{ route('profil.show') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-user"></i> Profil
                </a>
            </nav>
        </div>

        <div>
            <form action="{{ route('logout') }}" method="POST">
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
                Dashboard <span class="text-gray-400 font-normal text-sm">/ Beranda</span>
            </h2>

            <a href="{{ route('poin.tukar') }}" 
               class="bg-white flex items-center gap-3 rounded-full pl-3 pr-5 py-1.5 border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/20 transition group">
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                <div>
                    <div class="text-xs font-extrabold text-primary">
                        <span id="userPoin">{{ number_format(Auth::user()->poin ?? 0, 0, ',', '.') }} Poin</span>
                    </div>
                    <div class="text-[10px] text-gray-400 font-medium group-hover:text-primary transition">Tukar Poin →</div>
                </div>
            </a>
        </header>

        <div class="bg-gradient-to-r from-gradStart to-gradEnd text-white p-6 rounded-2xl shadow-lg mb-6">
            <h2 class="text-xl font-bold">Ringkasan Transaksi</h2>
            <p class="text-white/90 text-sm mt-1">Pantau kontribusi aktivitas penyetoran sampah dan tabungan poin digital Anda 📊</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-weight-hanging text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Sampah Disetor</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            {{ number_format($userTotalSampah ?? 0, 1, ',', '.') }} 
                            <span class="text-sm font-normal text-white/80">Kg</span>
                        </h3>
                    </div>
                </div>
                
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-coins text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Pendapatan Poin</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            <span class="text-sm font-bold text-white/80">Pts</span> 
                            {{ number_format($userTotalPoin ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-receipt text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Total Transaksi</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1">
                            {{ $userTotalTransaksi ?? 0 }} 
                            <span class="text-sm font-normal text-white/80">Sesi</span>
                        </h3>
                    </div>
                </div>

                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-green-100 opacity-90 mb-1">
                            <i class="fas fa-check-circle text-sm"></i>
                            <p class="text-xs font-semibold uppercase tracking-wider">Disetujui</p>
                        </div>
                        <h3 class="text-2xl font-black mt-1 text-white">
                            {{ $userTransaksiDisetujui ?? 0 }} 
                            <span class="text-sm font-normal text-white/80">Sukses</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mb-6">
            
            <div class="w-full lg:w-2/4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-primary text-lg flex items-center gap-2">
                            <i class="fas fa-chart-bar text-gradStart"></i> Grafik Pendapatan Poin Harian
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">Pertumbuhan tabungan perolehan poin dari transaksi sukses.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-[#e9f0e4] text-primary rounded-lg">Poin (Pts)</span>
                </div>
                <div class="w-full relative h-[260px]">
                    <canvas id="userPoinChart"></canvas>
                </div>
            </div>

            <div class="w-full lg:w-2/4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-primary text-lg flex items-center gap-2">
                            <i class="fas fa-dumpster text-gradEnd"></i> Sampah Disetor Harian
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">Akumulasi bobot muatan kontribusi harian Anda.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-gray-100 text-gray-600 rounded-lg">Massa (Kg)</span>
                </div>
                <div class="w-full relative h-[260px]">
                    <canvas id="userBeratChart"></canvas>
                </div>
            </div>

        </div>

        <div class="mb-6">
            <a href="{{ route('setor.create') }}"
                class="inline-block bg-primary text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-[#2d3f1a] transition">
                + Setor Sampah
            </a>
        </div>

        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-primary">Mitra Pengepul Terdekat</h2>
                <p class="text-gray-400 text-xs mt-0.5">Lakukan transaksi langsung dengan lapak agen yang sedang beroperasi</p>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full flex items-center gap-1.5 animate-pulse">
                <span class="w-2 h-2 rounded-full bg-green-500"></span> Live Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($daftarToko as $toko)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-primary/10 transition flex items-start gap-4 relative overflow-hidden group">
                    
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#e9f0e4] to-primary/10 flex items-center justify-center text-primary font-extrabold text-xl shadow-inner shrink-0 border border-gray-100">
                        {{ strtoupper(substr($toko->nama_toko ?? $toko->username, 0, 1)) }}
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-bold text-gray-800 text-base group-hover:text-primary transition line-clamp-1">
                                {{ $toko->nama_toko }}
                            </h3>
                            <span class="text-xs font-bold text-primary bg-primary/5 px-2.5 py-1 rounded-lg shrink-0 flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-[10px]"></i>
                                {{ number_format($toko->jarak, 1, ',', '.') }} Km
                            </span>
                        </div>

                        <p class="text-gray-400 text-xs mt-1 flex items-center gap-1.5">
                            <i class="fas fa-store-alt text-gray-300"></i> Agen Mitra Resmi Trashify
                        </p>

                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @if(isset($toko->kategori_sampah))
                                @foreach(explode(',', $toko->kategori_sampah) as $kat)
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-gray-50 border border-gray-200 text-gray-600 shadow-sm">
                                        🌱 {{ trim($kat) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-[10px] text-gray-400 italic">Menerima semua jenis sampah</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 bg-white text-center py-12 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium">📦 Saat ini belum ada mitra pengepul terdekat yang buka.</p>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        const chartLabelsShared = JSON.parse('{!! json_encode($userChartLabels ?? []) !!}');

        // 1. Render Bar Chart Pendapatan Poin (Lebar 2/4)
        const ctxPoin = document.getElementById('userPoinChart').getContext('2d');
        new Chart(ctxPoin, {
            type: 'bar',
            data: {
                labels: chartLabelsShared,
                datasets: [{
                    label: 'Poin Masuk',
                    data: JSON.parse('{!! json_encode($userChartPoin ?? []) !!}'),
                    backgroundColor: '#709867', // gradStart
                    hoverBackgroundColor: '#3D5524', // primary
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3D5524',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        displayColors: false,
                        callbacks: { label: function(c) { return ` + ${c.raw.toLocaleString('id-ID')} Pts`; } }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#9ca3af' } },
                    y: { grid: { color: '#f3f4f6' }, border: { dash: [5, 5] }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#9ca3af' } }
                }
            }
        });

        // 2. Render Bar Chart Tonase Berat Sampah (Lebar 2/4)
        const ctxBerat = document.getElementById('userBeratChart').getContext('2d');
        new Chart(ctxBerat, {
            type: 'bar',
            data: {
                labels: chartLabelsShared,
                datasets: [{
                    label: 'Massa Sampah',
                    data: JSON.parse('{!! json_encode($userChartBerat ?? []) !!}'),
                    backgroundColor: '#9BB863', // gradEnd
                    hoverBackgroundColor: '#3D5524',
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 24 // 🛠️ OPTIMASI: Ketebalan ditingkatkan dari 16 ke 24 agar seimbang secara simetris
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3D5524',
                        titleFont: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        displayColors: false,
                        callbacks: { label: function(c) { return ` ${c.raw} Kg`; } }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#9ca3af' } }, // 🛠️ OPTIMASI: Ukuran teks disamakan dengan chart kiri
                    y: { grid: { color: '#f3f4f6' }, border: { dash: [5, 5] }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#9ca3af' } } // 🛠️ OPTIMASI: Ukuran teks disamakan dengan chart kiri
                }
            }
        });
    </script>
</body>
</html>