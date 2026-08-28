@extends('layouts.admin')

@section('header_title', 'Restok Produk')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
        
        @if($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200">
                <ul class="list-disc pl-4 space-y-1 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Restok Produk: {{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Stok saat ini: <strong class="text-gray-900">{{ $product->stock }} Unit</strong></p>
        </div>

        <form method="POST" action="{{ route('admin.products.restock.process', $product) }}" class="space-y-5">
            @csrf

            <div>
                <label for="qty" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tambahan Stok <span class="text-red-500">*</span></label>
                <input type="number" 
                       id="qty" 
                       name="qty" 
                       value="{{ old('qty') }}" 
                       required 
                       min="1"
                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                @error('qty')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea id="note" 
                          name="note" 
                          rows="3" 
                          placeholder="Contoh: Restok dari supplier XYZ"
                          class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-3 border">{{ old('note') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.products.index') }}" 
                   class="px-4 py-2 rounded-lg bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    Simpan Restok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection