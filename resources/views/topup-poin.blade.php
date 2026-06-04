<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Top Up Poin - Trashify Mitra</title>
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
            <a href="{{ route('pengepul.dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 transition flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <span class="text-2xl font-extrabold text-primary">Trashify <span class="text-xs font-semibold px-2 py-0.5 bg-primary/10 text-primary rounded-full ml-1">Mitra</span></span>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 pt-12 pb-24">

    <div class="max-w-4xl mx-auto mb-6">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded-xl text-sm shadow mb-6">
                ✨ {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-600 text-white p-4 rounded-xl text-sm shadow mb-6">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-primary mb-2">Top Up Poin</h1>
        <p class="text-gray-500">Isi saldo poin operasional Anda untuk mempermudah transaksi pembayaran sampah masuk dari Eco User.</p>
    </div>

    <form action="{{ route('pengepul.poin.proses') }}" method="POST">
        @csrf <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">

                <div class="bg-gradient-to-r from-[#3D5524] via-[#709867] to-[#9BB863] rounded-xl p-8 text-white shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80">Saldo Poin Sekarang</p>
                            <p class="text-5xl font-extrabold mt-1">{{ number_format(Auth::guard('pengepul')->user()->poin ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80">Nilai Konversi Kas</p>
                            <p class="text-2xl font-bold mt-1">Rp {{ number_format(Auth::guard('pengepul')->user()->poin ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-primary mb-4">Nomor Rekening / Sumber Dana Bank</h2>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', Auth::guard('pengepul')->user()->phone) }}" required
                               class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:border-primary transition font-medium"
                               placeholder="Masukkan nomor rekening bank asal transfer Anda">
                    </section>

                    <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-primary mb-4">Pilih Metode Pembayaran</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <button type="button" onclick="selectMethod(this,'BCA Virtual Account')" class="payment-method p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center text-center gap-3 transition hover:shadow-md">
                                <img src="https://img.icons8.com/color/96/bank.png" class="w-12 h-12 object-contain">
                                <span class="font-semibold text-sm">BCA VA</span>
                            </button>
                            <button type="button" onclick="selectMethod(this,'Mandiri Virtual Account')" class="payment-method p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center text-center gap-3 transition hover:shadow-md">
                                <img src="https://img.icons8.com/color/96/bank.png" class="w-12 h-12 object-contain">
                                <span class="font-semibold text-sm">Mandiri VA</span>
                            </button>
                            <button type="button" onclick="selectMethod(this,'BRI Virtual Account')" class="payment-method p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center text-center gap-3 transition hover:shadow-md">
                                <img src="https://img.icons8.com/color/96/bank.png" class="w-12 h-12 object-contain">
                                <span class="font-semibold text-sm">BRI VA</span>
                            </button>
                            <button type="button" onclick="selectMethod(this,'QRIS / Bank Lain')" class="payment-method p-6 bg-white rounded-xl border border-gray-100 flex flex-col items-center text-center gap-3 transition hover:shadow-md">
                                <img src="https://img.icons8.com/color/96/qr-code.png" class="w-12 h-12 object-contain">
                                <span class="font-semibold text-sm">QRIS</span>
                            </button>
                        </div>
                        <input type="hidden" name="metode" id="metodeInput" required>
                    </section>

                    <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-primary mb-4">Pilih Nominal Top Up</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $nominals = [50000, 100000, 250000, 500000, 1000000, 2500000];
                            @endphp
                            @foreach($nominals as $n)
                                <div onclick="selectNominal(this, '{{ $n }}')" class="nominal p-5 bg-white rounded-xl border border-gray-100 transition font-bold text-gray-700 flex justify-between items-center cursor-pointer hover:shadow-md">
                                    <span>Rp {{ number_format($n, 0, ',', '.') }}</span>
                                    <span class="text-xs font-semibold px-2 py-1 bg-primary/10 text-primary rounded-lg">+{{ number_format($n, 0, ',', '.') }} Poin</span>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="nominal" id="nominalInput" required>
                    </section>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-28">
                    <h3 class="text-lg font-bold text-primary mb-6">Ringkasan Top Up</h3>
                    <div class="space-y-4 text-sm font-medium text-gray-600">
                        <div class="flex justify-between border-b pb-2">
                            <span>Metode</span>
                            <span id="summaryMethod" class="text-primary font-bold">-</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span>Harga Beli</span>
                            <span id="summaryNominal" class="text-primary font-bold">-</span>
                        </div>
                        <div class="flex justify-between pb-1">
                            <span>Poin Diperoleh</span>
                            <span id="summaryPoin" class="text-green-600 font-bold">-</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-primary text-white py-3.5 rounded-xl font-bold hover:bg-[#2d3f1a] transition shadow-md">
                        Konfirmasi Top Up
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function selectMethod(el, name){
    document.querySelectorAll('.payment-method').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('summaryMethod').innerText = name;
    document.getElementById('metodeInput').value = name;
}

function selectNominal(el, value){
    document.querySelectorAll('.nominal').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    
    document.getElementById('summaryNominal').innerText = "Rp " + formatRupiah(value);
    document.getElementById('summaryPoin').innerText = "+" + formatRupiah(value) + " Poin";
    document.getElementById('nominalInput').value = value;
}
</script>

</body>
</html>