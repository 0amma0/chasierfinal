<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login - Toko Roti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 text-gray-800">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        
        <!-- Header Login -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Login CokoBell</h2>
            <p class="text-xs text-gray-500 mt-1">Admin dan kasir masuk dari halaman yang sama</p>
        </div>

        <!-- Alert Error Session -->
        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-600 font-semibold text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Alert Validation Errors -->
        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-600">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700">Email Akun:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="nama@email.com"
                       class="w-full p-3 border border-gray-200 rounded-xl text-sm mt-1 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700">Password:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full p-3 border border-gray-200 rounded-xl text-sm mt-1 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
            </div>

            <!-- Nama Karyawan (opsional, untuk kasir) -->
            <div>
                <label for="nama_karyawan" class="block text-xs font-semibold text-gray-700">Nama Karyawan (kasir):</label>
                <input type="text" 
                       id="nama_karyawan" 
                       name="nama_karyawan" 
                       value="{{ old('nama_karyawan') }}" 
                       placeholder="Isi jika login sebagai kasir"
                       class="w-full p-3 border border-gray-200 rounded-xl text-sm mt-1 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                <p class="text-[11px] text-gray-400 mt-1">Role ditentukan otomatis dari akun. Field ini untuk nama petugas di struk.</p>
            </div>

            <!-- Tombol Login -->
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition cursor-pointer shadow-md hover:shadow-indigo-500/20 mt-2">
                Login
            </button>
        </form>

        <!-- Tombol Kembali ke Landing Page -->
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-xs font-medium text-gray-400 hover:text-indigo-600 transition">
                ← Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>