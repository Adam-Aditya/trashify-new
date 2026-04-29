<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trashify Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-64 h-screen bg-green-900 text-white p-5">
        <h1 class="text-2xl font-bold mb-8">Trashify</h1>

        <ul class="space-y-4">
            <li><a href="/dashboard" class="block p-2 rounded hover:bg-green-700">Dashboard</a></li>
            <a href="/history" class="block p-2 rounded hover:bg-green-700">History</a>
            <li><a href="#" class="block p-2 rounded hover:bg-green-700">Wallet</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-green-700">Profil</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-1 p-6">

        <h1 class="text-3xl font-bold mb-6">Impact Dashboard</h1>
        
        <!-- CARD -->
        <div class="bg-green-700 text-white p-6 rounded-xl mb-6">
            <h2 class="text-xl font-semibold">Circular Milestone</h2>
            <p class="text-sm mt-2">Kamu sudah berkontribusi dalam pengelolaan sampah 🎉</p>

            <div class="flex gap-4 mt-4">
                <div class="bg-green-800 p-3 rounded">
                    <p class="text-sm">Total Sampah</p>
                    <h3 class="text-lg font-bold">12.4 Kg</h3>
                </div>
                <div class="bg-green-800 p-3 rounded">
                    <p class="text-sm">Efisiensi</p>
                    <h3 class="text-lg font-bold">+14%</h3>
                </div>
            </div>
        </div>

        <a href="/setor-sampah"
            class="inline-block bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
            + Setor Sampah
        </a>

        <!-- MARKET VALUE -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="text-gray-600">Total Saldo</h2>
            <h1 class="text-2xl font-bold text-green-700">Rp 250.000</h1>
        </div>

        <!-- TABLE -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">History Sampah</h2>

            <table class="w-full">
                <tr class="text-left border-b">
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Berat</th>
                    <th>Harga</th>
                </tr>

                @forelse($data as $d)
                <tr class="border-b">
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->jenis }}</td>
                    <td>{{ $d->berat }} Kg</td>
                    <td>Rp {{ number_format($d->harga) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-3">Belum ada data</td>
                </tr>
                @endforelse
            </table>
            </table>
        </div>

    </div>
</div>

</body>
</html>