@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- HEADER INFO PETUGAS -->
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-extrabold text-xl shadow-sm">
                {{ strtoupper(substr($employee->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $employee->name }}</h2>
                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700 border border-indigo-200">
                        {{ strtoupper($employee->role) }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $employee->is_active ?? true ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->is_active ?? true ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Email Login: {{ $employee->email }} | No. HP: {{ $employee->phone ?? '-' }}</p>
            </div>
        </div>

        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
            ← Kembali ke Daftar Karyawan
        </a>
    </div>

    <!-- STATISTIK KERJA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-200">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Shift Kasir Dijalankan</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSessions }} Sesi Shift</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-200">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Omset yang Dihasilkan</span>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- TABEL LOG SHIFT & KERJA PETUGAS LOGIN -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/50">
            <h3 class="text-base font-bold text-gray-900">Riwayat Shift & Nama Petugas Bertugas saat Login</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Nama Petugas Login</th>
                        <th class="px-4 py-3.5 font-semibold">Waktu Buka Shift</th>
                        <th class="px-4 py-3.5 font-semibold">Waktu Tutup Shift</th>
                        <th class="px-4 py-3.5 font-semibold">Modal Awal</th>
                        <th class="px-4 py-3.5 font-semibold">Omset Penjualan</th>
                        <th class="px-4 py-3.5 font-semibold">Selisih Kas</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50/80 transition">
                        <!-- Nama spesifik karyawan yang diketik saat login -->
                        <td class="px-4 py-3.5 font-bold text-gray-900">
                            👤 {{ $session->cashier_name ?? $employee->name }}
                        </td>
                        <td class="px-4 py-3.5 text-gray-600">
                            {{ $session->opened_at->format('d/m/Y H:i') }} WIB
                        </td>
                        <td class="px-4 py-3.5 text-gray-600">
                            {{ $session->closed_at ? $session->closed_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                        </td>
                        <td class="px-4 py-3.5 font-semibold text-gray-800">
                            Rp {{ number_format($session->opening_balance, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 font-semibold text-emerald-600">
                            Rp {{ number_format($session->total_sales, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 font-semibold">
                            @if($session->status === 'closed')
                                <span class="{{ $session->difference < 0 ? 'text-red-600' : ($session->difference > 0 ? 'text-blue-600' : 'text-gray-700') }}">
                                    Rp {{ number_format($session->difference, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($session->status === 'open')
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20 ring-inset">
                                    Sedang Shift
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-500/10 ring-inset">
                                    Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada riwayat shift kerja untuk akun ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection