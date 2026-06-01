<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tukar Poin - Trashify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>

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
    .selected {
        border-color: #3D5524 !important;
        box-shadow: 0 0 0 3px rgba(61,85,36,0.2);
    }
    </style>
</head>

<body class="bg-[#f7faf8] text-gray-800 min-h-screen font-jakarta">

<nav class="w-full sticky top-0 z-50 bg-white shadow">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 h-20">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 transition flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <span class="text-2xl font-extrabold text-primary">Trashify</span>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 pt-12 pb-24">

    @if($errors->any())
        <div class="bg-red-600 text-white p-4 rounded-xl text-sm shadow mb-6 max-w-4xl">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-primary mb-2">Poin Anda</h1>
        <p class="text-gray-500">Setiap poin yang Anda kumpulkan berasal dari aksi kecil menjaga bumi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-8">

            <div class="bg-gradient-to-r from-[#3D5524] via-[#709867] to-[#9BB863] rounded-xl p-8 text-white shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm opacity-80">Total Poin</p>
                        <p class="text-5xl font-extrabold mt-1">{{ number_format($poin, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-80">Setara Finansial</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($poin, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('poin.proses') }}" method="POST" class="space-y-8">
                @csrf

                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-primary mb-4">Nomor E-Wallet</h2>
                    <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->phone) }}" required
                           class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:border-primary transition font-medium"
                           placeholder="Masukkan nomor HP akun E-Wallet Anda">
                </section>

                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-primary mb-4">Pilih Provider</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <button type="button" onclick="selectProvider(this,'GoPay')" class="provider p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center gap-3 transition hover:shadow-md">
                            <img src="{{ asset('resource/gopay.png') }}" class="w-12 h-12 object-contain" onerror="this.src='https://img.icons8.com/color/96/wallet.png'">
                            <span class="font-semibold text-sm">GoPay</span>
                        </button>
                        <button type="button" onclick="selectProvider(this,'OVO')" class="provider p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center gap-3 transition hover:shadow-md">
                            <img src="{{ asset('resource/OVO.png') }}" class="w-12 h-12 object-contain" onerror="this.src='https://img.icons8.com/color/96/wallet.png'">
                            <span class="font-semibold text-sm">OVO</span>
                        </button>
                        <button type="button" onclick="selectProvider(this,'DANA')" class="provider p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center gap-3 transition hover:shadow-md">
                            <img src="{{ asset('resource/dana.png') }}" class="w-12 h-12 object-contain" onerror="this.src='https://img.icons8.com/color/96/wallet.png'">
                            <span class="font-semibold text-sm">DANA</span>
                        </button>
                        <button type="button" onclick="selectProvider(this,'ShopeePay')" class="provider p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center gap-3 transition hover:shadow-md">
                            <img src="{{ asset('resource/shopeepay.png') }}" class="w-12 h-12 object-contain" onerror="this.src='https://img.icons8.com/color/96/wallet.png'">
                            <span class="font-semibold text-sm">ShopeePay</span>
                        </button>
                    </div>
                    <input type="hidden" name="provider" id="providerInput" required>
                </section>

                <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-primary mb-4">Pilih Nominal Penukaran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $nominals = [10000, 25000, 50000, 100000, 200000, 500000];
                        @endphp
                        @foreach($nominals as $n)
                            @php $disabled = ($poin < $n) ? "opacity-40 pointer-events-none bg-gray-50" : "cursor-pointer hover:shadow-md"; @endphp
                            <div onclick="selectNominal(this, {{ $n }})" class="nominal p-5 bg-white rounded-xl border border-gray-100 transition font-bold text-gray-700 flex justify-between items-center {{ $disabled }}">
                                <span>Rp {{ number_format($n, 0, ',', '.') }}</span>
                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-500 rounded-lg">{{ number_format($n) }} Poin</span>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="nominal" id="nominalInput" required>
                </section>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-28">
                <h3 class="text-lg font-bold text-primary mb-6">Ringkasan Penukaran</h3>
                <div class="space-y-4 text-sm font-medium text-gray-600">
                    <div class="flex justify-between border-b pb-2">
                        <span>Provider</span>
                        <span id="summaryProvider" class="text-primary font-bold">-</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span>Nominal</span>
                        <span id="summaryNominal" class="text-primary font-bold">-</span>
                    </div>
                    <div class="flex justify-between pb-1">
                        <span>Poin Dipakai</span>
                        <span id="summaryPoin" class="text-red-600 font-bold">-</span>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 bg-primary text-white py-3.5 rounded-xl font-bold hover:bg-[#2d3f1a] transition shadow-md">
                    Konfirmasi Penukaran
                </button>
            </div>
        </div>
        </form>
    </div>
</main>

<script>
function selectProvider(el, name){
    document.querySelectorAll('.provider').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('summaryProvider').innerText = name;
    document.getElementById('providerInput').value = name;
}

function selectNominal(el, value){
    document.querySelectorAll('.nominal').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('summaryNominal').innerText = "Rp " + value.toLocaleString('id-ID');
    document.getElementById('summaryPoin').innerText = "-" + value.toLocaleString('id-ID') + " Poin";
    document.getElementById('nominalInput').value = value;
}
</script>

</body>
</html>