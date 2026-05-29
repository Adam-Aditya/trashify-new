<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Trashify</title>

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
</head>

<body class="bg-[#e9f0e4] flex items-center justify-center min-h-screen p-5 font-jakarta">

<div class="bg-white w-full max-w-5xl min-h-[650px] flex rounded-[30px] shadow-2xl overflow-hidden">

    <div class="flex-1 flex flex-col justify-center items-center relative bg-white">
        <div class="absolute w-[250px] h-[250px] border-[25px] border-[#d4e3cc] rounded-full -top-20 -left-20"></div>

        <div class="text-center z-10">
            <img src="{{ asset('resource/logo trashify.png') }}" class="w-[150px] mx-auto drop-shadow-lg" alt="Logo Trashify">
            <h1 class="text-primary text-5xl font-extrabold mt-4">Trashify</h1>
        </div>

        <div class="absolute w-[250px] h-[250px] border-[25px] border-[#b8ccae] rounded-full -bottom-20 -right-20"></div>
    </div>

    <div class="flex-[1.1] bg-gradient-to-br from-gradStart to-gradEnd p-12 flex flex-col justify-center items-center text-white">

        <div class="w-full max-w-sm mb-4">
            @if (session('success'))
                <div class="bg-green-600 text-white p-3 rounded-xl text-sm mb-2 text-center shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-600 text-white p-3 rounded-xl text-sm mb-2 shadow">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="w-full max-w-sm text-center {{ $errors->has('loginError') || !old('email') ? '' : 'hidden' }}" id="login-section">
            <h2 class="text-3xl mb-8 font-semibold">Log in</h2>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="w-full outline-none text-black" required>
                </div>

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="password" name="password" placeholder="Password" class="w-full outline-none text-black" required>
                </div>

                <button type="submit" class="bg-primary w-full py-3 rounded-full font-bold hover:bg-[#2d3f1a] transition">
                    Login
                </button>
            </form>

            <p class="text-sm mt-6">
                tidak punya akun?
                <a href="javascript:void(0)" onclick="toggleForm('signup')" class="text-primary font-bold">
                    daftar disini!
                </a>
            </p>
        </div>

        <div class="w-full max-w-sm text-center {{ old('email') && !$errors->has('loginError') ? '' : 'hidden' }}" id="signup-section">
            <h2 class="text-3xl mb-8 font-semibold">Sign up</h2>

            <form action="{{ route('register.process') }}" method="POST">
                @csrf

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="w-full outline-none text-black" required>
                </div>

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full outline-none text-black" required>
                </div>

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="password" name="password" placeholder="Password" class="w-full outline-none text-black" required>
                </div>

                <div class="bg-white rounded-full mb-4 px-6 py-3 shadow">
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="No.Telp" class="w-full outline-none text-black" required>
                </div>

                <button type="submit" class="bg-primary w-full py-3 rounded-full font-bold hover:bg-[#2d3f1a] transition">
                    Sign up
                </button>
            </form>

            <p class="text-sm mt-6">
                sudah punya akun?
                <a href="javascript:void(0)" onclick="toggleForm('login')" class="text-primary font-bold">
                    login disini!
                </a>
            </p>
        </div>

    </div>
</div>

<script>
function toggleForm(target) {
    const loginSec = document.getElementById('login-section');
    const signupSec = document.getElementById('signup-section');

    if (target === 'signup') {
        loginSec.classList.add('hidden');
        signupSec.classList.remove('hidden');
    } else {
        signupSec.classList.add('hidden');
        loginSec.classList.remove('hidden');
    }
}
</script>

</body>
</html>