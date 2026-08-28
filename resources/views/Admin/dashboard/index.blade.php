@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- 🟢 1. HEADER & STATUS KASIR CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-x-3">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard POS</h1>
                    @if($activeCashSession)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Kasir Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Kasir Nonaktif
                        </span>
                    @endif
                </div>

                <div class="mt-2 text-sm text-gray-600">
                    @if($activeCashSession)
                        Kasir dibuka oleh <strong class="text-gray-900">{{ $activeCashSession->user->name }}</strong> 
                        pukul <strong class="text-gray-900">{{ $activeCashSession->opened_at->format('H:i') }} WIB</strong>
                    @else
                        Belum ada sesi kasir yang dibuka hari ini. Silakan buka kasir untuk memulai transaksi.
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi Kasir -->
            <div class="flex items-center gap-3">
                @if($activeCashSession)
                    <a href="{{ route('admin.cash-sessions.show', $activeCashSession) }}" class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 transition">
                        Detail Sesi
                    </a>
                    <a href="{{ route('admin.cash-sessions.close-form', $activeCashSession) }}" class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 transition">
                        Tutup Kasir
                    </a>
                @else
                    <a href="{{ route('admin.cash-sessions.open-form') }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-emerald-500 transition">
                        + Buka Kasir Sekarang
                    </a>
                @endif
            </div>
        </div>

        <!-- Rincian Kasir Jika Aktif -->
        @if($activeCashSession)
            <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200/60">
                    <span class="text-xs text-gray-500 font-medium">Modal Awal Sesi</span>
                    <p class="text-lg font-bold text-gray-900 mt-0.5">Rp {{ number_format($activeCashSession->opening_balance, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200/60">
                    <span class="text-xs text-gray-500 font-medium">Penjualan Sesi Ini</span>
                    <p class="text-lg font-bold text-emerald-600 mt-0.5">Rp {{ number_format($activeCashSession->total_sales, 0, ',', '.') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- 🟢 2. STATS SECTION -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-6 px-6">
        <dl class="grid grid-cols-1 gap-x-8 gap-y-8 text-center sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
            <div class="mx-auto flex max-w-xs flex-col gap-y-1 w-full pt-4 sm:pt-0">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Penjualan Hari Ini</dt>
                <dd class="order-first text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Rp {{ number_format($todaySales, 0, ',', '.') }}
                </dd>
            </div>

            <div class="mx-auto flex max-w-xs flex-col gap-y-1 w-full pt-4 sm:pt-0">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Penjualan Bulan Ini</dt>
                <dd class="order-first text-2xl font-bold tracking-tight text-emerald-600 sm:text-3xl">
                    Rp {{ number_format($monthSales, 0, ',', '.') }}
                </dd>
            </div>

            <div class="mx-auto flex max-w-xs flex-col gap-y-1 w-full pt-4 sm:pt-0">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Penjualan Bulan Lalu</dt>
                <dd class="order-first text-2xl font-bold tracking-tight text-gray-700 sm:text-3xl">
                    Rp {{ number_format($lastMonthSales, 0, ',', '.') }}
                </dd>
            </div>

            <div class="mx-auto flex max-w-xs flex-col gap-y-1 w-full pt-4 sm:pt-0">
                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Growth vs Bulan Lalu</dt>
                <dd class="order-first text-2xl font-bold tracking-tight sm:text-3xl flex items-center justify-center gap-1">
                    @if($growthPercent > 0)
                        <span class="text-emerald-600">↑ {{ number_format($growthPercent, 1) }}%</span>
                    @elseif($growthPercent < 0)
                        <span class="text-red-600">↓ {{ number_format(abs($growthPercent), 1) }}%</span>
                    @else
                        <span class="text-gray-500">0%</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    <!-- 🟢 3. LAPORAN PENJUALAN 3 BULAN TERAKHIR + TOMBOL EXPORT EXCEL -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 block">Laporan Penjualan</span>
                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight mt-0.5">3 BULAN TERAKHIR</h3>
            </div>
            
            <!-- 🟢 Tombol Export Excel -->
            <a href="{{ route('admin.dashboard.export') }}" 
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel (.xlsx/.csv)
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 font-semibold">Bulan / Periode</th>
                        <th class="px-6 py-3.5 font-semibold text-right">Total Penjualan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($chartData as $item)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-base">{{ $item['label'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['sub_label'] }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right font-extrabold text-gray-900 text-base">
                            Rp {{ number_format($item['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-gray-400">
                            Belum ada data transaksi penjualan 3 bulan terakhir.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🟢 4. TWO COLUMN GRID: PRODUK TERLARIS & STOK MENIPIS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Produk Terlaris -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Produk Terlaris</h3>
                <span class="text-xs text-gray-500 font-semibold">Top 5 Performer</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Produk</th>
                            <th class="px-6 py-3.5 font-semibold text-right">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topProducts as $item)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-3.5 font-medium text-gray-900">
                                {{ $item->product->name ?? 'Produk dihapus' }}
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    {{ $item->total_qty }} pcs
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-8 text-center text-gray-400">
                                Belum ada data penjualan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stok Mau Habis (Dengan Tombol Restok) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Stok Menipis</h3>
                <span class="text-xs text-amber-600 font-semibold">Perlu Restok</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Produk</th>
                            <th class="px-4 py-3.5 font-semibold text-center">Sisa Stok</th>
                            <th class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($lowStockProducts as $product)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-3.5 font-medium text-gray-900">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    {{ $product->stock }} pcs
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('admin.stock.form', $product) }}" 
                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-100 transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Restok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                Aman, tidak ada produk yang stoknya menipis.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection