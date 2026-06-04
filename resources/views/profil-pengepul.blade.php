<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengepul - Trashify</title>
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

<body class="bg-[#f5f7f6] flex font-jakarta">

<aside class="w-[280px] bg-primary text-white h-screen fixed p-10 flex flex-col justify-between shadow-xl">
    <div>
        <div class="flex items-center gap-3 mb-10">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg text-white border border-white/30">
                {{ strtoupper(substr(Auth::guard('pengepul')->user()->username, 0, 1)) }}
            </div>
            <div>
                <p class="font-bold">{{ Auth::guard('pengepul')->user()->nama_toko }}</p>
                <small class="text-gray-300">Mitra Pengepul</small>
            </div>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="{{ route('pengepul.dashboard') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <a href="#" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-history"></i> Riwayat
            </a>

            <a href="{{ route('pengepul.poin.tukar') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-wallet"></i> Wallet
            </a>

            <a href="{{ route('pengepul.profil') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-user"></i> Profil Toko
            </a>
        </nav>
    </div>

    <div>
        <form action="{{ route('pengepul.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left px-5 py-3 rounded-xl text-red-200 hover:bg-red-900/30 hover:text-white transition font-semibold">
                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<main class="ml-[280px] flex-1 p-10 min-h-screen">

    <div class="max-w-4xl mx-auto mb-6">
        @if (session('success'))
            <div class="bg-green-600 text-white p-4 rounded-xl text-sm shadow-md font-medium">
                ✨ {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 rounded-xl text-sm shadow-md font-medium">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="text-center mb-10">
        <div class="w-28 h-28 rounded-2xl mx-auto shadow-lg mb-4 bg-gradient-to-tr from-gradStart to-gradEnd flex items-center justify-center text-white text-4xl font-bold">
            {{ strtoupper(substr(Auth::guard('pengepul')->user()->nama_toko, 0, 1)) }}
        </div>
        <h1 class="text-3xl font-extrabold text-primary">
            {{ Auth::guard('pengepul')->user()->nama_toko }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Mitra resmi pengelola limbah Trashify
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto items-start">

        <div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-5">
            <div class="flex justify-between mb-2">
                <h2 class="font-bold text-xl text-primary">Informasi Lapak Mitra</h2>
            </div>

            <div onclick="editField('Nama Toko', 'nama_toko')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">NAMA TOKO / LAPAK <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="nama_tokoText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ Auth::guard('pengepul')->user()->nama_toko }}
                </p>
            </div>

            <div onclick="editField('Username', 'username')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">USERNAME KARYAWAN <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="usernameText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ Auth::guard('pengepul')->user()->username }}
                </p>
            </div>

            <div onclick="editField('Email', 'email')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">EMAIL OPERASIONAL <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="emailText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ Auth::guard('pengepul')->user()->email }}
                </p>
            </div>

            <div onclick="editField('Password', 'password')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">PASSWORD <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-key"></i> Ubah</span></p>
                <p id="passwordText" class="border-b pb-2 text-gray-800 font-medium mt-1">********</p>
            </div>

            <div onclick="editField('Phone', 'phone')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">NOMOR TELEPON / WA <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="phoneText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ Auth::guard('pengepul')->user()->phone }}
                </p>
            </div>

            <div onclick="getCityLocation()" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold">ALAMAT PENGEPUL / KOTA <span id="locationBtnText" class="text-primary text-[10px] ml-1 transition group-hover:underline"><i class="fas fa-map-marker-alt"></i> Deteksi Kota Otomatis</span></p>
                <p id="locationText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ Auth::guard('pengepul')->user()->location ?? 'Alamat/Kota belum diatur' }}
                </p>
            </div>

            <div onclick="editField('Kategori Sampah', 'kategori_sampah')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition group">
                <p class="text-xs text-gray-400 font-bold uppercase">Kategori Sampah Yang Diterima <span class="text-primary text-[10px] ml-1 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-edit"></i> Ubah</span></p>
                <div id="kategori_sampahText" class="flex flex-wrap gap-2 mt-1.5 border-b pb-2">
                    @php
                        $kategoriArr = explode(',', Auth::guard('pengepul')->user()->kategori_sampah ?? '');
                        // Bersihkan spasi kosong
                        $kategoriArr = array_filter(array_map('trim', $kategoriArr));
                    @endphp
                    @forelse($kategoriArr as $kat)
                        <span class="px-3 py-1.5 bg-primary/10 text-primary font-bold text-xs rounded-full uppercase tracking-wider flex items-center gap-1.5" data-value="{{ $kat }}">
                            <i class="fas fa-check-circle text-[10px]"></i> {{ $kat }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-400 italic">Belum memilih kategori</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-primary text-white p-8 rounded-2xl flex flex-col justify-between shadow-lg sticky top-10">
            <div>
                <div class="flex justify-between items-center">
                    <span class="bg-[#10b981] px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                        SALDO AKTIF
                    </span>
                    <i class="fas fa-wallet text-xl opacity-80"></i>
                </div>
                
                <h3 class="text-xl font-bold mt-4">Poin Operasional</h3>
                <p class="text-sm opacity-80 mt-1 leading-relaxed">
                    Saldo poin yang Anda gunakan sebagai instrumen pembayaran transaksi sampah masuk dari Eco User.
                </p>
            </div>

            <div class="mt-12">
                <p class="text-5xl font-extrabold tracking-tight">
                    {{ number_format(Auth::guard('pengepul')->user()->poin ?? 0, 0, ',', '.') }}
                    <span class="text-xl font-normal ml-1">Poin</span>
                </p>
                
                <small class="uppercase text-xs text-white/70 tracking-widest block mt-2">
                    Setara Finansial: Rp {{ number_format(Auth::guard('pengepul')->user()->poin ?? 0, 0, ',', '.') }}
                </small>
            </div>
        </div>

    </div>
</main>

<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-2xl w-85 shadow-2xl transition-all">
        <h3 id="modalTitle" class="font-bold text-primary mb-4"></h3>
        
        <input id="modalInput" class="w-full border p-2 rounded-xl mb-4 text-black outline-none focus:border-primary">

        <div id="modalCheckboxContainer" class="hidden flex-col gap-2.5 mb-4 text-sm text-gray-700">
            <label class="flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50">
                <input type="checkbox" value="plastik" class="kat-check w-4 h-4 accent-primary">
                <span class="font-semibold">Plastik (Botol, Gelas, dll)</span>
            </label>
            <label class="flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50">
                <input type="checkbox" value="kertas" class="kat-check w-4 h-4 accent-primary">
                <span class="font-semibold">Kertas (Kardus, Koran, Arsip)</span>
            </label>
            <label class="flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50">
                <input type="checkbox" value="logam" class="kat-check w-4 h-4 accent-primary">
                <span class="font-semibold">Logam (Besi, Tembaga, Alumunium)</span>
            </label>
        </div>

        <div class="flex justify-end gap-2 text-sm font-semibold">
            <button onclick="closeModal()" class="px-4 py-2 rounded-xl text-gray-400 hover:bg-gray-100 transition">Batal</button>
            <button onclick="saveData()" class="bg-primary text-white px-4 py-2 rounded-xl hover:bg-[#2d3f1a] transition">Simpan</button>
        </div>
    </div>
</div>

<form id="updateForm" action="{{ route('pengepul.profil.update') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="field" id="fieldInput">
    <input type="hidden" name="value" id="valueInput">
</form>

<script>
let currentField = "";
let currentDbField = "";

function editField(title, dbField) {
    currentField = title;
    currentDbField = dbField;
    document.getElementById("modalTitle").innerText = "Edit " + title;

    const input = document.getElementById("modalInput");
    const checkContainer = document.getElementById("modalCheckboxContainer");

    // Sembunyikan semua tipe input bawaan terlebih dahulu
    input.classList.add("hidden");
    checkContainer.classList.add("hidden");
    input.type = "text";

    if (dbField === "kategori_sampah") {
        // Tampilkan kontainer pilihan checkbox jika yang diedit adalah kategori sampah
        checkContainer.classList.remove("hidden");
        
        // Reset centang checkbox modal terlebih dahulu
        const checkboxes = document.querySelectorAll(".kat-check");
        checkboxes.forEach(cb => cb.checked = false);

        // Ambil data kategori aktif di layar, lalu centang otomatis di dalam modal
        const activeBadges = document.querySelectorAll("#kategori_sampahText span[data-value]");
        activeBadges.forEach(badge => {
            const val = badge.getAttribute("data-value").trim();
            const targetCb = document.querySelector(`.kat-check[value="${val}"]`);
            if (targetCb) targetCb.checked = true;
        });

    } else if (dbField === "password") {
        input.classList.remove("hidden");
        input.value = "";
        input.type = "password";
    } else {
        input.classList.remove("hidden");
        const textElement = document.getElementById(dbField + "Text");
        if (textElement) {
            let val = textElement.innerText.trim();
            if (val === "Alamat/Kota belum diatur" || val === "Alamat belum diatur") {
                input.value = "";
            } else {
                input.value = val;
            }
        }
    }

    document.getElementById("modal").classList.remove("hidden");
    document.getElementById("modal").classList.add("flex");
}

function closeModal() {
    document.getElementById("modal").classList.add("hidden");
    document.getElementById("modal").classList.remove("flex");
}

function saveData() {
    let finalValue = "";

    if (currentDbField === "kategori_sampah") {
        // Ambil semua nilai checkbox yang dicentang oleh user
        const checkedBoxes = document.querySelectorAll(".kat-check:checked");
        if (checkedBoxes.length === 0) return alert("Pilih minimal satu kategori sampah!");
        
        const values = [];
        checkedBoxes.forEach(cb => values.push(cb.value));
        finalValue = values.join(","); // satukan menjadi string terpisah koma (cth: plastik,kertas)
    } else {
        finalValue = document.getElementById("modalInput").value.trim();
        if (!finalValue) return alert("Kolom data tidak boleh dibiarkan kosong!");
    }

    document.getElementById("fieldInput").value = currentDbField;
    document.getElementById("valueInput").value = finalValue;

    document.getElementById("updateForm").submit();
}

function getCityLocation() {
    const locationText = document.getElementById("locationText");
    const btnText = document.getElementById("locationBtnText");

    if (!navigator.geolocation) {
        return alert("Browser Anda tidak mendukung deteksi lokasi otomatis.");
    }

    btnText.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Meminta Akses GPS...`;
    locationText.innerText = "Menghubungkan ke satelit GPS...";

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            locationText.innerText = "Menerjemahkan koordinat menjadi nama kota...";
            btnText.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Mencari Kota...`;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    const address = data.address;
                    let namaKota = address.city || address.regency || address.town || address.county || address.municipality || "Kota Tidak Terdeteksi";

                    namaKota = namaKota.replace("Regency", "Kabupaten").trim();

                    locationText.innerText = `Terdeteksi: ${namaKota}`;
                    btnText.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Menyimpan...`;

                    document.getElementById("fieldInput").value = "location"; 
                    document.getElementById("valueInput").value = namaKota;
                    document.getElementById("updateForm").submit();
                })
                .catch(err => {
                    console.error(err);
                    btnText.innerHTML = `<i class="fas fa-map-marker-alt"></i> Deteksi Kota Otomatis`;
                    locationText.innerText = "Gagal menerjemahkan nama kota. Coba lagi.";
                    alert("Gagal mengambil data kota dari server geocoding.");
                });
        },
        function (error) {
            btnText.innerHTML = `<i class="fas fa-map-marker-alt"></i> Deteksi Kota Otomatis`;
            locationText.innerText = "{{ Auth::guard('pengepul')->user()->location ?? 'Alamat/Kota belum diatur' }}";
            
            if (error.code === error.PERMISSION_DENIED) {
                alert("Akses ditolak. Mohon izinkan permission lokasi pada pengaturan browser Anda.");
            } else {
                alert("Gagal mendeteksi koordinat GPS perangkat. Pastikan GPS/Lokasi di perangkat Anda sudah menyala.");
            }
        },
        { enableHighAccuracy: true, timeout: 8000 }
    );
}
</script>

</body>
</html>