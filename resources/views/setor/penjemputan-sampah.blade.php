<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjemputan - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#e9f0e4]/50 font-jakarta min-h-screen p-6 flex justify-center items-center relative">

<div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-6 text-center">
    
    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded-xl text-xs font-bold text-left shadow mb-4">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="w-20 h-20 bg-[#3D5524]/10 text-[#3D5524] rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
        <i class="fas fa-map-signs"></i>
    </div>
    
    <h1 class="text-2xl font-extrabold text-gray-800">Mode Penjemputan Aktif</h1>
    <p class="text-gray-500 text-sm mt-2">Silakan bersiap melakukan navigasi menuju titik lokasi pengguna **{{ $setoran->user->username }}**.</p>

    <div class="bg-gray-50 rounded-2xl p-4 my-6 text-left border border-gray-100 space-y-2">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider">Ringkasan Tugas:</div>
        <div class="text-sm font-semibold text-gray-700"><i class="fas fa-box text-primary mr-1"></i> Barang: {{ $setoran->nama }} ({{ $setoran->berat }} Kg)</div>
        <div class="text-sm font-semibold text-gray-700"><i class="fas fa-phone text-primary mr-1"></i> Kontak: {{ $setoran->user->phone ?? '-' }}</div>
        <div class="text-sm font-bold text-primary"><i class="fas fa-wallet mr-1"></i> Total Bayar: {{ number_format($setoran->harga, 0, ',', '.') }} Poin</div>
    </div>

    @if($setoran->user && $setoran->user->location)
        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($setoran->user->location) }}" target="_blank"
           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-md transition flex items-center justify-center gap-2 mb-3">
            <i class="fas fa-navigation"></i> Mulai Navigasi Maps
        </a>
    @endif

    <button type="button" id="btnBukaSelesaiModal" class="w-full bg-[#2d3f1a] hover:bg-[#1f2c12] text-white font-bold py-3 rounded-xl transition block text-sm shadow-sm">
        Selesaikan & Kembali ke Dashboard
    </button>
</div>

<div id="modalSelesaiTransaksi" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white max-w-sm w-full p-6 rounded-2xl shadow-2xl text-center transform scale-95 transition-transform duration-300">
        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
        <h3 class="text-lg font-extrabold text-gray-800">Yakin Menyelesaikan Transaksi?</h3>
        <p class="text-gray-500 text-sm mt-2">
            Konfirmasi ini akan otomatis memotong Wallet Anda sebesar <span class="font-bold text-primary"> {{ number_format($setoran->harga, 0, ',', '.') }}</span> poinmu untuk ditransfer ke saldo poin akun user.
        </p>
        
        <div class="grid grid-cols-2 gap-3 mt-6">
            <button type="button" id="btnBatalSelesaiModal" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs py-3 rounded-xl transition">
                Batal
            </button>
            
            <form action="{{ route('pengepul.setoran.selesai', $setoran->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-[#2d3f1a] text-white font-bold text-xs py-3 rounded-xl shadow-sm transition">
                    Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalSelesaiTransaksi');
    const btnBuka = document.getElementById('btnBukaSelesaiModal');
    const btnBatal = document.getElementById('btnBatalSelesaiModal');
    const modalBox = modal.querySelector('div');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('opacity-100');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    btnBuka.addEventListener('click', openModal);
    btnBatal.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
        if(e.target === modal) closeModal();
    });
</script>

</body>
</html>