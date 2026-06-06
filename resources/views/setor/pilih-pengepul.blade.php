<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Pengepul - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#3D5524', gradStart: '#709867', gradEnd: '#9BB863' }
                }
            }
        }
    </script>
</head>
<body class="bg-[#e9f0e4]/50 font-jakarta min-h-screen p-6 flex flex-col items-center">

<div class="max-w-2xl w-full">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#2d3f1a]">Pilih Pengepul Pendukung</h1>
            <p class="text-gray-500 text-sm mt-0.5">Berikut mitra terdekat yang menerima jenis sampah <span class="font-bold text-primary uppercase">[{{ $sampahInput['jenis'] }}]</span></p>
        </div>
        <a href="{{ route('setor.create') }}" class="text-sm font-bold text-gray-500 hover:text-red-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6 flex items-center justify-between">
        <div>
            <small class="text-gray-400 font-bold uppercase tracking-wider block text-[10px]">Ringkasan Setoran Anda</small>
            <span class="font-bold text-gray-800 text-base">{{ $sampahInput['nama'] }}</span>
            <span class="text-xs text-gray-400 ml-2">({{ $sampahInput['berat'] }} Kg)</span>
        </div>
        <span class="px-3 py-1 bg-primary/10 text-primary font-extrabold rounded-lg text-xs uppercase tracking-wide">
            {{ $sampahInput['jenis'] }}
        </span>
    </div>

    <div class="space-y-4">
        @forelse($daftarPengepul as $pengepul)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between gap-4 group hover:shadow-md transition">
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center font-extrabold text-primary text-xl shadow-inner shrink-0">
                        {{ strtoupper(substr($pengepul->nama_toko, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-base group-hover:text-primary transition">
                            {{ $pengepul->nama_toko }}
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">
                            <i class="fas fa-map-marker-alt text-primary/60"></i> Berjarak {{ number_format($pengepul->jarak, 1, ',', '.') }} Km dari posisi Anda
                        </p>
                    </div>
                </div>

                <form action="{{ route('setor.store') }}" method="POST" class="shrink-0">
                    @csrf
                    <input type="hidden" name="nama" value="{{ $sampahInput['nama'] }}">
                    <input type="hidden" name="jenis" value="{{ $sampahInput['jenis'] }}">
                    <input type="hidden" name="berat" value="{{ $sampahInput['berat'] }}">
                    <input type="hidden" name="deskripsi" value="{{ $sampahInput['deskripsi'] }}">
                    <input type="hidden" name="pengepul_id" value="{{ $pengepul->id }}">

                    <button type="submit" class="bg-primary text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm hover:bg-[#2d3f1a] transition flex items-center gap-1">
                        Ajukan <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </form>

            </div>
        @empty
            <div class="bg-white text-center py-12 rounded-2xl border border-dashed border-gray-200">
                <p class="text-gray-400 font-medium">📦 Maaf, tidak ada pengepul yang buka atau menerima kategori jenis sampah ini di sekitar Anda.</p>
                <a href="{{ route('setor.create') }}" class="text-xs font-bold text-primary mt-2 inline-block hover:underline">Coba ganti jenis sampah</a>
            </div>
        @endforelse
    </div>

</div>
</body>
</html>