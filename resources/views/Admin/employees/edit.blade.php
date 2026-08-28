@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200">
        
        <!-- Header Form -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Edit Data Karyawan</h2>
            <p class="text-xs text-gray-500">Perbarui informasi akun, nomor kontak, atau peran dari staf kasir/admin.</p>
        </div>

        <!-- Alert Error Validasi -->
        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" 
                       id="name"
                       name="name" 
                       value="{{ old('name', $employee->name) }}" 
                       required 
                       class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Toko / Username Login *</label>
                <input type="email" 
                       id="email"
                       name="email" 
                       value="{{ old('email', $employee->email) }}" 
                       required 
                       class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" 
                       id="phone"
                       name="phone" 
                       value="{{ old('phone', $employee->phone) }}" 
                       placeholder="08123456789" 
                       class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
            </div>

            <!-- Role / Jabatan -->
            <div>
                <label for="role" class="block text-xs font-semibold text-gray-700 mb-1">Jabatan / Role *</label>
                <select id="role" 
                        name="role" 
                        required 
                        class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border bg-white">
                    <option value="kasir" {{ old('role', $employee->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="admin" {{ old('role', $employee->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Password (Opsional) -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Password Baru (Opsional)</label>
                <input type="password" 
                       id="password"
                       name="password" 
                       placeholder="Kosongkan jika tidak ingin mengganti password" 
                       class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
                <span class="text-[11px] text-gray-400 mt-1 block">Minimal 6 karakter jika diisi.</span>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.employees.index') }}" 
                   class="px-4 py-2 rounded-xl bg-gray-100 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white transition shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection