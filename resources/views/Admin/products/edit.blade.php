@extends('layouts.admin')

@section('header_title', 'Edit Produk')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 max-w-4xl mx-auto">

  <!-- 🟢 1. ERROR ALERT -->
  @if($errors->any())
    <div class="mb-8 rounded-2xl bg-red-50 p-4 border border-red-200">
      <div class="flex">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan saat memperbarui data:</h3>
          <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <!-- 🟢 2. FORM UTAMA EDIT PRODUK -->
  <form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf
    @method('PUT')

    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-10">
        <h2 class="text-lg font-semibold text-gray-900">Perbarui Data Produk</h2>
        <p class="mt-1 text-sm text-gray-600">Ubah rincian informasi untuk produk <strong>{{ $product->name }}</strong>.</p>

        <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
          
          <!-- Nama Produk -->
          <div class="sm:col-span-3">
            <label for="name" class="block text-sm/6 font-medium text-gray-900">
              Nama Produk <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
              <input id="name" 
                     type="text" 
                     name="name" 
                     value="{{ old('name', $product->name) }}" 
                     required
                     placeholder="Contoh: Makanan Kucing Whiskas 1kg"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- Barcode -->
          <div class="sm:col-span-3">
            <label for="barcode" class="block text-sm/6 font-medium text-gray-900">
              Kode Barcode / SKU
            </label>
            <div class="mt-2">
              <input id="barcode" 
                     type="text" 
                     name="barcode" 
                     value="{{ old('barcode', $product->barcode) }}" 
                     placeholder="Contoh: 899123456789"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 font-mono" />
            </div>
          </div>

          <!-- Kategori -->
          <div class="sm:col-span-2">
            <label for="category_id" class="block text-sm/6 font-medium text-gray-900">
              Kategori Produk <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
              <select id="category_id" 
                      name="category_id" 
                      required
                      class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Harga Beli -->
          <div class="sm:col-span-2">
            <label for="buy_price" class="block text-sm/6 font-medium text-gray-900">
              Harga Beli (Modal) Rp <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
              <input id="buy_price" 
                     type="number" 
                     step="0.01" 
                     name="buy_price" 
                     value="{{ old('buy_price', $product->buy_price) }}" 
                     required
                     placeholder="0"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- Harga Jual -->
          <div class="sm:col-span-2">
            <label for="sell_price" class="block text-sm/6 font-medium text-gray-900">
              Harga Jual Rp <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
              <input id="sell_price" 
                     type="number" 
                     step="0.01" 
                     name="sell_price" 
                     value="{{ old('sell_price', $product->sell_price) }}" 
                     required
                     placeholder="0"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-indigo-600 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 font-bold" />
            </div>
          </div>

          <!-- INFORMASI STOK SAAT INI -->
          <div class="col-span-full">
            <div class="rounded-xl bg-blue-50/80 p-4 border border-blue-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-blue-700 font-medium">Stok Produk Saat Ini</p>
                  <p class="text-lg font-bold text-blue-900">{{ $product->stock }} Unit</p>
                </div>
              </div>
              <a href="{{ route('admin.products.restock.form', $product) }}" 
                 class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-500 transition">
                + Tambah / Restock Stok
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- 🟢 3. TOMBOL AKSI (CANCEL & UPDATE) -->
    <div class="mt-6 flex items-center justify-end gap-x-4">
      <a href="{{ route('admin.products.index') }}" 
         class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 border border-gray-300 hover:bg-gray-50 transition">
        Batal
      </a>
      <button type="submit" 
              class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
        Update Produk
      </button>
    </div>
  </form>
</div>
@endsection