@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- 1. HEADER & TOMBOL TAMBAH KARYAWAN -->
    <div class="lg:flex lg:items-center lg:justify-between bg-white p-5 rounded-2xl shadow-xs border border-gray-200">
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-x-3">
                <h2 class="text-2xl font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Data Karyawan & Staff
                </h2>
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-indigo-700/10 ring-inset">
                    Manajemen Tim
                </span>
            </div>
            <p class="mt-1 text-xs text-gray-500">Kelola akun kasir/admin, status aktif, dan pantau riwayat performa kerja.</p>
        </div>

        <div class="mt-4 flex lg:mt-0 lg:ml-4">
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-indigo-500 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Tambah Karyawan Baru
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-50 p-4 border border-red-200 text-sm font-semibold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- 2. TABEL DAFTAR KARYAWAN & STATUS AKTIF -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Nama Karyawan</th>
                        <th class="px-4 py-3.5 font-semibold">Kontak & Email</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Role</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Status Akun</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Total Shift</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Total Omset Penjualan</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-gray-50/80 transition">
                        
                        <!-- Nama & Inisial -->
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $employee->name }}</p>
                                    <p class="text-[11px] text-gray-400">ID Akun: #{{ $employee->id }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Email & Phone -->
                        <td class="px-4 py-3.5">
                            <p class="text-gray-900 font-medium text-xs">{{ $employee->email }}</p>
                            <p class="text-[11px] text-gray-500">{{ $employee->phone ?? '-' }}</p>
                        </td>

                        <!-- Role -->
                        <td class="px-4 py-3.5 text-center">
                            @if($employee->role === 'admin')
                                <span class="inline-flex items-center rounded-md bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700 ring-1 ring-purple-600/20 ring-inset">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-600/20 ring-inset">
                                    Kasir
                                </span>
                            @endif
                        </td>

                        <!-- STATUS AKTIF / NON-AKTIF + TOGGLE -->
                        <td class="px-4 py-3.5 text-center">
                            <form method="POST" action="{{ route('admin.employees.toggle-status', $employee) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        title="Klik untuk mengubah status"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition cursor-pointer {{ $employee->is_active ?? true ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    <span class="w-2 h-2 rounded-full {{ $employee->is_active ?? true ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $employee->is_active ?? true ? 'Aktif' : 'Non-Aktif' }}
                                </button>
                            </form>
                        </td>

                        <!-- Total Shift -->
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700">
                                {{ $employee->cash_sessions_count }} Sesi
                            </span>
                        </td>

                        <!-- Total Omset -->
                        <td class="px-4 py-3.5 text-right font-bold text-emerald-600">
                            Rp {{ number_format($employee->total_omset ?? 0, 0, ',', '.') }}
                        </td>

                        <!-- AKSI DETAIL & EDIT -->
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                
                                <!-- Tombol Detail Lihat Kerjanya -->
                                <a href="{{ route('admin.employees.show', $employee) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Kerja
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">
                                    Edit
                                </a>

                                <!-- Hapus -->
                                @if($employee->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada data karyawan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection