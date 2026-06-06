<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat - Trashify</title>
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
                <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('history.index') }}" class="bg-white/20 px-5 py-3 rounded-xl flex items-center gap-3 font-semibold transition">
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

        @if (session('success'))
            <div class="bg-green-600 text-white p-4 rounded-xl text-sm shadow mb-6">
                ✨ {{ session('success') }}
            </div>
        @endif

        <h1 class="text-3xl font-extrabold text-primary mb-6">Riwayat</h1>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-primary mb-4">Semua Riwayat Setoran</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nama Sampah</th>
                            <th scope="col" class="px-6 py-4 font-bold">Jenis</th>
                            <th scope="col" class="px-6 py-4 font-bold">Berat</th>
                            <th scope="col" class="px-6 py-4 font-bold">Poin Diterima</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Status Konfirmasi</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                        <tr class="bg-white border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-400 text-xs whitespace-nowrap">
                                {{ $d->created_at ? $d->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $d->nama }}</td>
                            <td class="px-6 py-4">
                                @if(strtolower($d->jenis) == 'plastik')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Plastik</span>
                                @elseif(strtolower($d->jenis) == 'kertas')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Kertas</span>
                                @elseif(strtolower($d->jenis) == 'logam')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Logam</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary">{{ $d->jenis }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $d->berat }} Kg</td>
                            <td class="px-6 py-4 font-bold text-primary">{{ number_format($d->harga, 0, ',', '.') }}</td>
                            
                            <td class="px-6 py-4 text-center">
                                @if($d->status == 'pending')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock text-[10px]"></i> Menunggu Persetujuan
                                    </span>
                                @elseif($d->status == 'diterima')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-truck text-[10px]"></i> Dalam Penjemputan
                                    </span>
                                @elseif($d->status == 'selesai_transaksi')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle text-[10px]"></i> Selesai Terbayar
                                    </span>
                                @elseif($d->status == 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle text-[10px]"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-4">
                                    @if($d->status == 'pending')
                                        <a href="{{ route('history.edit', $d->id) }}" class="text-blue-600 hover:text-blue-800 transition transform hover:scale-110 text-base" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 cursor-not-allowed text-base" title="Data sudah diproses, tidak bisa diedit">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    @endif

                                    <form action="{{ route('history.destroy', $d->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat setoran ini?')" class="text-red-500 hover:text-red-700 transition transform hover:scale-110 text-base" title="Hapus Data">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-400 font-medium">
                                📦 Belum ada riwayat transaksi sampah.
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