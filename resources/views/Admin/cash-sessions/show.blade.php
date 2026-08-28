@extends('layouts.admin')

@section('header_title', 'Detail Sesi Kasir #{{ $cashSession->id }}')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Sesi Kasir</h1>
        <a href="{{ route('admin.cash-sessions.index') }}" class="text-sm font-medium text-gray-400 hover:text-indigo-600 transition">
            ← Kembali
        </a>
    </div>

    <!-- Info Sesi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                @if($cashSession->status === 'open')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 mt-1">Buka</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 mt-1">Ditutup</span>
                @endif
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $cashSession->cashier_name }}</p>
                <p class="text-xs text-gray-500">{{ $cashSession->user->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuka</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $cashSession->opened_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditutup</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">
                    {{ $cashSession->closed_at ? $cashSession->closed_at->format('d M Y H:i') : 'Belum ditutup' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-6">
            <div class="bg-gray-50 p-4 rounded-xl">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Awal</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($cashSession->opening_balance, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Penjualan</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($cashSession->total_sales, 0, ',', '.') }}</p>
            </div>
            <div class="bg-indigo-50 p-4 rounded-xl">
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Saldo Diharapkan</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">Rp {{ number_format($cashSession->expected_balance, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($cashSession->status === 'closed')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-6 mt-6">
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Akhir</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($cashSession->closing_balance, 0, ',', '.') }}</p>
                </div>
                <div class="bg-{{ $cashSession->difference >= 0 ? 'red-50' : 'emerald-50' }} p-4 rounded-xl">
                    <p class="text-xs font-semibold {{ $cashSession->difference >= 0 ? 'text-red-600' : 'text-emerald-600' }} uppercase tracking-wider">Selisih</p>
                    <p class="text-2xl font-bold {{ $cashSession->difference >= 0 ? 'text-red-600' : 'text-emerald-600' }} mt-1">
                        {{ $cashSession->difference >= 0 ? '+' : '' }}Rp {{ number_format($cashSession->difference, 0, ',', '.') }}
                        @if($cashSession->difference === 0) (Balanced) @endif
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $cashSession->note ?: '-' }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Daftar Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Transaksi ({{ $cashSession->sales->count() }})</h2>
        </div>

        @if($cashSession->sales->isEmpty())
            <div class="px-6 py-12 text-center text-gray-400">
                Belum ada transaksi di sesi ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Nota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cashSession->sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $sale->invoice_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->created_at->format('H:i:s') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->member->name ?? 'Umum' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->items->count() }} item</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.pos.receipt', $sale) }}"
                                       class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-200 transition">
                                        Struk
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection