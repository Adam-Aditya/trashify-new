<!DOCTYPE html>
<html>
<head>
    <title>Edit Setor Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">Edit Setor Sampah</h1>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="/history/{{ $data->id }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <label class="block mb-1">Nama</label>
        <input type="text" name="nama" value="{{ $data->nama }}"
               class="w-full border p-2 mb-3 rounded">

        <!-- Jenis -->
        <label class="block mb-1">Jenis Sampah</label>
        <select name="jenis" id="jenis" class="w-full border p-2 mb-3 rounded">
            <option value="plastik" {{ $data->jenis == 'plastik' ? 'selected' : '' }}>Plastik</option>
            <option value="kertas" {{ $data->jenis == 'kertas' ? 'selected' : '' }}>Kertas</option>
            <option value="logam" {{ $data->jenis == 'logam' ? 'selected' : '' }}>Logam</option>
        </select>

        <!-- Berat -->
        <label class="block mb-1">Berat (Kg)</label>
        <input type="number" step="0.1" name="berat" id="berat"
               value="{{ $data->berat }}"
               class="w-full border p-2 mb-3 rounded">

        <!-- Harga otomatis -->
        <label class="block mb-1">Harga (otomatis)</label>
        <input type="text" id="harga"
               class="w-full border p-2 mb-3 bg-gray-100 rounded"
               readonly>

        <!-- Deskripsi -->
        <label class="block mb-1">Deskripsi</label>
        <textarea name="deskripsi"
                  class="w-full border p-2 mb-4 rounded">{{ $data->deskripsi }}</textarea>

        <!-- BUTTON -->
        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-[#2d3f1a] text-white px-6 py-3.5 rounded-xl shadow-md transition text-center hover:opacity-90">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                Simpan Perubahan
            </button>

            <a href="/history"
               class="px-6 py-3.5 rounded-xl bg-gray-100 text-gray-400 hover:bg-gray-200 transition text-center">
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
        harga.value = total ? 'Rp ' + total : '';
    }

    // Hitung saat halaman dibuka
    hitungHarga();

    jenis.addEventListener('change', hitungHarga);
    berat.addEventListener('input', hitungHarga);
</script>

</body>
</html>