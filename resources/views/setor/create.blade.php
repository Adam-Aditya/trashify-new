<!DOCTYPE html>
<html>
<head>
    <title>Setor Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Setor Sampah</h1>

    <form action="/setor-sampah" method="POST">
        @csrf

        <label>Nama</label>
        <input type="text" name="nama" class="w-full border p-2 mb-3">

        <label>Jenis Sampah</label>
        <select name="jenis" id="jenis" class="w-full border p-2 mb-3">
            <option value="plastik">Plastik</option>
            <option value="kertas">Kertas</option>
            <option value="logam">Logam</option>
        </select>

        <label>Berat (Kg)</label>
        <input type="number" step="0.1" name="berat" id="berat" class="w-full border p-2 mb-3">

        <label>Harga (otomatis)</label>
        <input type="text" id="harga" class="w-full border p-2 mb-3 bg-gray-200" readonly>

        <label>Deskripsi</label>
        <textarea name="deskripsi" class="w-full border p-2 mb-3"></textarea>

        <button class="bg-green-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
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

    jenis.addEventListener('change', hitungHarga);
    berat.addEventListener('input', hitungHarga);
</script>

</body>
</html>