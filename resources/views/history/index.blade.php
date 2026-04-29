<!DOCTYPE html>
<html>
<head>
    <title>History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-64 h-screen bg-green-900 text-white p-5">
        <h1 class="text-2xl font-bold mb-8">Trashify</h1>

        <ul class="space-y-4">
            <li><a href="/dashboard" class="block p-2 rounded hover:bg-green-700">Dashboard</a></li>
            <li><a href="/history" class="block p-2 rounded bg-green-700">History</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-green-700">Wallet</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-green-700">Profil</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-6">

        <h1 class="text-2xl font-bold mb-6">History Sampah</h1>

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <!-- HEADER -->
            <div class="grid grid-cols-6 bg-gray-100 p-4 font-semibold text-gray-600">
                <div>Nama</div>
                <div>Jenis</div>
                <div>Berat</div>
                <div>Harga</div>
                <div class="col-span-2 text-center">Aksi</div>
            </div>

            <!-- DATA -->
            @forelse($data as $d)
            <div class="grid grid-cols-6 items-center p-4 border-t">

                <!-- NAMA -->
                <div class="font-medium">
                    {{ $d->nama }}
                </div>

                <!-- JENIS -->
                <div>
                    @if($d->jenis == 'plastik')
                        <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded text-sm">Plastik</span>
                    @elseif($d->jenis == 'kertas')
                        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-sm">Kertas</span>
                    @elseif($d->jenis == 'logam')
                        <span class="bg-gray-300 text-gray-800 px-2 py-1 rounded text-sm">Logam</span>
                    @endif
                </div>

                <!-- BERAT -->
                <div>{{ $d->berat }} Kg</div>

                <!-- HARGA -->
                <div class="text-green-700 font-semibold">
                    Rp {{ number_format($d->harga) }}
                </div>

                <!-- AKSI -->
                <div class="flex justify-center gap-3 col-span-2">

                    <!-- EDIT -->
                    <a href="/history/{{ $d->id }}/edit" class="text-green-600 hover:scale-110">
                        ✏️
                    </a>

                    <!-- DELETE -->
                    <form action="/history/{{ $d->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus data?')" class="text-red-600 hover:scale-110">
                            🗑️
                        </button>
                    </form>

                </div>

            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                Belum ada data
            </div>
            @endforelse

        </div>

    </div>
</div>

</body>
</html>