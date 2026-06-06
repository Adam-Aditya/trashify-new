<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Setoran - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#e9f0e4]/50 font-jakarta min-h-screen p-6 flex justify-center items-center">

<div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden relative">
    
    <div class="bg-gradient-to-r from-[#3D5524] to-[#709867] p-6 text-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold">Detail Pengajuan Setoran</h1>
                <p class="text-white/80 text-xs">ID Setoran: #TRF-{{ $setoran->id }}</p>
            </div>
        </div>
        <a href="{{ route('pengepul.dashboard') }}" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="p-6 space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                <i class="fas fa-user-circle"></i> Profil Pengirim (Eco User)
            </h3>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-gray-100 text-gray-700 font-bold flex items-center justify-center border border-gray-200">
                    {{ strtoupper(substr($setoran->user->username ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-base">{{ $setoran->user->username ?? 'Nama Tidak Diketahui' }}</h4>
                    <p class="text-gray-400 text-xs mt-0.5"><i class="fas fa-phone-alt"></i> Kontak: {{ $setoran->user->phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-100 pb-4">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                <i class="fas fa-box"></i> Informasi Sampah
            </h3>
            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 shadow-inner">
                <div>
                    <small class="text-gray-400 text-[10px] block font-bold uppercase">Nama Barang</small>
                    <span class="font-bold text-gray-800 text-sm">{{ $setoran->nama }}</span>
                </div>
                <div>
                    <small class="text-gray-400 text-[10px] block font-bold uppercase">Kategori</small>
                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-md bg-emerald-100 text-emerald-800 uppercase inline-block mt-0.5">
                        {{ $setoran->jenis }}
                    </span>
                </div>
                <div class="mt-2">
                    <small class="text-gray-400 text-[10px] block font-bold uppercase">Total Timbangan</small>
                    <span class="font-extrabold text-gray-800 text-base">{{ $setoran->berat }} Kg</span>
                </div>
                <div class="mt-2">
                    <small class="text-gray-400 text-[10px] block font-bold uppercase">Biaya yang Harus Dibayar</small>
                    <span class="font-extrabold text-[#3D5524] text-base"> {{ number_format($setoran->harga, 0, ',', '.') }} Poin</span>
                </div>
            </div>

            @if($setoran->deskripsi)
                <div class="mt-3 p-3 bg-amber-50/50 border border-amber-100 rounded-xl text-xs text-amber-900">
                    <span class="font-bold block mb-0.5"><i class="far fa-sticky-note"></i> Catatan Pengirim:</span>
                    "{{ $setoran->deskripsi }}"
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                <i class="fas fa-map-marked-alt"></i> Lokasi Penjemputan
            </h3>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 border border-gray-100 rounded-xl">
                <div>
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <i class="fas fa-route text-[#3D5524]"></i>
                        <span>Radius Jarak: {{ number_format($jarak, 2, ',', '.') }} Km</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">
                        <i class="fas fa-compass"></i> Koordinat GPS: <span class="font-mono">{{ $setoran->user->location ?? 'Tidak ada data' }}</span>
                    </p>
                </div>

                @if($setoran->user && $setoran->user->location)
                    <a href="https://maps.google.com/?q={{ urlencode($setoran->user->location) }}" 
                       target="_blank" 
                       class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-map-signs"></i> Buka Google Maps
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
        <form action="{{ route('pengepul.setoran.status', $setoran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak setoran ini?')">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="ditolak">
            <input type="hidden" name="redirect_to" value="dashboard"> 
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1">
                <i class="fas fa-times"></i> Tolak Penjemputan
            </button>
        </form>

        <button type="button" id="btnBukaModal" class="bg-green-600 hover:bg-green-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition flex items-center gap-1">
            <i class="fas fa-check"></i> Setujui Pengajuan
        </button>
    </div>
</div>

<div id="modalKonfirmasi" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white max-w-sm w-full p-6 rounded-2xl shadow-2xl text-center transform scale-95 transition-transform duration-300">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
            <i class="fas fa-truck-moving"></i>
        </div>
        <h3 class="text-lg font-extrabold text-gray-800">Konfirmasi Penjemputan</h3>
        <p class="text-gray-500 text-sm mt-2">Anda akan diarahkan pada halaman panduan rute penjemputan sampah mitra.</p>
        
        <div class="grid grid-cols-2 gap-3 mt-6">
            <button type="button" id="btnBatalModal" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs py-3 rounded-xl transition">
                Batal
            </button>
            
            <form action="{{ route('pengepul.setoran.status', $setoran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="diterima">
                <input type="hidden" name="redirect_to" value="penjemputan">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold text-xs py-3 rounded-xl shadow-sm transition">
                    Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalKonfirmasi');
    const btnBuka = document.getElementById('btnBukaModal');
    const btnBatal = document.getElementById('btnBatalModal');
    const modalContent = modal.querySelector('div');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('opacity-100');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    btnBuka.addEventListener('click', openModal);
    btnBatal.addEventListener('click', closeModal);

    // Tutup jika klik luar kotak putih modal
    modal.addEventListener('click', function(e) {
        if(e.target === modal) closeModal();
    });
</script>

</body>
</html>