@extends('layouts.admin')

@section('header_title', 'Tutup Sesi Kasir')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tutup Sesi Kasir</h1>
            <p class="text-sm text-gray-500 mt-1">Masukkan saldo akhir untuk menutup sesi</p>
        </div>

        <div class="mb-6 space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Kasir</span>
                <span class="font-semibold text-gray-900">{{ $cashSession->cashier_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Saldo Awal</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($cashSession->opening_balance, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Penjualan</span>
                <span class="font-semibold text-emerald-600">Rp {{ number_format($cashSession->total_sales, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm border-t border-gray-200 pt-2">
                <span class="text-gray-600">Saldo Diharapkan</span>
                <span class="font-bold text-indigo-600">Rp {{ number_format($cashSession->expected_balance, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-600 font-semibold text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.cash-sessions.close', $cashSession) }}" method="POST" class="space-y-5">
            @csrf
            @method('POST')

            <div>
                <label for="closing_balance" class="block text-sm font-semibold text-gray-700">Saldo Akhir (Rp):</label>
                <input type="number"
                       id="closing_balance"
                       name="closing_balance"
                       value="{{ old('closing_balance', $cashSession->expected_balance) }}"
                       required
                       min="0"
                       step="100"
                       class="w-full p-3 border border-gray-200 rounded-xl text-sm mt-1 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition"
                       placeholder="Masukkan uang aktual di kasir">
                @error('closing_balance')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Jumlah uang aktual yang ada di kasir saat ini</p>
            </div>

            <div>
                <label for="note" class="block text-sm font-semibold text-gray-700">Catatan (Opsional):</label>
                <textarea id="note"
                          name="note"
                          rows="3"
                          class="w-full p-3 border border-gray-200 rounded-xl text-sm mt-1 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition"
                          placeholder="Catatan tambahan...">{{ old('note') }}</textarea>
            </div>

            <div class="pt-2">
                @php
                    $selisih = $cashSession->expected_balance - (old('closing_balance', $cashSession->expected_balance));
                                        $differenceColor = $selisih > 0 ? 'text-red-600' : ($selisih < 0 ? 'text-emerald-600' : 'text-gray-900');
                @endphp
                <div class="bg-gray-50 p-3 rounded-xl text-center">
                    <span class="text-xs text-gray-500">Selisih (Sistem - Aktual):</span>
                                            <span id="difference-display" class="ml-2 font-bold text-lg {{ $differenceColor }}">
                        Rp {{ number_format($selisih, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-amber-700 transition cursor-pointer shadow-md hover:shadow-amber-500/20">
                Tutup Sesi
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('admin.cash-sessions.index') }}" class="text-sm font-medium text-gray-400 hover:text-indigo-600 transition">
                ← Kembali ke Daftar Sesi
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('closing_balance').addEventListener('input', function() {
        const expected = {{ $cashSession->expected_balance }};
        const actual = parseFloat(this.value) || 0;
        const diff = expected - actual;

        const display = document.getElementById('difference-display');
        display.innerText = 'Rp ' + diff.toLocaleString('id-ID');

        if (diff > 0) {
            display.className = 'ml-2 font-bold text-lg text-red-600';
        } else if (diff < 0) {
            display.className = 'ml-2 font-bold text-lg text-emerald-600';
        } else {
            display.className = 'ml-2 font-bold text-lg text-gray-900';
        }
    });
</script>
@endsection