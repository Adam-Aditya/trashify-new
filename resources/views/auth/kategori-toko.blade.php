<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Mitra Toko - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#3D5524', gradStart: '#709867', gradEnd: '#9BB863' },
                    fontFamily: { jakarta: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8faf8] min-h-screen font-jakarta flex items-center justify-center p-6">

    <div class="w-full max-w-lg bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-primary">Profil Mitra Toko</h2>
            <p class="text-gray-500 text-sm mt-1">Lengkapi informasi lapak pengumpulan sampah Anda</p>
        </div>

        <form action="{{ route('pengepul.toko.save') }}" method="POST" class="space-y-6">
            @csrf <div>
                <label class="block mb-2 text-sm font-bold text-gray-700">Nama Toko / Lapak</label>
                <input type="text" name="nama_toko" placeholder="Contoh: UD Jaya Plastik" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-primary focus:ring-1 focus:ring-primary text-gray-800 transition">
            </div>

            <div>
                <label class="block mb-2 text-sm font-bold text-gray-700">Kategori Sampah yang Diterima</label>
                <p class="text-xs text-gray-400 mb-3">*Bisa memilih lebih dari satu kategori</p>
                
                <div class="grid grid-cols-1 gap-3">
                    <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="kategori[]" value="plastik" class="w-5 h-5 accent-primary rounded">
                        <span class="font-semibold text-gray-700">Plastik (Botol, Gelas, dll)</span>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="kategori[]" value="kertas" class="w-5 h-5 accent-primary rounded">
                        <span class="font-semibold text-gray-700">Kertas (Kardus, Koran, Arsip)</span>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="kategori[]" value="logam" class="w-5 h-5 accent-primary rounded">
                        <span class="font-semibold text-gray-700">Logam (Besi, Tembaga, Alumunium)</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#2d3f1a] text-white rounded-xl font-bold hover:opacity-90 shadow-md transition">
                Selesai & Masuk Dashboard
            </button>
        </form>
    </div>

</body>
</html>