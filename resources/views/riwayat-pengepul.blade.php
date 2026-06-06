<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Trashify</title>
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
                <a href="{{ route('pengepul.dashboard') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                <a href="{{ route('pengepul.riwayat') }}" class="bg-white/20 px-5 py-3 rounded-xl flex items-center gap-3 font-semibold transition">
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
                Riwayat Transaksi <span class="text-gray-400 font-normal text-sm">/ Log Arsip</span>
            </h2>
        </header>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-primary">Arsip Pembayaran & Penolakan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Daftar rekaman seluruh aktivitas setoran masuk pembukuan lapak Anda.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Tanggal</th>
                            <th scope="col" class="px-6 py-3 font-bold">Mitra Pengirim</th>
                            <th scope="col" class="px-6 py-3 font-bold">Nama Sampah</th>
                            <th scope="col" class="px-6 py-3 font-bold">Kategori</th>
                            <th scope="col" class="px-6 py-3 font-bold">Berat</th>
                            <th scope="col" class="px-6 py-3 font-bold">Nominal Pembayaran</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatTransaksi as $rt)
                        <tr class="bg-white border-b hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-medium text-gray-400 text-xs whitespace-nowrap">
                                {{ $rt->updated_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $rt->user->username ?? 'Eco User' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $rt->nama }}</td>
                            <td class="px-6 py-4">
                                @if($rt->jenis == 'plastik')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Plastik</span>
                                @elseif($rt->jenis == 'kertas')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Kertas</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Logam</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $rt->berat }} Kg</td>
                            <td class="px-6 py-4 font-black {{ $rt->status == 'selesai_transaksi' ? 'text-primary' : 'text-gray-400 line-through' }}">
                                - {{ number_format($rt->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($rt->status == 'selesai_transaksi')
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-xl">
                                        <i class="fas fa-check-circle"></i> Selesai dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-bold px-3 py-1.5 rounded-xl">
                                        <i class="fas fa-times-circle"></i> Ditolak Lapak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400 font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                                    <span>Belum ada rekaman arsip transaksi yang selesai diproses.</span>
                                </div>
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