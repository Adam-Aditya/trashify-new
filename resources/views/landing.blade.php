<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

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

<body class="bg-light text-grey-800">

<!-- NAVBAR -->
<nav class="fixed w-full bg-[#3D5524] shadow z-50">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-4 py-4">

        <div class="flex items-center gap-2">
            <!-- Gunakan asset() agar path gambar aman di Laravel -->
            <img src="{{ asset('resource/logo trashify.png') }}" 
                alt="Logo Trashify" 
                class="w-8 h-8 object-contain">
            <h1 class="text-xl font-extrabold text-white">Trashify</h1>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('login.user') }}"
            class="px-4 py-2 text-sm text-white/80 hover:bg-white/20 rounded-lg flex items-center">
                Login
            </a>
            <a href="{{ route('pengepul.auth') }}" class="px-5 py-2 bg-white text-primary rounded-xl font-semibold hover:bg-gray-100 flex items-center transition">
                Jadi Mitra
            </a>
        </div>

    </div>
</nav>

<!-- HERO -->
<section class="relative pt-32 pb-20 px-6 max-w-1xl mx-auto grid md:grid-cols-2 gap-10 items-center overflow-hidden">

    <div class="absolute inset-0 
        bg-gradient-to-r 
        from-[#3D5524] 
        via-[#709867] 
        to-[#9BB863]">
    </div>

    <img src="{{ asset('resource/botol.jpg') }}" 
        class="absolute inset-0 w-full h-full object-cover opacity-10 pointer-events-none">
        
    <div class="relative z-10 ml-8 md:ml-20">
        <h1 class="text-5xl font-extrabold text-white mb-4">
            Trashify
        </h1>

        <p class="text-white/90 mb-6">
            Platform pintar untuk mengelola sampah, mendapatkan poin,
            dan membantu lingkungan jadi lebih bersih.
        </p>

        <div class="flex gap-4">
            <a href="{{ route('login.user') }}" 
            class="px-6 py-3 border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-primary transition text-center inline-block">
                Gabung Sebagai User
            </a>

            <a 
            <a href="{{ route('pengepul.auth') }}" 
            class="px-6 py-3 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition text-center">
                Jadi Mitra
            </a>
        </div>
    </div>

    <!-- MOCKUP IMAGE -->
    <div class="flex justify-center relative z-10">
        <img src="{{ asset('resource/Homepage pengguna-portrait.png') }}" 
            alt="Trashify App Preview"
            class="w-[280px] md:w-[320px] hover:scale-105 transition duration-300">
    </div>

</section>

<!-- FEATURES -->
<section class="bg-white py-24 px-6">
    <div class="max-w-6xl mx-auto text-center mb-16">
        <h2 class="text-4xl font-extrabold text-primary tracking-tight">
            Fitur Utama Trashify
        </h2>
        <p class="text-gray-500 mt-3 text-lg max-w-2xl mx-auto">
            Mengintegrasikan teknologi modern ke dalam pengelolaan sampah harian Anda. Nikmati kemudahan menjaga lingkungan sekaligus mendatangkan keuntungan finansial.
        </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

        <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 text-primary text-xl font-bold">01</div>
                <h3 class="font-bold text-xl text-primary mb-3">Smart Sorting Technology</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Identifikasi jenis sampah Anda (organik, anorganik, atau B3) secara instan dan akurat. Sistem cerdas kami membantu meminimalkan kesalahan pemilahan agar proses daur ulang menjadi jauh lebih optimal dan bernilai tinggi.
                </p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 text-primary text-xl font-bold">02</div>
                <h3 class="font-bold text-xl text-primary mb-3">On-Demand Pickup System</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Tidak perlu lagi repot keluar rumah atau mencari tempat pembuangan akhir. Cukup tentukan lokasi penjemputan via aplikasi, dan mitra pengepul resmi terdekat kami akan langsung datang ke lokasi Anda sesuai jadwal yang diinginkan.
                </p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 text-primary text-xl font-bold">03</div>
                <h3 class="font-bold text-xl text-primary mb-3">Reward Points & Eco-Wallet</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Ubah sampah menjadi berkah finansial. Setiap kilogram sampah terpilah yang Anda setorkan akan langsung dikonversi menjadi poin digital. Tukarkan poin tersebut menjadi saldo uang tunai, e-wallet, atau berbagai voucher belanja menarik.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="py-20 px-6 text-center">
    <div class="max-w-3xl mx-auto 
        bg-gradient-to-r 
        from-[#3D5524] 
        via-[#709867] 
        to-[#9BB863]
        text-white p-10 rounded-2xl shadow-lg">

        <h2 class="text-3xl font-bold mb-4">
            Mulai kontribusi sekarang
        </h2>

        <p class="mb-6 opacity-90">
            Setiap sampah yang kamu kelola membantu bumi jadi lebih baik.
        </p>

        <button class="bg-white text-[#3D5524] px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
            Mulai Sekarang
        </button>

    </div>
</section>

<!-- FOOTER -->
<footer class="bg-white py-8 text-center text-gray-500">
    <p>© {{ date('Y') }} Trashify.</p> <!-- Menggunakan helper date bawaan PHP -->
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll("button");

    buttons.forEach(btn => {
        if (btn.innerText.trim() === "Mulai Sekarang") {
            btn.addEventListener("click", function () {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        }
    });
});
</script>
</body>
</html>