@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 mb-1">Tambah Karyawan Baru</h2>
        <p class="text-xs text-gray-500 mb-6">Buat akun untuk staf kasir atau admin baru.</p>

        <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 p-2.5 border">
                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Email / Username Login *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 p-2.5 border">
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789" class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 p-2.5 border">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Jabatan / Role *</label>
                <select name="role" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 p-2.5 border">
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-indigo-500 p-2.5 border">
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-xs font-semibold text-white hover:bg-indigo-500 transition">Simpan Karyawan</button>
            </div>
        </form>
    </div>
</div>
@endsection