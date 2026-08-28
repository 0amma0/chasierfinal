@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- HEADER SECTION -->
    <div class="lg:flex lg:items-center lg:justify-between bg-white p-5 rounded-2xl shadow-xs border border-gray-200">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl sm:truncate">Riwayat Sesi Kasir</h2>
        </div>
        <div class="mt-4 flex lg:mt-0 lg:ml-4 gap-3">
            <span class="inline-flex items-center rounded-xl bg-gray-100 px-4 py-2.5 text-xs font-semibold text-gray-600">
                Monitor laporan karyawan & kelola riwayat
            </span>
        </div>
    </div>

    <!-- FLASH ALERT -->
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

    <!-- FILTER SECTION -->
    <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-200">
        <form method="GET" action="{{ route('admin.cash-sessions.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="cashier_name" value="{{ request('cashier_name') }}" placeholder="Cari nama kasir bertugas..." class="w-full sm:w-64 text-sm p-2.5 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-gray-800 transition cursor-pointer">
                Filter
            </button>
            @if(request()->has('cashier_name'))
                <a href="{{ route('admin.cash-sessions.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABLE SECTION -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Sesi ID</th>
                        <th class="px-4 py-3.5 font-semibold">Kasir Bertugas</th>
                        <th class="px-4 py-3.5 font-semibold">Waktu Dibuka</th>
                        <th class="px-4 py-3.5 font-semibold">Waktu Ditutup</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Modal Awal</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Total Penjualan</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-4 py-3.5 font-bold text-gray-900">#{{ $session->id }}</td>
                        <td class="px-4 py-3.5 font-semibold text-gray-900">
                            {{ $session->cashier_name ?? $session->user?->name ?? 'Kasir' }}
                        </td>
                        <td class="px-4 py-3.5 text-gray-600">
                            {{ $session->opened_at ? $session->opened_at->format('d/m/Y H:i') : '-' }} WIB
                        </td>
                        <td class="px-4 py-3.5 text-gray-600">
                            {{ $session->closed_at ? $session->closed_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-medium text-gray-800">
                            Rp {{ number_format($session->opening_balance, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-bold text-emerald-600">
                            Rp {{ number_format($session->total_sales ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($session->status === 'open')
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20 ring-inset">
                                    Sedang Berjalan
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-500/10 ring-inset">
                                    Sudah Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.cash-sessions.show', $session) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                    Detail
                                </a>
                                <form action="{{ route('admin.cash-sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('Hapus sesi kasir ini beserta transaksinya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                            Belum ada riwayat sesi kasir.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($sessions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection