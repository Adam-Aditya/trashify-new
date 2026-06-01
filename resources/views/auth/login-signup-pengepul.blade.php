<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup Pengepul - Trashify</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
                        jakarta: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: linear-gradient(135deg, #709867 0%, #9BB863 100%);
            backdrop-filter: blur(10px);
        }
        .bg-trashify {
            background-color: #f8faf8;
            background-image: url('https://www.transparenttextures.com/patterns/leaf.png');
        }
    </style>
</head>

<body class="bg-trashify min-h-screen font-jakarta text-gray-800">

<nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto">
    <div class="flex items-center gap-2">
        <img src="{{ asset('resource/logo trashify.png') }}" class="w-10 h-10 object-contain" onerror="this.src='https://img.icons8.com/color/96/recycle-sign.png'">
        <span class="text-2xl font-bold text-primary tracking-tight">Trashify</span>
    </div>

    <div class="space-x-4">
        <button onclick="showPage('login')" class="px-6 py-2 font-medium text-primary">Login</button>
        <button onclick="showPage('signup')" class="px-6 py-2 text-white rounded-full bg-gradient-to-r from-gradStart to-gradEnd">
            Sign Up
        </button>
    </div>
</nav>

<main class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-center px-8 py-12 gap-16">

<div class="lg:w-1/2 text-center lg:text-left relative">
    <h1 class="text-5xl lg:text-6xl font-extrabold text-primary mb-6">
        Kelola Sampah Jadi Lebih Bermakna.
    </h1>
    <p class="text-lg text-gray-600 mb-8">
        Bergabunglah dengan Trashify untuk menciptakan lingkungan yang lebih bersih.
    </p>
</div>

<div class="w-full max-w-md">

    <div id="login-card" class="glass-card p-8 rounded-[2rem] shadow-2xl text-white">
        <h2 class="text-3xl font-bold text-center mb-8">Log in</h2>

        <form id="loginForm" action="{{ route('pengepul.login.process') }}" class="space-y-4">
            @csrf <input type="text" name="username" placeholder="Username/Email"
            class="w-full px-4 py-3 rounded-full bg-white text-gray-800 outline-none" required>

            <input type="password" name="password" placeholder="Password"
            class="w-full px-4 py-3 rounded-full bg-white text-gray-800 outline-none" required>

            <button type="submit" class="w-full py-3 bg-primary rounded-full font-bold hover:bg-[#2d3f1a] transition">
                Login
            </button>

            <p class="text-center text-sm mt-6">
                tidak punya akun?
                <span onclick="showPage('signup')" class="font-bold cursor-pointer underline">
                    daftar disini!
                </span>
            </p>
        </form>
    </div>

    <div id="signup-card" class="glass-card p-8 rounded-[2rem] shadow-2xl text-white hidden">
        <h2 class="text-3xl font-bold text-center mb-6">Sign up</h2>

        <form id="signupForm" action="{{ route('pengepul.register.process') }}" class="space-y-3">
            @csrf <input type="text" name="username" placeholder="Username" required
            class="w-full px-4 py-2.5 rounded-full bg-white text-gray-800 outline-none">

            <input type="email" name="email" placeholder="Email" required
            class="w-full px-4 py-2.5 rounded-full bg-white text-gray-800 outline-none">

            <input type="password" name="password" placeholder="Password" required
            class="w-full px-4 py-2.5 rounded-full bg-white text-gray-800 outline-none">

            <input type="text" name="phone" placeholder="No. Telp" required
            class="w-full px-4 py-2.5 rounded-full bg-white text-gray-800 outline-none">

            <button type="submit" class="w-full py-3 bg-primary rounded-full font-bold hover:bg-[#2d3f1a] transition">
                Sign up
            </button>

            <p class="text-center text-sm mt-4">
                sudah punya akun?
                <span onclick="showPage('login')" class="font-bold cursor-pointer underline">
                    login disini!
                </span>
            </p>
        </form>
    </div>

</div>
</main>

<script>
function showPage(page) {
    document.getElementById('login-card').classList.toggle('hidden', page === 'signup');
    document.getElementById('signup-card').classList.toggle('hidden', page === 'login');
}

// LOGIN AJAX LARAVEL
document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    fetch(this.action, { // DIUBAH: Mengambil otomatis dari atribut action form
        method: "POST",
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = data.redirect; // Lompat ke halaman kategori toko
        } else {
            alert(data.message);
        }
    })
    .catch(err => alert("Terjadi gangguan koneksi server."));
});

// SIGNUP AJAX LARAVEL
document.getElementById("signupForm").addEventListener("submit", function(e){
    e.preventDefault();

    fetch(this.action, { // DIUBAH: Mengambil otomatis dari atribut action form
        method: "POST",
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = data.redirect; // Lompat ke halaman kategori toko
        } else {
            alert("Registrasi Gagal, periksa kembali kelengkapan data.");
        }
    })
    .catch(err => alert("Terjadi gangguan koneksi server."));
});
</script>

</body>
</html>