<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Trashify</title>
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
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
            <div>
                <p class="font-bold">{{ $user->username }}</p>
                <small class="text-gray-300">Eco User</small>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <a href="{{ route('history.index') }}" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-history"></i> Riwayat
            </a>

            <a href="#" class="px-5 py-3 rounded-xl flex items-center gap-3 text-green-100 hover:bg-white/10 transition">
                <i class="fas fa-wallet"></i> Wallet
            </a>

            <a href="{{ route('profil.show') }}" class="bg-white/20 px-5 py-3 rounded-xl flex items-center gap-3 font-semibold transition">
                <i class="fas fa-user"></i> Profil
            </a>
        </nav>
    </div>

    <div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left px-5 py-3 rounded-xl text-red-200 hover:bg-red-900/30 hover:text-white transition font-semibold">
                <i class="fas fa-sign-out-alt mr-2"></i> Log out
            </button>
        </form>
    </div>

</aside>

<main class="ml-[280px] flex-1 p-10">

    <div class="max-w-4xl mx-auto mb-6">
        @if (session('success'))
            <div class="bg-green-600 text-white p-4 rounded-xl text-sm shadow">
                ✨ {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-600 text-white p-4 rounded-xl text-sm shadow">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="text-center mb-10">
        <div class="w-28 h-28 rounded-2xl mx-auto shadow-lg mb-4 bg-gradient-to-tr from-gradStart to-gradEnd flex items-center justify-center text-white text-4xl font-bold">
            {{ strtoupper(substr($user->username, 0, 1)) }}
        </div>
        <h1 id="nameText" class="text-3xl font-extrabold text-primary">
            {{ $user->username }}
        </h1>
        <p id="emailHeader" class="text-gray-500">
            {{ $user->email }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">

        <div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

            <div class="flex justify-between mb-6">
                <h2 class="font-bold text-xl text-primary">Personal Information</h2>
            </div>

            <div onclick="editField('Username')" class="mb-5 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <p class="text-xs text-gray-400 font-bold">USERNAME <span class="text-primary text-[10px] ml-1"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="usernameText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ $user->username }}
                </p>
            </div>

            <div onclick="editField('Email')" class="mb-5 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <p class="text-xs text-gray-400 font-bold">EMAIL <span class="text-primary text-[10px] ml-1"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="emailText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ $user->email }}
                </p>
            </div>

            <div onclick="editField('Password')" class="mb-5 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <p class="text-xs text-gray-400 font-bold">PASSWORD <span class="text-primary text-[10px] ml-1"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="passwordText" class="border-b pb-2 text-gray-800 font-medium mt-1">********</p>
            </div>

            <div onclick="editField('Phone')" class="mb-5 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <p class="text-xs text-gray-400 font-bold">PHONE <span class="text-primary text-[10px] ml-1"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="phoneText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ $user->phone }}
                </p>
            </div>

            <div onclick="editField('Location')" class="cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                <p class="text-xs text-gray-400 font-bold">LOCATION <span class="text-primary text-[10px] ml-1"><i class="fas fa-edit"></i> Ubah</span></p>
                <p id="locationText" class="border-b pb-2 text-gray-800 font-medium mt-1">
                    {{ $user->location ?? 'Belum diatur' }}
                </p>
            </div>
        </div>

        <div class="bg-primary text-white p-8 rounded-2xl flex flex-col justify-between shadow-lg">
            <div>
                <span class="bg-[#10b981] px-3 py-1 rounded-full text-xs font-bold">
                    ELITE STATUS
                </span>

                <h3 class="text-xl font-bold mt-4">Curator Impact</h3>
                <p class="text-sm opacity-80 mt-1 leading-relaxed">
                    Kamu termasuk top user dalam kontribusi lingkungan.
                </p>
            </div>

            <div class="mt-8">
                <p class="text-5xl font-extrabold tracking-tight">4,820</p>
                <small class="uppercase text-xs text-white/70 tracking-widest block mt-1">Total Points</small>
            </div>
        </div>

    </div>

</main>

<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-2xl w-80 shadow-2xl">
        <h3 id="modalTitle" class="font-bold text-primary mb-4"></h3>
        <input id="modalInput" class="w-full border p-2 rounded-xl mb-4 text-black outline-none focus:border-primary">

        <div class="flex justify-end gap-2 text-sm font-semibold">
            <button onclick="closeModal()" class="px-4 py-2 rounded-xl text-gray-400 hover:bg-gray-100 transition">Batal</button>
            <button onclick="saveData()" class="bg-primary text-white px-4 py-2 rounded-xl hover:bg-[#2d3f1a] transition">Simpan</button>
        </div>
    </div>
</div>

<script>
let currentField = "";

function editField(field) {
    currentField = field;
    document.getElementById("modalTitle").innerText = "Edit " + field;

    const input = document.getElementById("modalInput");
    input.type = "text";

    if (field === "Username") input.value = document.getElementById("usernameText").innerText.trim();
    if (field === "Email") input.value = document.getElementById("emailText").innerText.trim();
    if (field === "Password") {
        input.value = "";
        input.type = "password";
    }
    if (field === "Phone") input.value = document.getElementById("phoneText").innerText.trim();
    if (field === "Location") {
        const loc = document.getElementById("locationText").innerText.trim();
        input.value = loc === "Belum diatur" ? "" : loc;
    }

    document.getElementById("modal").classList.remove("hidden");
    document.getElementById("modal").classList.add("flex");
}

function closeModal() {
    document.getElementById("modal").classList.add("hidden");
    document.getElementById("modal").classList.remove("flex");
}

function saveData() {
    const value = document.getElementById("modalInput").value.trim();
    if (!value) return alert("Tidak boleh kosong!");

    let fieldName = "";

    if (currentField === "Username") fieldName = "username";
    if (currentField === "Email") fieldName = "email";
    if (currentField === "Password") fieldName = "password";
    if (currentField === "Phone") fieldName = "phone";
    if (currentField === "Location") fieldName = "location";

    document.getElementById("fieldInput").value = fieldName;
    document.getElementById("valueInput").value = value;

    document.getElementById("updateForm").submit();
}
</script>

<form id="updateForm" action="{{ route('profil.update') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="field" id="fieldInput">
    <input type="hidden" name="value" id="valueInput">
</form>

</body>
</html>