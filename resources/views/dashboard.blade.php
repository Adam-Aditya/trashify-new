<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <h2 class="text-xl font-bold">Circular Milestone</h2>
            <p class="text-white/90 text-sm mt-1">Kamu sudah berkontribusi dalam pengelolaan sampah 🎉</p>

            <div class="flex gap-4 mt-4">
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl min-w-[120px]">
                    <p class="text-xs text-white/80">Total Sampah</p>
                    <h3 class="text-xl font-bold mt-1">12.4 Kg</h3>
                </div>
                <div class="bg-primary/20 backdrop-blur-sm p-4 rounded-xl min-w-[120px]">
                    <p class="text-xs text-white/80">Efisiensi</p>
                    <h3 class="text-xl font-bold mt-1">+14%</h3>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <a href="{{ route('setor.create') }}"
                class="inline-block bg-primary text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-[#2d3f1a] transition">
                + Setor Sampah
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-primary mb-4">Riwayat Sampah Terbaru</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Nama Sampah</th>
                            <th scope="col" class="px-6 py-3 font-bold">Jenis</th>
                            <th scope="col" class="px-6 py-3 font-bold">Berat</th>
                            <th scope="col" class="px-6 py-3 font-bold">Harga Estimasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                        <tr class="bg-white border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $d->nama }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary">
                                    {{ $d->jenis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $d->berat }} Kg</td>
                            <td class="px-6 py-4 font-bold text-primary">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400 font-medium">
                                📦 Belum ada data setoran sampah.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>