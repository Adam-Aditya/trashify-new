<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor Sampah - Trashify</title>
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
<body class="bg-[#e9f0e4]/50 font-jakarta min-h-screen flex items-center justify-center p-6">

<div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-[#2d3f1a]/10 flex items-center justify-center text-[#2d3f1a] text-lg">
            <i class="fas fa-search-location"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-[#2d3f1a]">Setor Sampah</h1>
    </div>

    {{-- 🛠️ PERBAIKAN: Method diubah ke GET untuk meneruskan data filter ke halaman list pengepul --}}
    <form action="{{ route('setor.pilih-pengepul') }}" method="GET">

        <div class="mb-4">
            <label class="block mb-1 text-sm font-bold text-gray-600">Nama Sampah</label>
            <input type="text" name="nama" placeholder="Misal: Botol Plastik Aqua"
                   class="w-full border border-gray-200 p-3 rounded-xl outline-none focus:border-[#2d3f1a] text-gray-800 font-medium transition" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm font-bold text-gray-600">Jenis Sampah</label>
            <select name="jenis" id="jenis" class="w-full border border-gray-200 p-3 rounded-xl outline-none focus:border-[#2d3f1a] text-gray-800 font-medium transition bg-white">
                <option value="plastik">Plastik</option>
                <option value="kertas">Kertas</option>
                <option value="logam">Logam</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm font-bold text-gray-600">Berat (Kg)</label>
            <input type="number" step="0.1" name="berat" id="berat" placeholder="0.0"
                   class="w-full border border-gray-200 p-3 rounded-xl outline-none focus:border-[#2d3f1a] text-gray-800 font-bold transition" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm font-bold text-gray-400">Harga Estimasi (Otomatis)</label>
            <input type="text" id="harga"
                   class="w-full border border-gray-200 p-3 bg-gray-50 text-[#2d3f1a] font-extrabold rounded-xl outline-none cursor-not-allowed"
                   readonly>
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-sm font-bold text-gray-600">Deskripsi Pendek</label>
            <textarea name="deskripsi" rows="3" placeholder="Tambahkan catatan kondisi sampah (opsional)..."
                      class="w-full border border-gray-200 p-3 rounded-xl outline-none focus:border-[#2d3f1a] text-gray-800 transition"></textarea>
        </div>

        <div class="flex gap-3 text-sm font-semibold">
            {{-- 🛠️ PERBAIKAN: Tombol diubah menjadi Cari Pengepul --}}
            <button type="submit" 
                class="flex-1 bg-[#2d3f1a] text-white px-6 py-3.5 rounded-xl shadow-md transition text-center hover:opacity-90 flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Cari Pengepul
            </button>

            <a href="{{ route('dashboard') }}" 
               class="px-6 py-3.5 rounded-xl bg-gray-100 text-gray-400 hover:bg-gray-200 transition text-center flex items-center justify-center">
                Batal
            </a>
        </div>

    </form>
</div>

<script>
    const jenis = document.getElementById('jenis');
    const berat = document.getElementById('berat');
    const harga = document.getElementById('harga');

    function hitungHarga() {
        let hargaPerKg = 0;

        if (jenis.value === 'plastik') hargaPerKg = 3000;
        if (jenis.value === 'kertas') hargaPerKg = 5000;
        if (jenis.value === 'logam') hargaPerKg = 6000;

        let total = berat.value * hargaPerKg;
        harga.value = total ? ' ' + total.toLocaleString('id-ID') : '';
    }

    jenis.addEventListener('change', hitungHarga);
    berat.addEventListener('input', hitungHarga);
</script>

</body>
</html>